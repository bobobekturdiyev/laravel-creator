<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        /* Checkbox labelning o'rtasida o'zgarishni ko'rsatish uchun */
        .form-check-label {
            display: flex;
            align-items: center;
        }

        /* Checkboxni qora chiziq bilan stilizatsiya qilish uchun */
        .form-check-input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            position: relative;
            border: 2px solid #000;
            outline: none;
            background-color: transparent;
            border-radius: 3px;
            cursor: pointer;
        }

        /* Checkboxni belgilagan holatda rangli qilish uchun */
        .form-check-input[type="checkbox"]:checked {
            background-color: #000;
        }

        /* Checkboxni stilizatsiyasida o'zgarishni animatsion qilish uchun */
        .form-check-input[type="checkbox"]:checked::after {
            content: "\2713"; /* Tik belgisi */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 14px;
            color: #fff;
        }
    </style>


    <title>Laravel Creator</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    &copy; Created by Bobobek Turdiev - Programmer UZ
                </li>
                <li class="nav-item">
                </li>
            </ul>

            <div class="form-inline my-2 my-lg-0">
                <a class="btn btn-success" href="{{url('/api/documentation')}}" target="_blank">Open Swagger</a>
            </div>

        </div>
    </div>
</nav>
<div class="wrapper">
    <div class="container">
        <form class="mt-5">
            @csrf
            <div class="row mb-3">
                <div class="col-6">
                    <label for="model" class="form-label">Model Name</label>
                    <input type="text" class="form-control" id="model" name="model" placeholder="Enter Model Name">
                </div>
            </div>

            <table class="table table-bordered" id="column_table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Length/Values</th>
                    <th scope="col">Default</th>
                    <th scope="col">Nullable</th>
                    <th scope="col">isFile</th>
                    <th scope="col">Actions</th>
                    <th scope="col" style="width: 1%;">Drag</th> <!-- This header is for the drag handle column -->
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>
                        <input type="text" name="col_name[]" class="form-control" placeholder="Column Name">
                    </td>
                    <td>
                        <select name="col_type[]" class="form-control" id="columnTypeSelect">
                            <option value="bigIncrements">Auto Increment (BIGINT)</option>
                            <option value="bigInteger">BIGINT</option>
                            <option value="binary">BINARY</option>
                            <option value="boolean">BOOLEAN</option>
                            <option value="char">CHAR</option>
                            <option value="date">DATE</option>
                            <option value="dateTime">DATETIME</option>
                            <option value="dateTimeTz">DATETIME (with timezone)</option>
                            <option value="decimal">DECIMAL</option>
                            <option value="double">DOUBLE</option>
                            <option value="enum">ENUM</option>
                            <option value="float">FLOAT</option>
                            <option value="increments">Auto Increment (INT)</option>
                            <option value="integer">INTEGER</option>
                            <option value="ipAddress">IP Address</option>
                            <option value="json">JSON</option>
                            <option value="jsonb">JSONB (PostgreSQL)</option>
                            <option value="longText">LONGTEXT</option>
                            <option value="macAddress">MAC Address</option>
                            <option value="mediumIncrements">Auto Increment (MEDIUMINT)</option>
                            <option value="mediumInteger">MEDIUMINT</option>
                            <option value="mediumText">MEDIUMTEXT</option>
                            <option value="morphs">Morphs</option>
                            <option value="nullableMorphs">Nullable Morphs</option>
                            <option value="nullableTimestamps">Nullable Timestamps</option>
                            <option value="rememberToken">Remember Token</option>
                            <option value="smallIncrements">Auto Increment (SMALLINT)</option>
                            <option value="smallInteger">SMALLINT</option>
                            <option value="string">STRING (Varchar)</option>
                            <option value="text">TEXT</option>
                            <option value="time">TIME</option>
                            <option value="timeTz">TIME (with timezone)</option>
                            <option value="timestamp">TIMESTAMP</option>
                            <option value="timestampTz">TIMESTAMP (with timezone)</option>
                            <option value="tinyIncrements">Auto Increment (TINYINT)</option>
                            <option value="tinyInteger">TINYINT</option>
                            <option value="unsignedBigInteger">Unsigned BIGINT</option>
                            <option value="unsignedDecimal">Unsigned DECIMAL</option>
                            <option value="unsignedInteger">Unsigned INTEGER</option>
                            <option value="unsignedMediumInteger">Unsigned MEDIUMINT</option>
                            <option value="unsignedSmallInteger">Unsigned SMALLINT</option>
                            <option value="unsignedTinyInteger">Unsigned TINYINT</option>
                            <option value="uuid">UUID</option>
                            <option value="year">YEAR</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" NAME="col_length[]" placeholder="Length" class="form-control">
                    </td>
                    <td>
                        <input type="text" NAME="col_default[]" placeholder="Default" class="form-control">
                    </td>
                    <td>
                        <input class="form-check-input" type="checkbox" name="col_nullable[]">
                    </td>

                    <td>
                        <input class="form-check-input" type="checkbox" name="col_isFile[]">
                    </td>
                    <td>
                        <button class="btn btn-danger removeRow">Remove</button>
                    </td>
                    <td class="drag-handle">☰</td>
                </tr>
                </tbody>
            </table>
            <div class="mb-3 mt-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="allCheck">
                    <label class="form-check-label" for="allCheck">All of them</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="modelCheck" value="model">
                    <label class="form-check-label" for="modelCheck">Model</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="migration" value="migration">
                    <label class="form-check-label" for="migration">Migration</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="factory" value="factory">
                    <label class="form-check-label" for="factory">Seeder/Factory</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="controller" value="controller">
                    <label class="form-check-label" for="controller">Controller</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="resource" value="resource">
                    <label class="form-check-label" for="resource">Resource</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="has_swagger" value="has_swagger">
                    <label class="form-check-label" for="has_swagger">Has Swagger</label>
                </div>
            </div>

            <div class="row">
                <div class="col-3">
                    <input type="number" name="numRows" placeholder="Add the column" class="form-control" value="1">
                </div>
                <div class="col-9">
                    <a class="btn btn-secondary" id="addColumn">+ Add Column</a>
                    <a class="btn btn-danger" id="clearAll">x Clear All Columns</a>
                </div>
            </div>


            <div class="row mt-3 mb-3">
                <div class="col-4 d-none" id="controllerPath">
                    <label for="model" class="form-label">Controller Path</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">App/Http/Controllers/</div>
                        </div>
                        <input type="text" class="form-control" id="path" name="path" placeholder="Enter Path">
                    </div>
                </div>
                <div class="col-8 d-none" id="apiPath">
                    <label for="model" class="form-label">API Path</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">api/</div>
                        </div>
                        <input type="text" class="form-control" id="api_path" name="api_path"
                               placeholder="Enter API Path">
                        <select name="fileName" id="fileName" class="form-control">
                            @foreach($files as $file)
                                <option value="{{$file}}">{{$file}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="custom-checkbox">
                <input class="form-check-input" type="checkbox" id="is_blade" value="is_blade">
                <label class="form-check-label" for="is_blade">Blade</label>
            </div>
            <button type="submit" class="btn btn-primary mt-5">Generate Columns</button>
        </form>


        <hr/>

        <div class="mt-5 d-none" id="foreignForm">
            <div class="row mb-3">
                <div class="col-6">

                    <table class="table table-bordered" id="foreign_table">
                        <thead>
                        <tr>
                            <th scope="col">Foreign Key</th>
                            <th scope="col">Primary Column</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    <button class="btn btn-secondary" type="button" id="addForeignKeyBtn">Add Foreign Key Row</button>
                </div>
            </div>

            <button type="button" class="btn btn-success mt-5 submit-data">Submit Data</button>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
        crossorigin="anonymous"></script>

<script>

    let requestUrl = "{{route('creator.generate')}}";

    let modelNames = JSON.parse(@json($models));

    let model_primary = ''
    function prepareModelSelector(){
        model_primary = `<select class="form-control" name="model_primary[]">`;

        for(let i = 0; i < modelNames.length; i++){
            model_primary += `\n<option value="`+ i + `">`+ modelNames[i] + `</option>\n`;
        }

        model_primary += `</select>`;

    }

    $(document).ready(function () {

        prepareModelSelector();

        $('#model').on('input', function () {
            var currentValue = $(this).val();

            // Check if the last character isn't a space. If it's not a space, apply PascalCase transformation.
            if (currentValue.charAt(currentValue.length - 1) !== ' ') {
                var pascalCaseValue = toPascalCase(currentValue);
                $(this).val(pascalCaseValue);
            }
        });

        function toPascalCase(str) {
            return str.split(' ').map(function (word) {
                return word.charAt(0).toUpperCase() + word.slice(1); // remove the .toLowerCase() part here
            }).join('');
        }

        // Adding new rows to the table
        $('#addColumn').click(function (e) {
            e.preventDefault();
            const numRows = parseInt($('input[name="numRows"]').val()) || 0; // Get the number of rows to add
            let currentRowCount = $('table#column_table tbody tr').length;

            for (let i = 1; i <= numRows; i++) {
                const newRow = `<tr>
                <th scope="row">${++currentRowCount}</th>
                <td>
                    <input type="text" name="col_name[]" class="form-control" placeholder="Column Name">
                </td>
                <td>
                    <select name="col_type[]" class="form-control">
                            <option value="bigIncrements">Auto Increment (BIGINT)</option>
                            <option value="bigInteger">BIGINT</option>
                            <option value="binary">BINARY</option>
                            <option value="boolean">BOOLEAN</option>
                            <option value="char">CHAR</option>
                            <option value="date">DATE</option>
                            <option value="dateTime">DATETIME</option>
                            <option value="dateTimeTz">DATETIME (with timezone)</option>
                            <option value="decimal">DECIMAL</option>
                            <option value="double">DOUBLE</option>
                            <option value="enum">ENUM</option>
                            <option value="float">FLOAT</option>
                            <option value="increments">Auto Increment (INT)</option>
                            <option value="integer">INTEGER</option>
                            <option value="ipAddress">IP Address</option>
                            <option value="json">JSON</option>
                            <option value="jsonb">JSONB (PostgreSQL)</option>
                            <option value="longText">LONGTEXT</option>
                            <option value="macAddress">MAC Address</option>
                            <option value="mediumIncrements">Auto Increment (MEDIUMINT)</option>
                            <option value="mediumInteger">MEDIUMINT</option>
                            <option value="mediumText">MEDIUMTEXT</option>
                            <option value="morphs">Morphs</option>
                            <option value="nullableMorphs">Nullable Morphs</option>
                            <option value="nullableTimestamps">Nullable Timestamps</option>
                            <option value="rememberToken">Remember Token</option>
                            <option value="smallIncrements">Auto Increment (SMALLINT)</option>
                            <option value="smallInteger">SMALLINT</option>
                            <option value="string">STRING (Varchar)</option>
                            <option value="text">TEXT</option>
                            <option value="time">TIME</option>
                            <option value="timeTz">TIME (with timezone)</option>
                            <option value="timestamp">TIMESTAMP</option>
                            <option value="timestampTz">TIMESTAMP (with timezone)</option>
                            <option value="tinyIncrements">Auto Increment (TINYINT)</option>
                            <option value="tinyInteger">TINYINT</option>
                            <option value="unsignedBigInteger">Unsigned BIGINT</option>
                            <option value="unsignedDecimal">Unsigned DECIMAL</option>
                            <option value="unsignedInteger">Unsigned INTEGER</option>
                            <option value="unsignedMediumInteger">Unsigned MEDIUMINT</option>
                            <option value="unsignedSmallInteger">Unsigned SMALLINT</option>
                            <option value="unsignedTinyInteger">Unsigned TINYINT</option>
                            <option value="uuid">UUID</option>
                            <option value="year">YEAR</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="col_length[]" placeholder="Length" class="form-control">
                </td>
                <td>
                    <input type="text" name="col_default[]" placeholder="Default" class="form-control">
                </td>
                <td>
                    <input class="form-check-input" type="checkbox" name="col_nullable[]">
                </td>
                <td>
                    <input class="form-check-input" type="checkbox" name="col_isFile[]">
                </td>
                <td>
                    <button class="btn btn-danger removeRow">Remove</button>
                </td>
                <td class="drag-handle">☰</td>
            </tr>`;
                $('table#column_table tbody').append(newRow);
                clearForeign();
            }
        });

        // Removing a row from the table and adjust counter
        $(document).on('click', '.removeRow', function (e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            clearForeign();
            updateRowNumbers();
        });

        $(document).on('click', '.removeRowForeign', function (e) {
            e.preventDefault();
            $(this).closest('tr').remove();
        });

        function clearForeign(){
            $('table#foreign_table tbody').empty();
            $('#foreignForm').addClass('d-none');
        }

        $('#clearAll').click(function (e) {
            e.preventDefault();
            $('table#column_table tbody').empty();
            clearForeign();
        });

        // Function to update row numbers
        function updateRowNumbers() {
            $('table#column_table tbody tr').each(function (index) {
                $(this).find('th').text(index + 1);
            });
        }

        $('#modelCheck').change(function () {
            if (!$(this).prop('checked')) {
                $('#migration').prop('checked', false);
            }
        });

        $('#controller').change(function () {
            if (!$(this).prop('checked')) {
                $('#controllerPath').addClass('d-none');
                $('#apiPath').addClass('d-none');
            } else {
                $('#controllerPath').removeClass('d-none');
                $('#apiPath').removeClass('d-none');
                $('#resource').prop('checked', true);

            }
        });

        $('#allCheck').change(function () {
            if (!$(this).prop('checked')) {
                $('#modelCheck').prop('checked', false);
                $('#migration').prop('checked', false);
                $('#factory').prop('checked', false);
                $('#seeder').prop('checked', false);
                $('#resource').prop('checked', false);
                $('#controller').prop('checked', false);
                $('#has_swagger').prop('checked', false);
                $('#controllerPath').addClass('d-none');
                $('#apiPath').addClass('d-none');
            } else {
                $('#modelCheck').prop('checked', true);
                $('#migration').prop('checked', true);
                $('#factory').prop('checked', true);
                $('#seeder').prop('checked', true);
                $('#resource').prop('checked', true);
                $('#controller').prop('checked', true);
                $('#has_swagger').prop('checked', true);
                $('#controllerPath').removeClass('d-none');
                $('#apiPath').removeClass('d-none');
            }
        });

        $('#migration').change(function () {
            if ($(this).prop('checked')) {
                $('#modelCheck').prop('checked', true);
            }
        });

        let requestData = {};
        let tableColumns = "";


        // Serializing the data and sending via AJAX
        $('form').submit(function (e) {
            e.preventDefault();

            requestData = {};
            const data = {
                columns: [],
                foreignKeys: [],
                model_name: $('#model').val(),
                path_name: '/Controllers/' + $('#path').val(),
                path_api: 'api/' + $('#api_path').val(),
                fileName: $('#fileName').val(),
                model: $('#modelCheck').prop('checked'),
                migration: $('#migration').prop('checked'),
                factory: $('#factory').prop('checked'),
                controller: $('#controller').prop('checked'),
                resource: $('#resource').prop('checked'),
                has_swagger: $('#has_swagger').prop('checked'),
                is_blade: $('#is_blade').prop('checked'),
                _token: $('input[name=_token]').val(),
            };

            $('table#column_table tbody tr').each(function () {
                const row = $(this);
                data.columns.push({
                    name: row.find('input[name="col_name[]"]').val(),
                    type: row.find('select[name="col_type[]"]').val(),
                    length: row.find('input[name="col_length[]"]').val(),
                    default: row.find('input[name="col_default[]"]').val(),
                    nullable: row.find('input[name="col_nullable[]"]').prop('checked'),
                    isFile: row.find('input[name="col_isFile[]"]').prop('checked'),
                });
            });

            console.log(data)

            requestData = data;

            table_columns = `<select class="form-control" name="table_columns[]">`;

            for(let i = 0; i < data.columns.length; i++){
                table_columns += `\n<option value="`+ i + `">`+ data.columns[i].name + `</option>\n`;
            }

            table_columns += `</select>`;

            $('#table_columns').append(table_columns);

            $('#foreignForm').removeClass('d-none')

            console.log('finish')

        });

        function addRowInForeign(){
            let row = `<tr>
                            <td>`+table_columns+`</td>
                            <td>`+ model_primary +`</td>
                            <td>  <button class="btn btn-danger removeRowForeign">Remove</button> </td>
                        </tr>`;

            $('table#foreign_table tbody').append(row);

        }

        $(document).on('click', '#addForeignKeyBtn', function (e){
            e.preventDefault();
            addRowInForeign();
        })


        // Removing a row from the table and adjust counter
        $(document).on('click', '.submit-data', function (e) {
            e.preventDefault();

            $('table#foreign_table tbody tr').each(function () {
                const row = $(this);
                requestData.foreignKeys.push({
                    foreign_key: requestData.columns[row.find('select[name="table_columns[]"]').val()].name,
                    primary_key_model: modelNames[row.find('select[name="model_primary[]"]').val()],
                });
            });


            console.log(requestData)
            $.ajax({
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                url: requestUrl,
                type: 'POST',
                data: requestData,
                success: function (response) {
                    // Handle success response
                    console.log(response);

                    alert(response);
                },
                error: function (error) {
                    // Handle error response
                    console.log(error);
                }
            });
        });
    });

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
    // Make table rows sortable
    var sortable = new Sortable(document.querySelector("tbody"), {
        animation: 150,
        handle: ".drag-handle",
        onUpdate: function (evt) {
            updateRowNumbers();
        }
    });

    // Function to update row numbers
    function updateRowNumbers() {
        $('table#column_table tbody tr').each(function (index) {
            $(this).find('th').text(index + 1);
        });
    }

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
</body>
</html>
