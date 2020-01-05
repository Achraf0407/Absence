<?php

namespace App\Http\Controllers\School\Classe\Students;

use App\Students;
use Illuminate\Routing\Controller;

class Show extends Controller
{
    public function __invoke(Students $students)
    {
        return ['students' => $students];
    }
}
