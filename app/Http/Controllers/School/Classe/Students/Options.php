<?php

namespace App\Http\Controllers\School\Classe\Students;

use App\Students;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LaravelEnso\Select\app\Traits\OptionsBuilder;

class Options extends Controller
{
    use OptionsBuilder;

    protected $model = Students::class;

    //protected $queryAttributes = ['name'];

    //public function query(Request $request)
    //{
    //    return Students::query();
    //}
}
