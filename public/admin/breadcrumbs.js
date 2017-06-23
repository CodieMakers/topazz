/**
 * @author Lukáš Krotovič
 * @version 1.0.0
 * @package Topazz
 */

import Breadcrumbs from './Breadcrumbs.vue';

export default {
    install(Vue) {
        Object.defineProperties(Vue.prototype, {
            $breadcrumbs: {
                get() {
                    return this.$route.matched.filter((route) => {
                        return route.hasOwnProperty('meta') && route.meta.hasOwnProperty('breadcrumb');
                    });
                }
            }
        });

        Vue.component('breadcrumbs', Breadcrumbs);
    }
}