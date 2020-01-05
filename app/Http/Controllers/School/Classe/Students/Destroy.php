<?php

namespace App\Http\Controllers\School\Classe\Students;

use App\Students;
use Illuminate\Routing\Controller;

class Destroy extends Controller
{
    public function __invoke(Students $students)
    {
        $students->delete();

        return [
            'message' => __('The students was successfully deleted'),
            'redirect' => 'school.classe.students.index',
        ];
    }
}
