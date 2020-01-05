import routeImporter from '@core/modules/importers/routeImporter';

const routes = routeImporter(require.context('./school', false, /.*\.js$/));
const RouterView = () => import('@core/bulma/pages/Router.vue');

export default {
    path: '/school',
    component: RouterView,
    meta: {
        breadcrumb: 'school',
    },
    children: routes,
};
