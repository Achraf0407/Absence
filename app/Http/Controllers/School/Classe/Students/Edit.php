<?php

namespace App\Http\Controllers\School\Classe\Students;

use App\Students;
use Illuminate\Routing\Controller;
use App\Forms\Builders\School\Classe\StudentsForm;

class Edit extends Controller
{
    public function __invoke(Students $students, StudentsForm $form)
    {
        return ['form' => $form->edit($students)];
    }
}
