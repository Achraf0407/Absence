<?php

use LaravelEnso\DataImport\app\Models\DataImport;

$factory->define(DataImport::class, function () {
    return [
        'type' => collect(config('enso.imports.configs'))->keys()->random(),
        'successful' => 0,
        'failed' => 0,
        'chunks' => 0,
        'processed_chunks' => 0,
        'file_parsed' => false,
    ];
});
