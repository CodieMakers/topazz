<template>
    <div id="dashboard" class="flex flex-column align-start justify-between">
        <!--<section class="card-wrapper mb2">-->

        <!--</section>-->
        <section class="card mb2">
            <h1>Reports</h1>
            <article class="card-body">
            </article>
        </section>
        <section class="card mb2" v-if="hasUpdates">
            <h1><i class="fa fa-cloud-download"></i>Available Updates</h1>
            <article class="card-body">

            </article>
        </section>
    </div>
</template>

<script>
    /**
     * @author Lukáš Krotovič
     * @version 1.0.0
     * @package Topazz
     */

    export default {
        created() {
            this.fetchData();
        },
        watcher: {
            "$route": "fetchData"
        },
        data() {
            return {
                hasUpdates: false,
                availableUpdates: {}
            };
        },
        methods: {
            fetchData() {
                this.$loading = true;
                this.$http.get('dashboard').then(response => {
                    this.$loading = false;
                    const data = response.body;
                    if (data.hasOwnProperty('availableUpdates')) {
                        this.hasUpdates = true;
                        this.availableUpdates = data.availableUpdates;
                    }
                });
            }
        }
    }
</script>