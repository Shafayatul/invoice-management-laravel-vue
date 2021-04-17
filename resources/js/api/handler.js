import axios from 'axios'
import Cookies from 'js-cookie';
import { qs, toSuccess, toError, toFormData } from '../helpers';

// Creating a axios instance
let api = axios.create({ baseURL: '/api' })

// Request handler
export const h = async (callback, ...data) => {
  //Setting up access token to the header
  let accessToken = Cookies.get('accessToken')
  if (accessToken) api.defaults.headers = {
    Authorization: `Bearer ${accessToken}`
  }
  // handling request
  try { return toSuccess((await callback(...data)).data) }
  catch (error) { return toError(error) }
};

export const GET = (endpoint, query = {}) => api.get(`${endpoint}?${qs.stringify(query)}`)
export const PUT = (endpoint, data) => api.put(endpoint, toFormData(data))
export const POST = (endpoint, data) => api.post(endpoint, toFormData(data))
export const PATCH = (endpoint, data) => api.patch(endpoint, toFormData(data))
export const DELETE = (endpoint, id) => api.delete(endpoint, id)

