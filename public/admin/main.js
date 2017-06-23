/**
 * @author Lukáš Krotovič
 * @version 1.0.0
 * @package Topazz
 */

import Vue from "vue";
import VueResource from 'vue-resource';
import VueNotification from 'vue-notification';
import Router from "./Router.vue";
import Breadcrumbs from './breadcrumbs';
import VueLoader from './loader';
import VueSecurity from './security';
import Defaults from './defaults';

Vue.use(VueResource, {
    root: '/admin'
});
Vue.use(VueNotification);
Vue.use(Breadcrumbs);
Vue.use(VueLoader);
Vue.use(VueSecurity);

Vue.use(Defaults);

new Vue({
    el: '#admin',
    router: Router,
    data: {
        panelShown: true,
        loadingUser: false
    },
    computed: {
        panelToggleButtonTitle() {
            return this.panelShown ? 'Hide panel' : 'Show panel';
        }
    },
    created() {
        this.loadCurrentUser();
        this.loadNonce();
        this.loadCsrfToken();
    },
    methods: {
        loadCurrentUser() {
            this.loadingUser = true;
            this.$http.get('current-user').then(response => {
                this.$currentUser = response.body;
                this.loadingUser = false;
            });
        },
        togglePanel(event) {
            this.panelShown = !this.panelShown;
            event.preventDefault();
        }
    }
});
