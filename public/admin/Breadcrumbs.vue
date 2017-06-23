<template>
    <section class="breadcrumbs" v-if="$breadcrumbs.length">
        <template v-for="(route, index) in $breadcrumbs">
            <router-link :to="link(route)" class="item" :key="'bread-' + index">{{route.meta.breadcrumb}}</router-link>
            <i class="fa fa-angle-right mx1" v-if="index !== ($breadcrumbs.length - 1)"></i>
        </template>
    </section>
</template>

<script>
    /**
     * @author Lukáš
     * @version 1.0.0
     * @package Topazz
     */
    export default {
        filters: {
            breadcrumbText(route) {
                return route.meta.breadcrumb;
            }
        }, methods: {
            link(route) {
                if (route.hasOwnProperty('name') && route.name !== undefined) {
                    return {
                        name: route.name,
                        params: this.$route.params
                    };
                }
                return {
                    path: (route.hasOwnProperty('fullPath')) ? route.fullPath : route.path,
                    params: this.$route.params
                }
            }
        }
    }
</script>