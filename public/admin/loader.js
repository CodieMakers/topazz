/**
 * @author Lukáš Krotovič
 * @version 1.0.0
 * @package Topazz
 */

export default {
    install(Vue) {

        const loader = {
            loading: false
        };

        Object.defineProperty(Vue.prototype, '$loading', {
            get() {
                return loader.loading;
            },
            set(loading) {
                loader.loading = loading;
            }
        });

        Vue.component('loader', {
            template: `<div class="loading" v-if="loading"></div>`,
            data() {
                return loader;
            }
        });
    }
}