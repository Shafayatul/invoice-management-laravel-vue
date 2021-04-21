import { h, GET,PUT, POST,PATCH, DELETE } from './handler'

const api = {
    get: (endpoint, queries = {}) => h(GET, endpoint, queries),
    post: (endpoint, data) => h(POST, endpoint, data),
    put: (endpoint, data) => h(PUT, endpoint, data),
    patch: (endpoint, data) => h(PATCH, endpoint, data),
    delete: endpoint => h(DELETE, endpoint),

    // AUTHENTICATION
    auth: {
        login: data => h(POST, "/login", data),
        register: data => h(POST, "/register", data),
        logout: () => h(POST, "/logout"),
        fetchProfile: () => h(GET, "/login-user-info"),
        forgot: data => h(POST, "/password/forgot", data),
        reset: data => h(POST, "/password/reset", data),
        check: token => h(GET, "/password/check", token),
        update: data => h(POST, "/update-password", data)
    },

    // USERS
    users: {
        getAll: queries => h(GET, "/user/index", queries),
        update: (data, id) => h(POST, "/user/update?user_id=" + id, data),
        delete: id => h(GET, "/user/destroy?user_id=" + id),
        create: data => h(POST, "/user/store", data),
        block: id => h(GET, "/user/block-or-unblock/" + id),
        reAssign: data => h(POST, "user/assign-company", data)
        // get: id => h(GET, "/users/show/" + id),
        // search: data => h(POST, "/users/search-employee", data)
    },
    //Company
    company: {
        getAll: queries => h(GET, "/company/index", queries),
        update: (data, id) => h(POST, "/company/update?company_id=" + id, data),
        delete: id => h(GET, "/company/destroy?company_id=" + id),
        create: data => h(POST, "/company/store", data),
        compnayList: () => h(GET, "company/company-data")
        // get         : id          => h(GET,     '/users/show/' + id),
        // search      : data        => h(POST,    '/users/search-employee', data)
    },
    payment: {
        getAll: queries => h(GET, "/payment-category/index", queries),
        update: (data, id) =>h(POST, "/payment-category/update?payment_category_id=" + id, data),
        delete: id =>h(GET, "/payment-category/destroy?payment_category_id=" + id),
        create: data => h(POST, "/payment-category/store", data)
        // get         : id          => h(GET,     '/users/show/' + id),
        // search      : data        => h(POST,    '/users/search-employee', data)
    }
};

export default api