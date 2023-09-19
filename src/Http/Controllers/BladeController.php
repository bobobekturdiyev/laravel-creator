<?php

namespace Programmeruz\LaravelCreator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Symfony\Component\Translation\t;

class BladeController extends Controller
{
    public function generateColumns(Request $request)
    {
        $data = [];
//        if ($request->model === 'true') {
//            $this->generateModel($request->columns, $request->model_name);
//            $data[] = "model generated";
//        }
//
//        if ($request->migration === 'true') {
//            $this->generateMigration($request->columns, $request->model_name, $request->foreignKeys);
//            $data[] = "migration generated";
//            Artisan::call('migrate');
//        }
//
//        if ($request->factory === 'true') {
//            $this->generateFactory($request->columns, $request->model_name);
//            $this->generateSeeder($request->model_name);
//            $data[] = "seeder & factory generated";
//        }

        $this->createAndCopyViews();

        if ($request->controller) {
            $this->generateController($request->model_name, $request->columns);
            $this->appendToRoutes($request->model_name);
            $data[] = "controller generated";
        }


        echo json_encode($data);
    }

    private function generateModel($columns, $modelName)
    {
        $modelTemplate = __DIR__ . '/../../storage/app/templates/model_template.txt';
        $modelTemplate = File::get($modelTemplate);

        $columnNames = '';
        foreach ($columns as $column) {
            $columnNames .= "\n        '{$column['name']}',";
        }
        $modelContent = str_replace(['{MODEL_NAME}', '{COLUMN_NAMES}'], [$modelName, $columnNames], $modelTemplate);
        $fileLocation = app_path("Models/{$modelName}.php");
        File::put($fileLocation, $modelContent);


    }

    private function generateMigration($columns, $modelName, $foreignKeys)
    {

        $migrationTemplate = __DIR__ . '/../../storage/app/templates/migration_template.txt';
        $migrationTemplate = File::get($migrationTemplate);

        $migrationName = Str::plural(Str::snake($modelName));
        $tableName = date('Y_m_d_His') . "_create_{$migrationName}_table.php";

        $columnDefinitions = '';
        foreach ($columns as $column) {
            $columnName = $column['name'];
            $type = $column['type'];

            $defaultCode = '';
            $nullableCode = '';

            if (isset($column['default']) && !empty($column['default'])) {
                $defaultCode = "->default('" . str_replace(' ', '', $column['default']) . "')";
            }

            if (isset($column['nullable']) && $column['nullable'] === "true") {
                $nullableCode = "->nullable()";
            }

            switch ($type) {
                case 'bigIncrements':
                    $code = "\$table->id()";
                    break;
                case 'integer':
                    $code = "\$table->integer('{$columnName}')";
                    break;
                case 'unsignedBigInteger':
                    $code = "\$table->unsignedBigInteger('{$columnName}')";
                    break;
                case 'string':
                    isset($column['length']) ? $length = ', ' . $column['length'] : $length = '';
                    $code = "\$table->string('{$columnName}'{$length})";
                    break;
                case 'text':
                    $code = "\$table->text('{$columnName}')";
                    break;
                case 'longtext':
                    $code = "\$table->longText('{$columnName}')";
                    break;
                case 'date':
                    $code = "\$table->date('{$columnName}')";
                    break;
                case 'enum':
                    $enumValues = explode(',', $column['length']);

                    for ($i = 0; $i < count($enumValues); $i++) {
                        $enumValues[$i] = str_replace(' ', '', $enumValues[$i]);
                    }

                    $formattedEnumValues = implode("', '", $enumValues);
                    $code = "\$table->enum('{$columnName}', ['$formattedEnumValues'])";
                    break;
                // You can add more column types as necessary
                // Example:
                case 'tinyint':
                    $code = "\$table->tinyInteger('{$columnName}')";
                    break;
                case 'smallint':
                    $code = "\$table->smallInteger('{$columnName}')";
                    break;
                case 'mediumint':
                    $code = "\$table->mediumInteger('{$columnName}')";
                    break;
                case 'bigint':
                    $code = "\$table->bigInteger('{$columnName}')";
                    break;
                case 'float':
                    $code = "\$table->float('{$columnName}')";
                    break;
                case 'double':
                    $code = "\$table->double('{$columnName}', 15, 8)"; // Assuming precision 15 and scale 8 as a common case
                    break;
                case 'decimal':
                    $code = "\$table->decimal('{$columnName}', 8, 2)"; // Assuming precision 8 and scale 2 as a common case
                    break;
                case 'timestamp':
                    $code = "\$table->timestamp('{$columnName}')";
                    break;
                case 'time':
                    $code = "\$table->time('{$columnName}')";
                    break;
                case 'datetime':
                    $code = "\$table->dateTime('{$columnName}', 0)"; // Assuming precision 0 as a common case
                    break;
                case 'boolean':
                    $code = "\$table->boolean('{$columnName}')";
                    $defaultCode = "->default({$column['default']})";
                    break;
                case 'json':
                    $code = "\$table->json('{$columnName}')";
                    break;
                default:
                    $code = ""; // Or handle other types as necessary
                    break;
            }

            if ($code != "") {
                $columnDefinitions .= "{$code}{$nullableCode}{$defaultCode};\n\t\t\t";
            }


        }
        if(isset($foreignKeys)){
            foreach ($foreignKeys as $foreignKey){
                $primary_key_model = Str::plural(Str::snake($foreignKey['primary_key_model']));
                $columnDefinitions .= "\$table->foreign('{$foreignKey['foreign_key']}')->references('id')->on('{$primary_key_model}');\n\t\t\t";
            }
        }

        $migrationContent = str_replace(['{MODEL_NAME}', '{TABLE_NAME}', '{COLUMN_DEFINITIONS}'], [$modelName, $migrationName, $columnDefinitions], $migrationTemplate);
        $migrationFilePath = base_path("database/migrations/{$tableName}");
        File::put($migrationFilePath, $migrationContent);
    }

