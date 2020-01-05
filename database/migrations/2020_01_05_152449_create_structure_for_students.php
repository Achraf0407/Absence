<?php

use LaravelEnso\Migrator\app\Database\Migration;

class CreateStructureForStudents extends Migration
{
    protected $permissions = [
        ['name' => 'school.classe.students.index', 'description' => 'Show index for students', 'type' => 0, 'is_default' => false],

        ['name' => 'school.classe.students.create', 'description' => 'Create students', 'type' => 1, 'is_default' => false],
        ['name' => 'school.classe.students.store', 'description' => 'Store a new students', 'type' => 1, 'is_default' => false],
        ['name' => 'school.classe.students.show', 'description' => 'Show students', 'type' => 1, 'is_default' => false],
        ['name' => 'school.classe.students.edit', 'description' => 'Edit students', 'type' => 1, 'is_default' => false],
        ['name' => 'school.classe.students.update', 'description' => 'Update students', 'type' => 1, 'is_default' => false],
        ['name' => 'school.classe.students.destroy', 'description' => 'Delete students', 'type' => 1, 'is_default' => false],
        ['name' => 'school.classe.students.initTable', 'description' => 'Init table for students', 'type' => 0, 'is_default' => false],

        ['name' => 'school.classe.students.tableData', 'description' => 'Get table data for students', 'type' => 0, 'is_default' => false],

        ['name' => 'school.classe.students.exportExcel', 'description' => 'Export excel for students', 'type' => 0, 'is_default' => false],

        ['name' => 'school.classe.students.options', 'description' => 'Get students options for select', 'type' => 0, 'is_default' => false],
    ];

    protected $menu = [
        'name' => 'Students', 'icon' => 'user-tie', 'route' => 'school.classe.students.index', 'order_index' => 100, 'has_children' => false
    ];

    protected $parentMenu = 'Administration';
}

