<template>
    <router-view v-if="!$loading"></router-view>
</template>

<script>
    /**
     * @author Lukáš Krotovič
     * @version 1.0.0
     * @package Topazz
     */

    export default {
        data() {
            return {
                hasError: false,
                errorMessage: '',
                users: [],
                roleMapping: {
                    0: "Administrator",
                    1: "Moderator",
                    2: "Editor",
                    3: "Blogger"
                }
            }
        }, created() {
            this.fetchData();
        }, watcher: {
            "$route": "fetchData"
        }, methods: {
            fetchData() {
                this.$loading = true;
                this.$http.get('users').then(response => {
                    this.$loading = false;
                    if (response.status === 200) {
                        this.users = response.body;
                    } else {
                        this.hasError = true;
                        this.errorMessage = "There was an error during fetching data.";
                    }
                }, error => {
                    this.hasError = true;
                    this.errorMessage = error.body;
                });
            }
        }
    }
</script>