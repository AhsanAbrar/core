import { appData } from '@/app-data'
import axios, { type AxiosInstance, type AxiosResponse } from 'axios'
import { useAxiosErrorHandler } from './axiosErrorHandler'
import { useTopLoaderStore } from '@stores/top-loader'

const http: AxiosInstance = axios.create({
  baseURL: appData.prefix ? `/${appData.prefix}/api/` : '/api/',
  headers: {
    ...(appData.debug ? { 'X-Spanvel-Error-HTML': 'true' } : {}),
  },
})

const { handleError } = useAxiosErrorHandler()

/**
 * Request → start loader
 */
http.interceptors.request.use(config => {
  const topLoader = useTopLoaderStore()
  topLoader.start()
  return config
})

/**
 * Response → stop loader
 */
http.interceptors.response.use(
  (response: AxiosResponse) => {
    const topLoader = useTopLoaderStore()
    topLoader.stop()
    return response
  },
  error => {
    const topLoader = useTopLoaderStore()
    topLoader.stop()
    return handleError(error)
  },
)

export { http }
