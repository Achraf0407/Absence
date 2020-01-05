<?php

namespace App\Http\Controllers\School\Classe\Students;

use App\Tables\Builders\School\Classe\StudentsTable;
use Illuminate\Routing\Controller;
use LaravelEnso\Tables\app\Traits\Init;
use LaravelEnso\Permissions\app\Tables\Builders\PermissionTable;

class InitTable extends Controller
{
    use Init;
    protected $tableClass = StudentsTable::class;
}
