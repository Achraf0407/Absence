<?php

namespace App\Http\Controllers\School\Classe\Students;

use App\Tables\Builders\School\Classe\StudentsTable;
use Illuminate\Routing\Controller;
use LaravelEnso\Tables\app\Traits\Data;

class TableData extends Controller
{
    use Data;

    protected $tableClass = StudentsTable::class;
}
