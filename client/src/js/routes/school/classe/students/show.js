const StudentsShow = () => import('../../../../pages/school/classe/students/Show.vue');

export default {
    name: 'school.classe.students.show',
    path: ':students',
    component: StudentsShow,
    meta: {
        breadcrumb: 'show',
        title: 'Show Students',
    },
};
