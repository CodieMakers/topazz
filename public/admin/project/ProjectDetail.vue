<template>
    <div id="user-detail" class="has-bottom-panel">
        <section class="card">
            <h1>
                <i class="fa" :class="{'fa-plus': isNewRecord, 'fa-briefcase': !isNewRecord}"></i>
                {{project.name}}
            </h1>
            <article class="form">
                <div class="row clearfix flex items-center">
                    <label for="name" class="label flex-auto required">Name:</label>
                    <input type="text" id="name" v-model="project.name" class="input col-8" required/>
                </div>
                <div class="row clearfix flex items-center">
                    <label for="host" class="label flex-auto required">Domain:</label>
                    <input type="text" id="host" v-model="project.host" class="input col-8" required/>
                </div>
                <div class="row clearfix flex items-center">
                    <label for="status" class="label flex-auto">Status:</label>
                    <select id="status" v-model="project.status" class="select col-8">
                        <option value="0">Published</option>
                        <option value="1">Private</option>
                        <option value="2">For review</option>
                    </select>
                </div>
                <div class="row clearfix flex items-center">
                    <label for="theme" class="label flex-auto">Theme:</label>
                    <select id="theme" v-model="project.theme_name" class="select col-8">
                        <option value="">None</option>
                        <option v-for="theme in $parent.themes" :value="theme.name">
                            {{theme.name}}
                        </option>
                    </select>
                </div>
            </article>
            <article class="footnote">
                <i class="fa fa-sm fa-star golden-gate mr2"></i>Those inputs are required.
            </article>
        </section>
        <section class="bottom-panel">
            <div class="right">
                <button type="button" class="btn btn-primary white bg-golden-gate"
                        @click.stop.prevent="remove" v-if="!isNewRecord && canAccess('project.remove')">
                    <i class="fa fa-trash"></i>Remove
                </button>
                <button type="button" class="btn btn-primary white bg-moonstone-blue" @click.stop.prevent="save">
                    <i class="fa" :class="{'fa-save': !saving, 'fa-spin fa-circle-o-notch': saving}"></i>Save
                </button>
            </div>
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
        props: ['id'],
        data() {
            let _project = {
                name: '',
                host: '',
                status: 2,
                theme_name: ''
            };
            if (this.id != 0) {
                _project = this.$parent.projects.find(item => item.id == this.id);
            }
            return {
                project: _project,
                saving: false
            }
        },
        computed: {
            isNewRecord() {
                return this.id == 0;
            }
        },
        methods: {
            remove() {
                this.$loading = true;
                this.$http.delete('projects/' + this.project.id).then(response => {
                    this.$loading = false;
                    this.$parent.projects.splice(this.$parent.projects.findIndex(item => item.id === this.project.id), 1);
                    this.$notify({
                        type: 'success',
                        text: 'You have successfully removed this project.'
                    });
                    this.$router.replace({name: 'projects'});
                }, error => {
                    console.log(error);
                    this.$loading = false;
                    this.$notify({
                        type: 'error',
                        text: 'There was an error during removing this project.'
                    });
                });
            },
            save() {
                if (this.isNewRecord) {
                    this.$loading = true;
                    this.$http.put('projects', this.project).then(response => {
                        this.$loading = false;
                        this.$parent.projects.push(response.body);
                        this.$notify({
                            type: 'success',
                            text: 'You have successfuly created this project.'
                        });
                        this.$router.push({name: 'project', params: {id: response.body.id}});
                    }, error => {
                        console.log(error);
                        this.$loading = false;
                        this.$notify({
                            type: 'error',
                            text: 'There was an error during creating this project.'
                        });
                    });
                } else {
                    this.$http.post('projects/' + this.project.id, this.project).then(response => {
                        this.$loading = false;
                        this.$parent.projects.splice(
                            this.$parent.projects.findIndex(item => item.id === this.project.id),
                            1, response.body
                        );
                        this.project = response.body;
                        this.$notify({
                            type: 'success',
                            text: 'You have successfuly updated this project.'
                        });
                    }, error => {
                        console.log(error);
                        this.$loading = false;
                        this.$notify({
                            type: 'error',
                            text: 'There was an error during updating this project.'
                        });
                    });
                }
            }
        }
    }
</script>