    public function generateFactory($columns, $modelName)
    {
        $factoryFields = "";

        foreach ($columns as $column) {
            $columnName = $column['name'];
            $type = $column['type'];

            switch ($type) {
                case 'integer':
                    $field = "'{$columnName}' => \$this->faker->numberBetween(1, 1000),";
                    break;
                case 'string':
                    $field = "'{$columnName}' => \$this->faker->word,";
                    break;
                case 'text':
                    $field = "'{$columnName}' => \$this->faker->sentence,";
                    break;
                case 'longtext':
                    $field = "'{$columnName}' => \$this->faker->text,";
                    break;
                case 'date':
                    $field = "'{$columnName}' => \$this->faker->date,";
                    break;
                case 'datetime':
                    $field = "'{$columnName}' => \$this->faker->dateTime,";
                    break;
                case 'enum':
                    $enumValues = explode(',', $column['length']);
                    $randomValue = $enumValues[array_rand($enumValues)];
                    $field = "'{$columnName}' => '" . str_replace(" ", "", $randomValue) . "',";
                    break;
                case 'boolean':
                    $field = "'{$columnName}' => \$this->faker->boolean,";
                    break;
                case 'json':
                    $field = "'{$columnName}' => [],"; // You might need more specific JSON data here.
                    break;
                case 'float':
                case 'double':
                    $field = "'{$columnName}' => \$this->faker->randomFloat(),";
                    break;
                case 'decimal':
                    $field = "'{$columnName}' => \$this->faker->randomFloat(2, 1, 100),"; // 2 decimal places, between 1 and 100
                    break;
                case 'uuid':
                    $field = "'{$columnName}' => \$this->faker->uuid,";
                    break;
                case 'ipAddress':
                    $field = "'{$columnName}' => \$this->faker->ipv4,";
                    break;
                case 'macAddress':
                    $field = "'{$columnName}' => \$this->faker->macAddress,";
                    break;
                // ... you can add more cases as required
                default:
                    $field = ""; // Or handle other types as necessary
                    break;
            }

            $factoryFields .= "\t\t\t$field\n";
        }

        $templateContent = __DIR__ . '/../../storage/app/templates/factory_template.txt';
        $templateContent = File::get($templateContent);

        $templateContent = str_replace('MODEL_NAME', $modelName, $templateContent);
        $templateContent = str_replace('FACTORY_FIELDS', $factoryFields, $templateContent);

        $fileName = "{$modelName}Factory.php";
        File::put(database_path("factories/$fileName"), $templateContent);
    }

