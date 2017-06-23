<script>
    /**
     * @author Lukáš Krotovič
     * @version 1.0.0
     * @package Topazz
     */

    import Vue from 'vue';
    import VueRouter from 'vue-router';
    import Dashboard from './Dashboard.vue';
    import Files from './Files.vue';
    import {UsersRouting} from './users';
    import {ProjectsRouting} from './projects';

    Vue.use(VueRouter);

    export default new VueRouter({
        basePath: '/admin/',
        routes: [
            {
                path: '/', component: Dashboard, alias: '/dashboard',
                meta: {
                    breadcrumb: 'Dashboard'
                }
            }, {
                path: '/files', component: Files,
                meta: {
                    breadcrumb: 'Files'
                }
            },
            UsersRouting,
            ProjectsRouting
        ],
        scrollBehavior(to, from, savedPosition) {
            if (savedPosition) {
                return savedPosition;
            }
            if (to.hash) {
                return {selector: to.hash};
            }
            return {x: 0, y: 0};
        }
    })
</script>