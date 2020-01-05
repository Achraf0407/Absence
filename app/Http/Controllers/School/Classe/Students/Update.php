<?php

namespace App\Http\Controllers\School\Classe\Students;

use App\Students;
use Illuminate\Routing\Controller;
use App\Http\Requests\School\Classe\ValidateStudentsRequest;

class Update extends Controller
{
    public function __invoke(ValidateStudentsRequest $request, Students $students)
    {
        $students->update($request->validated());

        return ['message' => __('The students was successfully updated')];
    }
}