    public function generateSeeder($modelName)
    {
        // Fetch template content
        $templateContent = __DIR__ . '/../../storage/app/templates/seeder_template.txt';
        $templateContent = File::get($templateContent);

        // Replace placeholders with the actual model name
        $templateContent = str_replace('MODEL_NAME', $modelName, $templateContent);

        // Save the seeder in the seeders directory
        $fileName = "{$modelName}Seeder.php";
        File::put(database_path("seeders/$fileName"), $templateContent);

        return response()->json(['message' => "Seeder file saved successfully!"]);
    }


    public function generateController($modelName, $columns)
    {
        $modelVariable = strtolower($modelName);
        $controllerName = "{$modelName}Controller";
        $filePathSidebar = base_path('resources/views/layouts/simple/sidebar.blade.php');
        $filePathDashboard = base_path('resources/views/dashboard.blade.php');

        $templateContent = File::get(__DIR__ . '/../../storage/app/templates/blade_controller_template.txt');
        $templateReadBlade = File::get(__DIR__ . '/../../storage/app/templates/read_blade_template.txt');
        $templateAddBlade = File::get(__DIR__ . '/../../storage/app/templates/add_blade_template.txt');
        $templateEditBlade = File::get(__DIR__ . '/../../storage/app/templates/edit_blade_template.txt');
        $templateDeleteBlade = File::get(__DIR__ . '/../../storage/app/templates/delete_blade_template.txt');
        $templateBladeSidebar = File::get($filePathSidebar);
        $templateBladeDashboard = File::get($filePathDashboard);

        $addModel = [];
        $editModel = [];
        $deleteModel = [];
        $columnDefinitions = [];
        $columnRowName = [];
        $validator = [];
        $model = [];
        $modelDelete = [];
        $columnRowName[] = '<th scope="col">#</th>';
        foreach ($columns as $column) {
            $validator_type = 'string|max:255';
            $type = "string"; // default type
            $format = "";     // default format, empty

            switch ($column['type']) {
                case "integer":
                case "bigInteger":
                case "bigIncrements":
                case "unsignedBigInteger":
                case "smallInteger":
                case "unsignedInteger":
                case "tinyInteger":
                    $type = "integer";
                    $validator_type = 'integer';
                    break;
                case "float":
                case "double":
                case "decimal":
                    $type = "number";
                    $validator_type = 'integer';
                    break;
                case "boolean":
                    $type = "boolean";
                    $validator_type = "boolean";
                    break;
                case "timestamp":
                case "date":
                case "dateTime":
                case "dateTimeTz":
                    $type = "string";
                    $format = "date-time";
                    $validator_type = 'date';
                    break;
                default:
                    $type = "string";
            }

            if($column['isFile'] == "true"){
                $columnDefinitions[] = "<td><img src='{{\$model->{$column['name']}}}' width='100' alt=''></td>";
            }
            elseif ($column['name'] != "updated_at"){
            $columnDefinitions[] = "<td>{{\$model->{$column['name']}}}</td>";
            }

            if ($column['isFile'] == 'true') {
                $type = 'file';
                if($column['length']){
                    $validator_type = "mimes:" . $column['length'];
                }
                else{
                    $validator_type = "";
                }
            }
            if ($column['type'] != 'bigIncrements' and $column['name'] != "updated_at"){
                $this->input = ucwords(str_replace('_', ' ', $column['name']));
                $columnRowName[] = "<th scope='col'>$this->input</th>";
            }
            if ($column['type'] != 'bigIncrements' and $column['type'] != 'timestamp') {
                if ($column['isFile'] == 'true') {
                    $model[] = str_replace('COLUMN_NAME', $column['name'], $this->file);
                    $modelDelete[] = str_replace('COLUMN_NAME', $column['name'], $this->fileUnlik);
                    $addModel[] = "<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>{$this->input}</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control' name='{$column['name']}' type='file'>
                                            @error('{$column['name']}')
                                            <p class='text-danger'>{{\$message}}</p>
                                            @enderror
                                        </div>
                                    </div>";

                    $editModel[] = "<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>{$this->input}</label>
                                        <div class='col-2'>
                                            <img src='{{\$model->{$column['name']}}}' width='100' alt=''>
                                        </div>
                                    </div>
                                    <div class='mb-3 row'>
                                        <div class='col-3'></div>
                                        <div class='col-sm-7'>
                                                <input class='form-control' name='{$column['name']}' type='file'>
                                                @error('{$column['name']}')
                                                <p class='text-danger'>{{\$message}}</p>
                                                @enderror
                                        </div>
                                    </div>";

                    $deleteModel[] = "<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>{$this->input}</label>
                                        <div class='col-2'>
                                            <img src='{{\$model->{$column['name']}}}' width='100' alt=''>
                                        </div>
                                    </div>";

                }
                elseif ($column['type'] === 'text'){
                    $model[] = '$MODEL_VARIABLE->' . $column['name'] . " = " . '$request->' . $column['name'] . ';';
                    $addModel[] = "<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>{$this->input}</label>
                                        <div class='col-sm-9'>
                                            <textarea id='message' name='{$column['name']}' rows='1' cols='111' placeholder='{$this->input}'>{{value(old('{$column['name']}'))}}</textarea>
                                            @error('{$column['name']}')
                                            <p class='text-danger'>{{\$message}}</p>
                                            @enderror
                                        </div>
                                    </div>";
                    $editModel[] = "<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>{$this->input}</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control'  type='text' name='{$column['name']}' value='{{\$model->{$column['name']}}}' placeholder='{$this->input}'>
                                            @error('{$column['name']}')
                                            <p class='text-danger'>{{\$message}}</p>
                                            @enderror
                                        </div>
                                    </div>";
                    $deleteModel[] = "<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>{$this->input}</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control' disabled  type='text' name='{$column['name']}' value='{{\$model->{$column['name']}}}' placeholder='{$this->input}'>
                                            @error('{$column['name']}')
                                            <p class='text-danger'>{{\$message}}</p>
                                            @enderror
                                        </div>
                                    </div>";
                }
                else {
                    $model[] = '$MODEL_VARIABLE->' . $column['name'] . " = " . '$request->' . $column['name'] . ';';
                    $addModel[] = "<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>{$this->input}</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control'  type='text' name='{$column['name']}' value=\"{{old('{$column['name']}')}}\" placeholder='{$this->input}'>
                                            @error('{$column['name']}')
                                            <p class='text-danger'>{{\$message}}</p>
                                            @enderror
                                        </div>
                                    </div>";
                    $editModel[] = "<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>{$this->input}</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control'  type='text' name='{$column['name']}' value='{{\$model->{$column['name']}}}' placeholder='{$this->input}'>
                                            @error('{$column['name']}')
                                            <p class='text-danger'>{{\$message}}</p>
                                            @enderror
                                        </div>
                                    </div>";
                    $deleteModel[] = "<div class='mb-3 row'>
                                        <label class='col-sm-3 col-form-label'>{$this->input}</label>
                                        <div class='col-sm-9'>
                                            <input class='form-control' disabled  type='text' name='{$column['name']}' value='{{\$model->{$column['name']}}}' placeholder='{$this->input}'>
                                            @error('{$column['name']}')
                                            <p class='text-danger'>{{\$message}}</p>
                                            @enderror
                                        </div>
                                    </div>";
                }
                if ($column['nullable'] == 'false') {
                    $validator[] = "'{$column['name']}' => " . "'required|{$validator_type}'";
                }
            }
        }
        $columnRowName[] = '<th scope="col">Action</th>';
        $addModelStr = implode("\n\t\t\t\t\t\t\t\t\t", $addModel);
        $editModelStr = implode("\n\t\t\t\t\t\t\t\t\t", $editModel);
        $deleteModelStr = implode("\n\t\t\t\t\t\t\t\t\t", $deleteModel);
        $columnRowNameStr = implode("\n\t\t\t\t\t\t\t\t", $columnRowName);
        $columnDefinitionsStr = implode("\n\t\t\t\t\t\t\t\t", $columnDefinitions);
        $modelStr = implode("\n\t\t", $model);
        $modelDeleteStr = implode("\n", $modelDelete);
        $validatorStr = implode(",\n\t\t\t", $validator);
        $modelStr = str_replace('MODEL_VARIABLE', $modelVariable, $modelStr);

        $addItem = "<li class='sidebar-list'>
                        <label class='badge badge-light-info'></label><a class='sidebar-link sidebar-title' href='{{route('MODEL_VARIABLE.index')}}'>
                            <svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                <g>
                                    <g>
                                        <path d='M14.3053 15.45H8.90527' stroke='#130F26' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                        <path d='M12.2604 11.4387H8.90442' stroke='#130F26' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                        <path fill-rule='evenodd' clip-rule='evenodd' d='M20.1598 8.3L14.4898 2.9C13.7598 2.8 12.9398 2.75 12.0398 2.75C5.74978 2.75 3.64978 5.07 3.64978 12C3.64978 18.94 5.74978 21.25 12.0398 21.25C18.3398 21.25 20.4398 18.94 20.4398 12C20.4398 10.58 20.3498 9.35 20.1598 8.3Z' stroke='#130F26' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                        <path d='M13.9342 2.83276V5.49376C13.9342 7.35176 15.4402 8.85676 17.2982 8.85676H20.2492' stroke='#130F26' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'></path>
                                    </g>
                                </g>
                            </svg><span>MODEL_NAME</span></a>
                    </li>
                    " . '<!-- ADD_ITEM -->';
        $addDashboard = "<div class='col-3 p-3'>
            <div class='card'>
                <div class='card-body'>
                    <a href=\"{{route('project.index')}}\">
                        <h4>Project</h4>
                    </a>
                </div>
            </div>
        </div>
        " . '<!-- ADD_ITEM -->';


        $replacements = [
            'MODEL_NAME' => $modelName,
            'MODEL_DELETE' => $modelDeleteStr,
            'MODEL_VARIABLE' => $modelVariable,
            'MODEL_CONTROLLER' => $controllerName,
            'VALIDATOR' => $validatorStr,
            'MODEL_REQUEST' => $modelStr,
            'DEFINITIONS' => $columnDefinitionsStr,
            'ROW_NAME' => $columnRowNameStr,
            'ADD_MODEL' => $addModelStr,
            'EDIT_MODEL' => $editModelStr,
            'DELETE_MODEL' => $deleteModelStr,
        ];
        $bladeTemplates = [];
        foreach ($replacements as $placeholder => $replacement) {
            $templateContent = str_replace($placeholder, $replacement, $templateContent);
            $templateReadBlade = str_replace($placeholder, $replacement, $templateReadBlade);
            $templateAddBlade = str_replace($placeholder, $replacement, $templateAddBlade);
            $templateEditBlade = str_replace($placeholder, $replacement, $templateEditBlade);
            $templateDeleteBlade = str_replace($placeholder, $replacement, $templateDeleteBlade);
            $addItem = str_replace($placeholder, $replacement, $addItem);
            $addDashboard = str_replace($placeholder, $replacement, $addDashboard);
        }

        if(!$this->checkTextInFile($filePathSidebar, $addItem)){
            $templateBladeSidebar = str_replace("<!-- ADD_ITEM -->", $addItem, $templateBladeSidebar);
        }
        if(!$this->checkTextInFile($filePathDashboard, $addDashboard)){
            $templateBladeDashboard = str_replace("<!-- ADD_ITEM -->", $addDashboard, $templateBladeDashboard);
        }

        $bladeTemplates["$modelVariable"] = $templateReadBlade;
        $bladeTemplates["add-$modelVariable"] = $templateAddBlade;
        $bladeTemplates["edit-$modelVariable"] = $templateEditBlade;
        $bladeTemplates["delete-$modelVariable"] = $templateDeleteBlade;
        $this->createBladeViews($modelVariable, $bladeTemplates);

        $controllerDirectory = app_path("Http/Controllers");
        $fileName = "$controllerName.php";
        $path = "$controllerDirectory/$fileName";

        File::put($path, $templateContent);
        File::put($filePathSidebar, $templateBladeSidebar);
        File::put($filePathDashboard, $templateBladeDashboard);
        $this->controllerName = $controllerName;
    }


