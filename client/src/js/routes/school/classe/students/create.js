const StudentsCreate = () => import('../../../../pages/school/classe/students/Create.vue');

export default {
    name: 'school.classe.students.create',
    path: 'create',
    component: StudentsCreate,
    meta: {
        breadcrumb: 'create',
        title: 'Create Students',
    },
};
