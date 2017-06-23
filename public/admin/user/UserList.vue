<template>
    <section class="card">
        <div class="card-header flex flex-wrap items-center justify-between">
            <h1 class="m0"><i class="fa fa-group"></i>Users</h1>
            <router-link to="/users/0" class="btn btn-outline moonstone-blue border-moonstone-blue">
                <i class="fa fa-user-plus"></i>Add
            </router-link>
        </div>
        <article class="table">
            <div class="row table-header">
                <div class="col">Username</div>
                <div class="col">Full Name</div>
                <div class="col">Role</div>
                <div class="col">Actions</div>
            </div>
            <div class="row center" v-for="(user, index) in users">
                <div class="col">{{user.username}}</div>
                <div class="col">{{fullName(user)}}</div>
                <div class="col">{{roleName(user)}}</div>
                <div class="col">
                    <router-link :to="{name: 'user', params: {id: user.id}}" class="btn action">
                        <i class="fa fa-external-link"></i>Detail
                    </router-link>
                    <a href="#1" @click.stop.prevent="removeUser(user, index)" class="btn action danger" v-if="canRemove(user)">
                        <i class="fa fa-trash"></i>Remove
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
                users: this.$parent.users,
                roleMapping: this.$parent.roleMapping
            };
        }, methods: {
            fullName(user) {
                return user.first_name + " " + user.last_name;
            },
            roleName(user) {
                return this.roleMapping[user.role];
            },
            removeUser(user, index) {
                this.$loading = true;
                this.$http.delete('users/' + user.id).then(response => {
                    this.$loading = false;
                    this.$notify({
                        type: 'success',
                        text: 'You have successfully removed this user.'
                    });
                    this.users.splice(index, 1);
                }, error => {
                    console.log(error);
                    this.$loading = false;
                    this.$notify({
                        type: 'error',
                        text: 'There was some error during removing this user.'
                    });
                });
            },
            canRemove(user) {
                return user.id !== this.$currentUser.id && this.canAccess('user.remove');
            }
        }
    }
</script>