    private function ensureTrailingSlash($inputString)
    {
        if (substr($inputString, -1) !== '/') {
            // Add a trailing slash if it's missing
            $inputString .= '/';
        }
        return $inputString;
    }

    private function removeTrailingSlash($input)
    {
        if (substr($input, -1) === '\\') {
            return substr($input, 0, -1);
        }
        return $input;
    }

    private function slashes($input)
    {
        if (substr($input, 0, 1) === '/') {
            $input = substr($input, 1);
        }

        return str_replace('/', '\\', $input);
    }

    private $controllerName;

    public function appendToRoutes($modelName)
    {
        $modelVariable = \Illuminate\Support\Str::snake($modelName);
        $controllerName = $this->controllerName;

        $routeToAdd = "Route::resource('{$modelVariable}', \App\Http\Controllers\\$controllerName::class);\n";

            $webRoutesPath = base_path("routes/web.php");

        // Open the file in append mode and add the new route
        if(!$this->checkTextInFile($webRoutesPath, $routeToAdd)){
            file_put_contents($webRoutesPath, $routeToAdd, FILE_APPEND);
        }
    }

    public function manipulateString($input) {
        return rtrim($input, '/');
    }

    private function createBladeViews($folderName, $bladeTemplates)
    {
        $basePath = resource_path('views');  // resources/views papkasi yoli
        $folderPath = $basePath . '/' . trim($folderName, '/');

        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        foreach ($bladeTemplates as $blade => $template) {
            $viewPath = $folderPath . '/' . $blade . '.blade.php';
            file_put_contents($viewPath, $template);
        }
    }

