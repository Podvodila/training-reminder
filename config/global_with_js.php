<?php

$exportArray = [];

// Properties and constants from this array will be omitted in result array.
// Specify the class name and list of constants or variables from this class
$exceptions = [
    // classname => Array of properties names and constants names
    // App\Model::class => ['addressSelect']
];

// add constants/variables from Models classes
foreach (scandir(app_path('Models')) as $modelDirectoryName) {
    // dirs with name . or .. will be omitted
    if (in_array($modelDirectoryName, ['.', '..'])) {
        continue;
    }

    $className = "App\Models\\$modelDirectoryName\\$modelDirectoryName";

    generateClassData($exportArray, $exceptions, $className);
}

return $exportArray;
