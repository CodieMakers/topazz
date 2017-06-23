<template>
    <section class="card">
        <header class="card-header flex flex-wrap items-center justify-between">
            <h1 class="m0">
                <i class="fa fa-briefcase"></i>Projects
            </h1>
            <router-link to="/projects/0" class="btn btn-outline moonstone-blue">
                <i class="fa fa-plus"></i>Add
            </router-link>
        </header>
        <article class="table">
            <header class="row table-header">
                <div class="col">Name</div>
                <div class="col">Domain</div>
                <div class="col">Status</div>
                <div class="col">Theme</div>
                <div class="col">Pages</div>
                <div class="col">Actions</div>
            </header>
            <div class="row center border-bottom border-platinum" v-for="(project, index) in projects" :key="'project-'+index">
                <div class="col">{{project.name}}</div>
                <div class="col">{{project.host}}</div>
                <div class="col">{{$parent.getStatusName(project.status)}}</div>
                <div class="col">{{project.theme_name}}</div>
                <div class="col">
                    <div class="flex items-center justify-between mb1" v-for="page in project.pages">
                        <router-link :to="'/pages/'+page.id" class="moonstone-blue">
                            {{page.name}}
                        </router-link>
                        <div class="gray">
                            <span>{{page.path}}</span>
                            <i class="fa fa-long-arrow-right mx1"></i>
                            <span>{{page.layout_name}}</span>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <router-link :to="{name: 'project', params:{id: project.id}}"
                                 class="btn action" title="Detail">
                        <i class="fa fa-external-link m0"></i>
                    </router-link>
                    <a href="#1" class="btn action danger white" v-if="canAccess('project.remove')"
                       @click.stop.prevent="remove(project, index)">
                        <i class="fa fa-trash m0"></i>
                    </a>
                </div>
            </div>
        </article>
    </section>
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
                projects: this.$parent.projects
            };
        },

    }
</script>