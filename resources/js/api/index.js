import { h, GET,PUT, POST,PATCH, DELETE } from './handler'

const api = {

  get           : (endpoint, queries = {})  => h(GET,     endpoint,   queries),
  post          : (endpoint, data)          => h(POST,    endpoint,   data),
  put           : (endpoint, data)          => h(PUT,     endpoint,   data),
  patch         : (endpoint, data)          => h(PATCH,   endpoint,   data),
  delete        : (endpoint)                => h(DELETE,  endpoint),

  // AUTHENTICATION
  auth: {
    login       : data        => h(POST,    '/login', data),
    register    : data        => h(POST,    '/register', data),
    logout      : ()          => h(POST,    '/logout'),
  },

  // USERS
  users: {
    getAll      : queries     => h(GET,     '/users/index', queries),
    get         : id          => h(GET,     '/users/show/' + id),
    save        : data        => h(POST,    '/users/create', data),
    update      : data        => h(POST,    '/users/update', data),
    delete      : id          => h(DELETE,  '/users/delete/' + id),
    search      : data        => h(POST,    '/users/search-employee', data)
  }
}

export default api