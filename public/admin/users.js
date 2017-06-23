/**
 * @author Lukáš Krotovič
 * @version 1.0.0
 * @package Topazz
 */

import Users from "./user/Users.vue";
import UserList from "./user/UserList.vue";
import UserDetail from "./user/UserDetail.vue";

export const UsersRouting = {
    path: '/users', component: Users,
    meta: {
        breadcrumb: 'Users',
        permissions: ['system.users']
    },
    children: [
        {
            path: '',
            component: UserList,
            name: 'users',
            meta: {breadcrumb: 'All', permissions: ['system.users']}
        }, {
            path: ':id',
            component: UserDetail,
            name: 'user',
            props: true,
            meta: {breadcrumb: 'Detail', permissions: ['user.all']}
        }
    ]
};