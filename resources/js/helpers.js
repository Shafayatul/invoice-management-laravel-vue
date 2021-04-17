import moment from "moment"
import Cookies from "js-cookie"
import { _ms } from "./consts"

/**
 * *********************************************************
 * |||| This will return a callback function to import  ||||
 * |||| specified Vue component inside views folder     ||||
 * *********************************************************
 * @param {String} name - ViewName or child-folder/ChildView
 * @returns {Function} () => import('@/views/ViewName.vue')
 */
export const view = name => () => import(`@/views/${name}.vue`)

/**
 * *********************************************************
 * |||| This will return a callback function to import  ||||
 * |||| specified Vue component inside layouts folder   ||||
 * *********************************************************
 * @param {String} name - LayoutName
 * @returns {Function} () => import('@/layouts/LayoutName.vue')
 */
export const layout = name => () => import(`@/layouts/${name}.vue`)

/**
 * *********************************************************
 * |||| Fix the type of any variable                    ||||
 * *********************************************************
 * @param {any} value - 'true' -> true | '18' -> 18 | 'null' -> null
 */
export const fixType = value => {
  const types = {
    'true': true,
    'false': false,
    'null': null,
    'undefined': undefined
  }
  return +value
    ? +value
    : value in types
      ? types[value]
      : value
}

/**
 * ******************************************************
 * |||| Query String                                 ||||
 * ******************************************************
 */
export const qs = {
  /** @param {{key:String}} data */
  stringify(data) {
    var str = [];
    for (var p in data)
      // eslint-disable-next-line no-prototype-builtins
      if (data.hasOwnProperty(p)) {
        str.push(
          encodeURIComponent(p) + "=" +
          encodeURIComponent(data[p])
        );
      }
    return str.join("&");
  },
  /** @param {String} queryString */
  parse(queryString) {
    return queryString
      .split('&')
      .reduce((acc, q) => {
        let [key, value] = q.split('=')
        return { ...acc, [key]: fixType(value) }
      }, {})
  }
}

export const camelToSnake = str => str.replace(
  /[A-Z]/g,
  letter => `_${letter.toLowerCase()}`
);

/**
 * *********************************************************
 * |||| Convert string, array or object to snake camel ||||
 * *********************************************************
 */
export const snakeToCamel = data => {
  if (typeof data === 'string') {
    return data.replace(
      /([-_][a-z])/g,
      (group) => group.toUpperCase()
        .replace('-', '')
        .replace('_', '')
    );
  }
  if (Array.isArray(data)) {
    return data.map(el => snakeToCamel(el))
  }
  return Object.entries(data)
    .reduce((nData, [key, value]) => {
      nData[snakeToCamel(key)] =
        (value && typeof value === 'object')
          ? snakeToCamel(value)
          : value;
      return nData
    }, {})
}

export const isObject = (v) => {
  return (typeof v === "object" || typeof v === 'function') && (v !== null)
}

export const isEmpty = v => {
  if ([undefined, null, ''].includes(v)) return true
  if (Array.isArray(v) && !v.length) return true
  if (isObject(v) && !Object.keys(v).length) return true
  return false
}

/**
 * *********************************************************
 * |||| Generate random ID                              ||||
 * *********************************************************
 */
export const miniId = (len = 3) =>
  Math.random()
    .toString(36)
    .slice(len <= 10 ? -len : -10);

/**
 * *********************************************************
 * |||| Format the axios success response
 * *********************************************************
 */
export const toSuccess = res => {
  // console.log('|-toSuccess-|', res);
  if (res.status === 'error') return toError(res)
  let mRes = { ...res }
  delete mRes.code
  delete mRes.status
  let data = {
    ...snakeToCamel(mRes),
    error: false,
    success: true,
    statusCode: res.code,
    statusText: res.status,
    message: res.message || 'Request succeeded'
  }
  return data
}

/**
 * *********************************************************
 * |||| Format the axios error response
 * *********************************************************
 */
export const toError = error => {
  let data = { error: true, success: false }
  if (error.response) {
    const res = error.response
    console.log(res);
    data = {
      ...data,
      statusCode: res.status,
      statusText: res.statusText || res.data.status,
    }
    if (res.data.errors) data.errors = snakeToCamel(res.data.errors)
    if (res.message) data.message = res.message
  }
  data.message = data.message || error.message
  return data
}

/**
 * *********************************************************
 * |||| Convert object to form data
 * *********************************************************
 */
export const toFormData = obj => {
  let formData = new FormData()
  Object.entries(obj).forEach(([key, value]) => {
    formData.append(key, value)
  })
  return formData
}

/**
 * *********************************************************
 * |||| Manage cookies
 * *********************************************************
 */
export const cookies = {
  set(...cookies) {
    if (typeof cookies !== 'object')
      throw new Error('Cookies have to be an object or array.')
    cookies.forEach(({
      key, value, secure = false,
      expires = Date.now() + _ms.week
    }) => {
      Cookies.set(key, value, {
        expires,
        secure
      })
    })
  },
  get(...keys) {
    return keys.reduce((acc, key) => {
      acc[key] = fixType(Cookies.get(key))
      return acc
    }, {})
  },
  remove(...keys) {
    keys.forEach(key => {
      Cookies.remove(key)
    })
  }
}

export const formatDate = (date) => {
  let d = date ? new Date(date) : new Date(),
    month = '' + (d.getMonth() + 1),
    day = '' + d.getDate(),
    year = d.getFullYear();
  if (month.length < 2)
    month = '0' + month;
  if (day.length < 2)
    day = '0' + day;
  return [year, month, day].join('-');
}

export const hasHistory = () => {
  return window.history.length > 2;
}

/**
 *
 * @param {number} time - How much time have to sleep
 * @param {string} unit - Unit of time ['s'-seconds,'m'-minute,'h'-houre]
 */
export const sleep = (time, unit) => new Promise((resolve) => {
  const ms = unit === 's' ? time * 1000 : unit === 'm' ? time * 60 * 1000 : unit === 'h' ? time * 60 * 60 * 1000 : time;
  setTimeout(() => resolve(), ms);
});

export const to12Hour = time => {
  // Check correct time format and split into components
  time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

  if (time.length > 1) { // If time format correct
    time = time.slice(1);  // Remove full string match value
    time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
    time[0] = +time[0] % 12 || 12; // Adjust hours
  }
  return time.join(''); // return adjusted time or original string
}

/**
 * *********************************************************
 * // Difference between dates
 * *********************************************************
 */
export const diff = {
  day(a, b = Date.now()) {
    return moment(new Date(a).getTime()).diff(b, "days")
  }
}