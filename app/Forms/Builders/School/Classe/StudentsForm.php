<?php

namespace App\Forms\Builders\School\Classe;

use App\Students;
use LaravelEnso\Forms\app\Services\Form;

class StudentsForm
{
    protected const TemplatePath = __DIR__.'/../../../Templates/School/Classe/students.json';

    protected $form;

    public function __construct()
    {
        $this->form = new Form(static::TemplatePath);
    }

    public function create()
    {
        return $this->form->create();
    }

    public function edit(Students $students)
    {
        return $this->form->edit($students);
    }
}
