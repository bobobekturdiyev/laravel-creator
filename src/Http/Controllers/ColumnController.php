<?php

namespace Programmeruz\LaravelCreator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ColumnController extends Controller
{
    public function generateColumns(Request $request)
    {


        $data = [];
        if ($request->model === 'true') {
            $this->generateModel($request->columns, $request->model_name);
            $data[] = "model generated";
        }

        if ($request->migration === 'true') {
            $this->generateMigration($request->columns, $request->model_name);
            $data[] = "migration generated";
        }
        if ($request->factory === 'true') {
            $this->generateFactory($request->columns, $request->model_name);
            $this->generateSeeder($request->model_name);
            $data[] = "seeder & factory generated";
        }

        if ($request->resource === 'true') {
            $this->generateResource($request->columns, $request->model_name);
            $data[] = "resource generated";
        }

        if ($request->controller === 'true') {
            $this->generateController($request->model_name, $request->has_swagger);
            $this->appendToApiRoutes($request->model_name);
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

    private function generateMigration($columns, $modelName)
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
                $defaultCode = "->default('" . str_replace(' ', '', $column['default']). "')";
            }

            if (isset($column['nullable']) && $column['nullable'] === "true") {
                $nullableCode = "->nullable()";
            }

            switch ($type) {
                case 'integer':
                    $code = "\$table->integer('{$columnName}')";
                    break;
                case 'string':
                    $length = $column['length'];
                    $code = "\$table->string('{$columnName}', {$length})";
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

                    for($i = 0; $i < count($enumValues); $i++){
                        $enumValues[$i] = str_replace(' ', '',  $enumValues[$i]);
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
                $columnDefinitions .= "\t\t\t{$code}{$nullableCode}{$defaultCode};\n";
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
                    $field = "'{$columnName}' => '". str_replace(" ", "", $randomValue)."',";
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

    public function generateResource($columns, $modelName)
    {

        // Fetch template content
        $templateContent = __DIR__ . '/../../storage/app/templates/resource_template.txt';
        $templateContent = File::get($templateContent);


        // Replace placeholders with the actual model name
        $templateContent = str_replace('MODEL_NAME', $modelName, $templateContent);

        // Build the column mapping string
        $columnMapping = '';
        foreach ($columns as $column) {
            $columnName = $column['name'];
            $columnMapping .= "'$columnName' => \$this->$columnName,\n\t\t\t";
        }

        // Replace COLUMN_MAPPING placeholder with actual mappings
        $templateContent = str_replace('COLUMN_MAPPING', rtrim($columnMapping, ",\n\t\t\t"), $templateContent);

        $resourceDirectory = app_path("Http/Resources");
        if (!File::exists($resourceDirectory)) {
            File::makeDirectory($resourceDirectory, 0755, true);
        }

        // Save the resource file in the appropriate directory
        $fileName = "{$modelName}Resource.php";
        File::put(app_path("Http/Resources/$fileName"), $templateContent);
    }


    public function generateController($modelName, $has_swagger)
    {
        $modelVariable = strtolower($modelName);
        $modelResource = "{$modelName}Resource";
        $controllerName = "{$modelName}Controller";

        $templateContent = File::get(__DIR__ . '/../../storage/app/templates/controller_template.txt');
        $swaggerGetAll = '';
        $swaggerGetSingle = '';
        $swaggerCreate = '';
        $swaggerUpdate = '';
        $swaggerDelete = '';

        if($has_swagger == "true") {
            $swaggerGetAll = '/**
         * @OA\Get(path="/MODEL_VARIABLE",
         *   summary="Get all MODEL_NAMES",
         *   @OA\Response(response="200", description="List of MODEL_NAMES")
         * )
         */';

            $swaggerGetSingle = '/**
         * @OA\Get(path="/MODEL_VARIABLE/{id}",
         *   summary="Get a single MODEL_NAME by ID",
         *   @OA\Response(response="200", description="Single MODEL_NAME")
         * )
         */';

            $swaggerCreate = '/**
         * @OA\Post(path="/MODEL_VARIABLE",
         *   summary="Create a new MODEL_NAME",
         *   @OA\Response(response="201", description="Newly created MODEL_NAME")
         * )
         */';

            $swaggerUpdate = '/**
         * @OA\Put(path="/MODEL_VARIABLE/{id}",
         *   summary="Update a MODEL_NAME by ID",
         *   @OA\Response(response="200", description="Updated MODEL_NAME")
         * )
         */';

            $swaggerDelete = '/**
         * @OA\Delete(path="/MODEL_VARIABLE/{id}",
         *   summary="Delete a MODEL_NAME by ID",
         *   @OA\Response(response="204", description="MODEL_NAME deleted successfully")
         * )
         */';
        }

        $swaggerGetAll = str_replace('MODEL_VARIABLE', $modelVariable, $swaggerGetAll);
        $swaggerGetSingle = str_replace('MODEL_VARIABLE', $modelVariable, $swaggerGetSingle);
        $swaggerCreate = str_replace('MODEL_VARIABLE', $modelVariable, $swaggerCreate);
        $swaggerUpdate = str_replace('MODEL_VARIABLE', $modelVariable, $swaggerUpdate);
        $swaggerDelete = str_replace('MODEL_VARIABLE', $modelVariable, $swaggerDelete);

        $swaggerGetAll = str_replace('MODEL_NAME', $modelName, $swaggerGetAll);
        $swaggerGetSingle = str_replace('MODEL_NAME', $modelName, $swaggerGetSingle);
        $swaggerCreate = str_replace('MODEL_NAME', $modelName, $swaggerCreate);
        $swaggerUpdate = str_replace('MODEL_NAME', $modelName, $swaggerUpdate);
        $swaggerDelete = str_replace('MODEL_NAME', $modelName, $swaggerDelete);

        // Replace the placeholders with real data
        $replacements = [
            'MODEL_NAME' => $modelName,
            'MODEL_VARIABLE' => $modelVariable,
            'MODEL_RESOURCE' => $modelResource,
            'MODEL_CONTROLLER' => $controllerName,
            'SWAGGER_GET_ALL' => $swaggerGetAll,
            'SWAGGER_GET_SINGLE' => $swaggerGetSingle,
            'SWAGGER_CREATE' => $swaggerCreate,
            'SWAGGER_UPDATE' => $swaggerUpdate,
            'SWAGGER_DELETE' => $swaggerDelete
        ];

        foreach ($replacements as $placeholder => $replacement) {
            $templateContent = str_replace($placeholder, $replacement, $templateContent);
        }

        $controllerDirectory = app_path("Http/Controllers");
        $fileName = "$controllerName.php";

        File::put("$controllerDirectory/$fileName", $templateContent);

        $this->controllerName = $controllerName;
    }

    private $controllerName;

    public function appendToApiRoutes($modelName) {
        $modelVariable = \Illuminate\Support\Str::snake($modelName);
        $controllerName = $this->controllerName;

        $routeToAdd = "Route::apiResource('{$modelVariable}', \App\Http\Controllers\\$controllerName::class);\n";

        $apiRoutesPath = base_path('routes/api.php');

        // Open the file in append mode and add the new route
        file_put_contents($apiRoutesPath, $routeToAdd, FILE_APPEND);
    }

}