    private function createAndCopyViews()
    {
        $laravelViewsPath = base_path('resources/views');
        $layoutsPath = $laravelViewsPath . '/layouts';
        $simplePath = $layoutsPath . '/simple';

        $is_layouts = false;
        $is_simple = false;
        if (!is_dir($layoutsPath)) {
            mkdir($layoutsPath, 0755, true);
            $is_layouts = true;
        }

        if (!is_dir($simplePath)) {
            mkdir($simplePath, 0755, true);
            $is_simple = true;
        }

        if($is_layouts or $is_simple) {
            $packageBladePath = base_path('vendor/programmeruz/laravel-creator/src/resources/views/layouts/simple');
            $files = scandir($packageBladePath);

            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $source = $packageBladePath . '/' . $file;
                    $destination = $simplePath . '/' . $file;

                    copy($source, $destination);
                }
            }
        }
    }

    private function checkTextInFile($filePath, $searchString)
    {
        $fileContents = file_get_contents($filePath);
        $indexOfSearchString = strpos($fileContents, $searchString);

        return $indexOfSearchString;
    }



    private $file = 'if ($request->hasFile("COLUMN_NAME")) {
            $file = $request->file("COLUMN_NAME");
            $filename = time(). "_" . $file->getClientOriginalName();
            if ($MODEL_VARIABLE->COLUMN_NAME) {
                $oldFilePath = \'uploads/COLUMN_NAME/\'.basename($MODEL_VARIABLE->COLUMN_NAME);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            $file->move("uploads/COLUMN_NAME", $filename);
            $MODEL_VARIABLE->COLUMN_NAME = asset("uploads/COLUMN_NAME/$filename");
        }';

    private $fileUnlik = 'if ($MODEL_VARIABLE->COLUMN_NAME) {
            $oldFilePath = \'uploads/COLUMN_NAME/\'.basename($MODEL_VARIABLE->COLUMN_NAME);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }';
    private $input = null;
}
