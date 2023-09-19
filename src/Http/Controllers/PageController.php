<?php

namespace Programmeruz\LaravelCreator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function getFileNamesInRoutes()
    {
        $routesPath = base_path('routes');
        $files = File::files($routesPath);

        $fileNames = [];
        foreach ($files as $file) {
            $fileNames[] = $file->getFilename();
        }

        return $fileNames;
    }

    public function getIndex(){
        $files = $this->getFileNamesInRoutes();
        $models = json_encode($this->getAllModels());
        return view('LaravelCreator::index', ['files' => $files, 'models' => $models]);
    }

    public function getAllModels()
    {
        $models = [];

        $files = scandir(app_path('/Models/'));

        foreach ($files as $file) {
            if (preg_match('/^[\w-]+\.php$/', $file)) {
              $models[] = str_replace('.php', '',$file);
            }
        }

        return $models;
    }
}
