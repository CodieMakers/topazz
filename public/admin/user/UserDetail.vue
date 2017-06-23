<template>
    <div id="user-detail" class="has-bottom-panel">
        <section class="card">
            <h1>
                <i class="fa" :class="{'fa-user-plus': isNewRecord, 'fa-user': !isNewRecord}"></i>
                {{fullName}}
            </h1>
            <article class="form">
                <div class="row clearfix flex items-center">
                    <label for="username" class="label flex-auto required">Username:</label>
                    <template v-if="isNewRecord && user.username !== ''">
                        <i class="fa fa-check mx1 mr2 green" v-if="usernameAvailable"></i>
                        <i class="fa fa-times mx1 mr2 golden-gate" v-else=""></i>
                    </template>
                    <input type="text" id="username" v-model="user.username" class="input col-8" required/>
                </div>
                <div class="row clearfix flex items-center">
                    <label for="email" class="label flex-auto required">Email:</label>
                    <input type="email" id="email" v-model="user.email" class="input col-8" required
                           placeholder="e.g. user@example.com"/>
                </div>
                <div class="row clearfix flex items-center">
                    <label for="password" class="label flex-auto" :class="{'required': isNewRecord}">Password:</label>
                    <input type="password" id="password" v-model="user.password" class="input col-8"
                           :required="isNewRecord"/>
                </div>
                <div class="row clearfix flex items-center">
                    <label for="password-check" class="label flex-auto" :class="{'required': isNewRecord}">Password check:</label>
                    <i class="fa fa-question-circle mx1" title="This field is only for confirmation"></i>
                    <i class="fa fa-check mx1 mr2 green" v-if="passwordMatch"></i>
                    <i class="fa fa-times mx1 mr2 golden-gate" v-else></i>
                    <input type="password" id="password-check" v-model="passwordCheck" class="input col-8"
                           :required="isNewRecord"/>
                </div>
            </article>
            <article class="form">
                <div class="row clearfix flex items-center">
                    <label for="first-name" class="label flex-auto">First name:</label>
                    <input type="text" id="first-name" v-model="user.first_name" class="input col-8"/>
                </div>
                <div class="row clearfix flex items-center">
                    <label for="last-name" class="label flex-auto">Last name:</label>
                    <input type="text" id="last-name" v-model="user.last_name" class="input col-8"/>
                </div>
                <div class="row clearfix flex items-center">
                    <label for="role" class="label flex-auto">Role:</label>
                    <select id="role" v-model="user.role" class="select col-8">
                        <option value="0">Administrator</option>
                        <option value="1">Moderator</option>
                        <option value="2">Editor</option>
                        <option value="3">Blogger</option>
                    </select>
                </div>
                <div class="row clearfix flex items-center">
                    <label for="profile-picture" class="label flex-auto">Profile picture:</label>
                    <div class="col-8 flex flex-wrap items-center justify-between" id="profile-picture">
                        <div class="clearfix">
                            <button class="btn btn-outline moonstone-blue" disabled>
                                <i class="fa fa-folder-open-o"></i>Open library
                            </button>
                            <button class="btn btn-outline moonstone-blue" @click.stop="useGravatar">
                                <i class="fa fa-wordpress"></i>Use Gravatar
                            </button>
                        </div>
                        <div class="clearfix col-3">
                            <img :src="user.profile_picture" alt="Preview" height="100"/>
                        </div>
                    </div>
                </div>
            </article>
            <article class="footnote">
                <i class="fa fa-sm fa-star golden-gate mr2"></i>Those inputs are required.
            </article>
        </section>
        <section class="bottom-panel">
            <div class="right">
                <button type="button" class="btn btn-primary white bg-golden-gate" @click.stop="remove" v-if="!isNewRecord && canRemove(user)">
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

    let gravatar = require('gravatar');

    export default {
        props: ['id'],
        data() {
            let _user = {
                username: '',
                email: '',
                password: '',
                first_name: '',
                last_name: '',
                role: 3,
                profile_picture: '/public/img/profile.png'
            };
            if (this.id != 0) {
                _user = this.$parent.users.find(item => item.id == this.id);
                _user.password = '';
            }
            return {
                user: _user,
                passwordCheck: '',
                saving: false
            };
        }, computed: {
            isNewRecord() {
                return this.id == 0;
            },
            fullName() {
                return this.user.first_name + " " + this.user.last_name;
            },
            passwordMatch() {
                return this.user.password !== '' && this.user.password === this.passwordCheck;
            },
            usernameAvailable() {
                return this.$parent.users.filter(user => user.username === this.user.username).length === 0;
            }
        }, methods: {
            useGravatar() {
                this.user.profile_picture = gravatar.url(this.user.email, {}, true);
            },
            save() {
                if (this.isNewRecord) {
                    this.$loading = true;
                    this.$http.put("users", this.user).then(response => {
                        this.saving = false;
                        this.$parent.users.push(response.body);
                        this.loadCsrfToken();
                        this.$notify({
                            type: 'success',
                            text: 'You have successfully created this user.'
                        });
                        this.$loading = false;
                        this.$router.push({name: 'users'});
                    }, error => {
                        console.log(error);
                        this.loadCsrfToken();
                        this.$notify({
                            type: 'error',
                            text: 'Something went wrong with creating this user.'
                        });
                    });
                } else {
                    this.saving = true;
                    let data = Object.assign({},this.user);
                    if (this.user.password === '') {
                        delete data.password;
                    }
                    this.$http.post('users/' + this.user.id, data).then(response => {
                        this.saving = false;
                        this.user = response.body;
                        this.$parent.users.splice(this.$parent.users.findIndex(user => user.id === this.user.id), 1, this.user);
                        this.loadCsrfToken();
                    }, error => {
                        console.log(error);
                        this.saving = false;
                        this.loadCsrfToken();
                        this.$notify({
                            type: 'error',
                            text: 'Something went wrong with saving this user.'
                        });
                    });
                }
            },
            remove() {
                if (!this.isNewRecord) {
                    this.$loading = true;
                    this.$http.delete('users/' + this.user.id).then(response => {
                        this.$loading = false;
                        this.$notify({
                            type: 'success',
                            text: 'You have successfully removed this user.'
                        });
                        this.$parent.users.splice(this.$parent.users.findIndex(item => item.id === this.user.id), 1);
                        this.$router.replace({name: 'users'});
                    }, error => {
                        console.log(error);
                        this.$loading = false;
                        this.$notify({
                            type: 'error',
                            text: 'There was some error during removing this user.'
                        });
                    });
                }
            },
            canRemove(user) {
                return user.id !== this.$currentUser.id && this.canAccess('user.remove');
            }
        }
    }
</script>