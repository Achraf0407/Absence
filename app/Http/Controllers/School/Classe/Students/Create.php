<?php

namespace App\Http\Controllers\School\Classe\Students;

use Illuminate\Routing\Controller;
use App\Forms\Builders\School\Classe\StudentsForm;

class Create extends Controller
{
    public function __invoke(StudentsForm $form)
    {
        return ['form' => $form->create()];
    }
}
