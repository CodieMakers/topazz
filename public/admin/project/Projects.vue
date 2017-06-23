<template>
    <div id="projects" v-if="!$loading">
        <router-view></router-view>
    </div>
</template>

<script>
    /**
     * @author Lukáš Krotovič
     * @version 1.0.0
     * @package Topazz
     */

    const statusMapping = {
        0: "Published",
        1: "Private",
        2: "For review"
    };

    export default {
        data() {
            return {
                projects: [],
                themes: []
            }
        },
        created() {
            this.loadProjects();
        },
        watcher: {
            '$router': 'loadProjects'
        },
        methods: {
            loadProjects () {
                this.$loading = true;
                this.$http.get('projects').then(response => {
                    this.projects = response.body;
                    this.$http.get('themes').then(response => {
                        this.$loading = false;
                        this.themes = response.body;
                    });
                });
            },
            getStatusName(status) {
                return statusMapping[status];
            }
        }
    }
</script>