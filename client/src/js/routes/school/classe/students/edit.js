const StudentsEdit = () => import('../../../../pages/school/classe/students/Edit.vue');

export default {
    name: 'school.classe.students.edit',
    path: ':students/edit',
    component: StudentsEdit,
    meta: {
        breadcrumb: 'edit',
        title: 'Edit Students',
    },
};
