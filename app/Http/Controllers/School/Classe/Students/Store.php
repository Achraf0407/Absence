<?php

namespace App\Http\Controllers\School\Classe\Students;

use App\Students;
use Illuminate\Routing\Controller;
use App\Http\Requests\School\Classe\ValidateStudentsRequest;

class Store extends Controller
{
    public function __invoke(ValidateStudentsRequest $request, Students $students)
    {
        $students->fill($request->validated())->save();

        return [
            'message' => __('The students was successfully created'),
            'redirect' => 'school.classe.students.edit',
            'param' => ['students' => $students->id],
        ];
    }
}
