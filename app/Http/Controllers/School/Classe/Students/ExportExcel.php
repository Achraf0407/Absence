<?php

namespace App\Http\Controllers\School\Classe\Students;

use App\Tables\Builders\School\Classe\StudentsTable;
use Illuminate\Routing\Controller;
use LaravelEnso\Tables\app\Traits\Excel;

class ExportExcel extends Controller
{
    use Excel;

    protected $tableClass = StudentsTable::class;
}
