<?php

namespace Programmeruz\LaravelCreator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
            Artisan::call('migrate');
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
            $this->generateController($request->model_name, $request->has_swagger, $request->columns);
            $this->appendToApiRoutes($request->model_name);
            $data[] = "controller generated";

            if($request->has_swagger === 'true') {
                $exitCode = Artisan::call('l5-swagger:generate');
            }

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


    public function generateController($modelName, $has_swagger, $columns)
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
 * @OA\Get(
 *      path="/MODEL_VARIABLE",
 *      security={{"api":{}}},
 *      operationId="MODEL_VARIABLE_index",
 *      summary="Get all MODEL_NAMES",
 *      description="Retrieve all MODEL_NAMES",
 *      tags={"MODEL_NAME API CRUD"},
 * )
 */';


            $swaggerGetSingle = '/**
 * @OA\Get(
 *      path="/MODEL_VARIABLE/{id}",
 *      security={{"api":{}}},
 *      operationId="MODEL_VARIABLE_show",
 *      summary="Get a single MODEL_NAME by ID",
 *      description="Retrieve a single MODEL_NAME by its ID",
 *      tags={"MODEL_NAME API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the MODEL_NAME to retrieve",
 *          @OA\Schema(type="integer")
 *      ),
 * )
 */';


            $swaggerCreate = '/**
 * @OA\Post(
 *      path="/MODEL_VARIABLE",
 *      security={{"api":{}}},
 *      operationId="MODEL_VARIABLE_store",
 *      summary="Create a new MODEL_NAME",
 *      description="Add a new MODEL_NAME",
 *      tags={"MODEL_NAME API CRUD"},
 *      @OA\RequestBody(required=true, description="MODEL_NAME save",
 *           @OA\MediaType(mediaType="multipart/form-data",
 *              @OA\Schema(type="object", required=COLUMNS_REQUIRED,
 *                 COLUMN_DEFINITIONS
 *              )
 *          )
 *      ),
 * )
 */';


            $swaggerUpdate = '/**
 * @OA\Put(
 *      path="/MODEL_VARIABLE/{id}",
 *      security={{"api":{}}},
 *      operationId="MODEL_VARIABLE_update",
 *      summary="Update a MODEL_NAME by ID",
 *      description="Update a specific MODEL_NAME by its ID",
 *      tags={"MODEL_NAME API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the MODEL_NAME to update",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\RequestBody(required=true, description="MODEL_NAME update",
 *           @OA\MediaType(mediaType="multipart/form-data",
 *              @OA\Schema(type="object", required=COLUMNS_REQUIRED,
 *                 COLUMN_DEFINITIONS
 *              )
 *          )
 *      ),
 * )
 */';


            $swaggerDelete = '/**
 * @OA\Delete(
 *      path="/MODEL_VARIABLE/{id}",
 *      security={{"api":{}}},
 *      operationId="MODEL_VARIABLE_delete",
 *      summary="Delete a MODEL_NAME by ID",
 *      description="Remove a specific MODEL_NAME by its ID",
 *      tags={"MODEL_NAME API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the MODEL_NAME to delete",
 *          @OA\Schema(type="integer")
 *      ),
 * )
 */';

        }

        $columnDefinitions = [];
        foreach($columns as $column) {
            $type = "string"; // default type
            switch($column['type']) {
                case "integer":
                case "bigInteger":
                case "unsignedBigInteger":
                case "smallInteger":
                case "unsignedInteger":
                case "tinyInteger":
                    $type = "integer";
                    break;
                case "float":
                case "double":
                case "decimal":
                    $type = "number";
                    break;
                case "boolean":
                    $type = "boolean";
                    break;
                case "timestamp":
                case "date":
                case "dateTime":
                case "dateTimeTz":
                    $type = "string";
                    $format = "date-time";
                    break;
                default:
                    $type = "string";
            }

            $property = '@OA\Property(property="'.$column['name'].'", type="'.$type;
            if(isset($format)) {
                $property .= '", format="'.$format;
            }
            $property .= '", example="Your example value here")';
            $columnDefinitions[] = $property;
        }

        $columnDefinitionsStr = implode(",\n", $columnDefinitions);
        $swaggerUpdate = str_replace('COLUMN_DEFINITIONS', $columnDefinitionsStr, $swaggerUpdate);
        $swaggerCreate = str_replace('COLUMN_DEFINITIONS', $columnDefinitionsStr, $swaggerCreate);

        $requiredColumns = [];

        foreach ($columns as $column) {
            if ($column['nullable'] == 'false') {
                $requiredColumns[] = $column['name'];
            }
        }

        $COLUMNS_REQUIRED = '"' . implode('", "', $requiredColumns) . '"';
        $swaggerUpdate = str_replace('COLUMNS_REQUIRED', $COLUMNS_REQUIRED, $swaggerUpdate);
        $swaggerCreate = str_replace('COLUMNS_REQUIRED', $COLUMNS_REQUIRED, $swaggerCreate);

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
