// export const _baseURL = process.env.BASE_URL
export const _isDev = process.env.NODE_ENV === 'development'
export const _isProd = process.env.NODE_ENV === 'production'

// export const _localServer = process.env.VUE_APP_LOCAL_SERVER
// export const _remoteServer = process.env.VUE_APP_REMOTE_SERVER
// export const _serverBaseURL = _isDev ? _localServer : _remoteServer

export const _ms = (() => {
  let sec = 1000
  let min = sec * 60
  let hour = min * 60
  let day = hour * 24
  let week = day * 7
  let month = day * 30
  let year = day * 365
  return {
    sec, min, hour,
    day, week, month, year
  }
})()
