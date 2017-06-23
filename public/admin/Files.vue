<template>
    <div id="files" v-if="!$loading">
        <section class="card">
            <header class="card-header flex flex-wrap items-center justify-between">
                <h1><i class="fa fa-folder-open-o"></i>Files</h1>
                <div class="loading static" v-if="loading"></div>
                <button class="btn btn-outline moonstone-blue" @click.stop="openModal">
                    <i class="fa fa-file-o"></i>Add
                </button>
            </header>
            <article class="clearfix relative">
                <div class="flex flex-wrap justify-start">
                    <div class="file" v-for="(file, index) in images" :key="'img-' + index">
                        <i class="fa fa-times" @click.stop="removeFile(file, index)"></i>
                        <img :src="file.url" alt="Preview"/>
                    </div>
                </div>
            </article>
            <article class="table">
                <div class="row table-header">
                    <div class="col">Name</div>
                    <div class="col">URL</div>
                    <div class="col">MIME</div>
                    <div class="col">Actions</div>
                </div>
                <div class="row center" v-for="(file, index) in files" :key="'file-' + index">
                    <div class="col">{{file.name}}</div>
                    <div class="col">
                        <a :href="file.url" target="_blank" class="moonstone-blue">{{file.url}}</a>
                    </div>
                    <div class="col">{{file.type}}</div>
                    <div class="col">
                        <button type="button" class="btn action danger" @click.stop="removeFile(file, index)">
                            <i class="fa fa-trash"></i>Remove
                        </button>
                    </div>
                </div>
            </article>
        </section>
        <modal name="add-file" :shown="isModalOpened" @close="closeModal">
            <h1 slot="header">
                <i class="fa fa-file-o mr1"></i>Add File
            </h1>
            <div slot="body" class="m2">
                <input type="file" class="input" @change="addFiles" multiple>
            </div>
            <footer slot="footer" class="clearfix">
                <div class="right">
                    <button class="btn btn-primary bg-moonstone-blue white" @click="uploadFiles">
                        <i class="fa fa-send-o"></i>Upload
                    </button>
                </div>
            </footer>
        </modal>
    </div>
</template>

<style scoped lang="scss">
    @import '../sass/color-variables';

    .file {
        margin: 1rem;
        position: relative;
        width: 8rem;
        height: auto;
        overflow: hidden;

        img {
            position: relative;
            top: 0;
            left: 0;
            width: 100%;
            height: auto;
            z-index: 1;
        }
        i.fa {
            position: absolute;
            top: 5px;
            right: 5px;
            padding: 3px;
            background-color: lighten($platinum, 10%);
            border-radius: 100%;
            z-index: 2;
            opacity: 0;
            cursor: pointer;
            transition: opacity .5s ease-in-out;
        }
        &:hover i.fa {
            opacity: 1;
        }
    }
</style>

<script>
    /**
     * @author Lukáš Krotovič
     * @version 1.0.0
     * @package Topazz
     */
    import Modal from './Modal.vue';
    export default {
        data() {
            return {
                isModalOpened: false,
                images: [],
                files: [],
                addedFiles: [],
                loading: false
            }
        }, created() {
            this.loadFiles();
        }, watcher: {
            "$route": "loadFiles"
        }, methods: {
            openModal() {
                this.isModalOpened = true;
            },
            closeModal() {
                this.isModalOpened = false;
            },
            uploadFiles() {
                let formData = new FormData();
                for (let i = 0, len = this.addedFiles.length; i < len; i++) {
                    let file = this.addedFiles[i];
                    formData.append(file.name, file);
                }
                this.$http.post('files', formData).then(response => {
                    if (response.ok) {
                        this.images = response.body["images"];
                        this.files = response.body["others"];
                        this.addedFiles = [];
                        this.isModalOpened = false;
                        this.$notify({
                            type: 'success',
                            text: "Files uploaded successfully."
                        });
                    }
                }, error => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        text: 'There was some error during uploading the files.'
                    });
                });
            },
            addFiles(event) {
                let files = event.target.files || event.dataTransfer.files;
                if (files.length) {
                    this.addedFiles = files;
                }
            },
            removeFile(file, index) {
                this.loading = true;
                this.$http.delete('files/' + file.name).then(response => {
                    this.loading = false;
                    this.$notify({
                        type: 'success',
                        text: 'You have successfully removed this file.'
                    });
                    if (/image\/.*/.test(file.type)) {
                        this.images.splice(index, 1);
                    } else {
                        this.files.splice(index, 1);
                    }
                }, error => {
                    console.log(error);
                    this.loading = false;
                    this.$notify({
                        type: 'error',
                        text: 'There was an error during removing this file.'
                    });
                });
            },
            loadFiles() {
                this.$loading = true;
                this.$http.get('files').then(response => {
                    this.images = response.body["images"];
                    this.files = response.body["others"];
                    this.$loading = false;
                });
            }
        }, components: {
            Modal
        }
    }
</script>