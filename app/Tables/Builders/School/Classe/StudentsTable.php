<?php

namespace App\Tables\Builders\School\Classe;

use App\Students;
use Illuminate\Database\Eloquent\Builder;
use LaravelEnso\Tables\app\Contracts\Table;

class StudentsTable implements Table
{
    protected const TemplatePath = __DIR__.'/../../../Templates/School/Classe/students.json';

    public function query(): Builder
    {
        return Students::selectRaw('
            students.id as "dtRowId",
            students.firstname,
            students.lastname,
            students.subject,
            students.prof,
        ');
    }

    public function templatePath(): string
    {
        return static::TemplatePath;
    }
}
