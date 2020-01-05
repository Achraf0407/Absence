<?php
Route::middleware(['web', 'auth'])
    ->group(function () {
        Route::namespace('School\Classe\Students')
            ->prefix('school/classe/students')->as('school.classe.students.')
            ->group(function () {
                
                Route::get('', 'Index')->name('index');
                Route::get('create', 'Create')->name('create');
                Route::post('', 'Store')->name('store');
                Route::get('{students}/edit', 'Edit')->name('edit');

                Route::patch('{students}', 'Update')->name('update');

                Route::delete('{students}', 'Destroy')->name('destroy');

                Route::get('initTable', 'InitTable')->name('initTable');
                Route::get('tableData', 'TableData')->name('tableData');
                Route::get('exportExcel', 'ExportExcel')->name('exportExcel');

                Route::get('options', 'Options')->name('options');
                Route::get('{students}', 'Show')->name('show');


        });
    });

// example/dummy data for the dashboard
Route::middleware(['web', 'auth'])
    ->namespace('Dashboard')
    ->prefix('dashboard')->as('dashboard.')
    ->group(function () {
        Route::get('line', 'ChartController@line')
            ->name('line');
        Route::get('bar', 'ChartController@bar')
            ->name('bar');
        Route::get('pie', 'ChartController@pie')
            ->name('pie');
        Route::get('doughnut', 'ChartController@doughnut')
            ->name('doughnut');
        Route::get('radar', 'ChartController@radar')
            ->name('radar');
        Route::get('polar', 'ChartController@polar')
            ->name('polar');
        Route::get('bubble', 'ChartController@bubble')
            ->name('bubble');
    });

Route::middleware(['auth', 'core'])
    ->group(function () {
        // add your local routes
    });
