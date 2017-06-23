/**
 * @author Lukáš
 * @version 1.0.0
 * @package Topazz
 */

class Permissions {

    init(router, permissions = [], failPath) {
        this.setPermissions(permissions);

        router.beforeEach((to, from, next) => {
            if (to.meta.hasOwnProperty('permissions')) {
                if (!this.check(to.meta.permissions)) {
                    if (to.meta.hasOwnProperty('failPath')) {
                        failPath = to.meta.failPath;
                    }
                    return next(failPath);
                }
            }
            return next();
        });
    }

    check(permissions) {
        if (typeof permissions !== Array) {
            permissions = [permissions];
        }
        if (!this.permissions.length) {
            return false;
        }
        for (let permission in permissions) {
            if (!this.permissions.includes(permission)) {
                return false;
            }
        }
        return true;
    }

    setPermissions(permissions) {
        this.permissions = Array.isArray(permissions) ? permissions : [permissions];
    }
}

let perm = new Permissions();

Permissions.install = function (Vue, {router, permissions, failPath}) {
    perm.init(router, permissions, failPath);

    Vue.prototype.$can = function (permissions) {
        return perm.check(permissions);
    };

    Vue.prototype.$permissions = perm;
};

export default Permissions;