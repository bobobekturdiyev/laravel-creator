<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <title>Laravel Creator</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        Dropdown
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                </li>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>

<div class="wrapper">
    <div class="container">
        <form class="mt-5">
            @csrf
            <div class="mb-3">
                <label for="model" class="form-label">Model Name</label>
                <input type="text" class="form-control" id="model" name="model" placeholder="Enter Model Name">
            </div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Length/Values</th>
                    <th scope="col">Default</th>
                    <th scope="col">Nullable</th>
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
                        <button class="btn btn-danger removeRow">Remove</button>
                    </td>
                    <td class="drag-handle">☰</td>
                </tr>
                </tbody>
            </table>
            <div class="mb-3 mt-4">
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
            <button type="submit" class="btn btn-primary mt-5">Generate Columns</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
        crossorigin="anonymous"></script>

<script>

    let requestUrl = "{{route('creator.generate')}}";
    $(document).ready(function () {

        $('#model').on('input', function() {
            var currentValue = $(this).val();

            // Check if the last character isn't a space. If it's not a space, apply PascalCase transformation.
            if (currentValue.charAt(currentValue.length - 1) !== ' ') {
                var pascalCaseValue = toPascalCase(currentValue);
                $(this).val(pascalCaseValue);
            }
        });

        function toPascalCase(str) {
            return str.split(' ').map(function(word) {
                return word.charAt(0).toUpperCase() + word.slice(1); // remove the .toLowerCase() part here
            }).join('');
        }
        // Adding new rows to the table
        $('#addColumn').click(function (e) {
            e.preventDefault();
            const numRows = parseInt($('input[name="numRows"]').val()) || 0; // Get the number of rows to add
            let currentRowCount = $('table tbody tr').length;

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
                    <button class="btn btn-danger removeRow">Remove</button>
                </td>
                <td class="drag-handle">☰</td>
            </tr>`;
                $('table tbody').append(newRow);
            }
        });

        // Removing a row from the table and adjust counter
        $(document).on('click', '.removeRow', function (e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            updateRowNumbers();
        });

        $('#clearAll').click(function (e) {
            e.preventDefault();
            $('table tbody').empty();
        });

        // Function to update row numbers
        function updateRowNumbers() {
            $('table tbody tr').each(function (index) {
                $(this).find('th').text(index + 1);
            });
        }
        $('#modelCheck').change(function() {
            if (!$(this).prop('checked')) {
                $('#migration').prop('checked', false);
            }
        });

        $('#migration').change(function() {
            if ($(this).prop('checked')) {
                $('#modelCheck').prop('checked', true);
            }
        });

        // Serializing the data and sending via AJAX
        $('form').submit(function (e) {
            e.preventDefault();

            const data = {
                columns: [],
                model_name: $('#model').val(),
                model: $('#modelCheck').prop('checked'),
                migration: $('#migration').prop('checked'),
                factory: $('#factory').prop('checked'),
                controller: $('#controller').prop('checked'),
                resource: $('#resource').prop('checked'),
                has_swagger: $('#has_swagger').prop('checked'),
                _token: $('input[name=_token]').val(),
            };

            $('table tbody tr').each(function () {
                const row = $(this);
                data.columns.push({
                    name: row.find('input[name="col_name[]"]').val(),
                    type: row.find('select[name="col_type[]"]').val(),
                    length: row.find('input[name="col_length[]"]').val(),
                    default: row.find('input[name="col_default[]"]').val(),
                    nullable: row.find('input[name="col_nullable[]"]').prop('checked'),
                });
            });

            console.log(JSON.stringify(data))
            $.ajax({
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                url: requestUrl,
                type: 'POST',
                data: data,
                success: function (response) {
                    // Handle success response
                    console.log(response);
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
        onUpdate: function(evt) {
            updateRowNumbers();
        }
    });

    // Function to update row numbers
    function updateRowNumbers() {
        $('table tbody tr').each(function (index) {
            $(this).find('th').text(index + 1);
        });
    }

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
</body>
</html>
