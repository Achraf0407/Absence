import routeImporter from '@core/modules/importers/routeImporter';

const routes = routeImporter(require.context('./classe', false, /.*\.js$/));
const RouterView = () => import('@core/bulma/pages/Router.vue');

export default {
    path: 'classe',
    component: RouterView,
    meta: {
        breadcrumb: 'classe',
    },
    children: routes,
};
