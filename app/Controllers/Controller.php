<?php

namespace App\Controllers;

class Controller
{
    public function saveData($name, $data)
    {
        if (!file_exists(storage_path('instagram'))) {
            mkdir(storage_path('instagram'));
        }
        $path = storage_path($name);
        $data = json_encode($data);
        return file_put_contents($path, $data);
    }

    public function getData($name)
    {
        $path = storage_path($name);
        if (file_exists($path)) {
            $data = file_get_contents($path);
            return json_decode($data, true);
        } else {
            return [];
        }
    }

    public function log($text, $exit = false) {
        echo $text . "\n";
        if ($exit) {
            exit(0);
        }
    }
}
