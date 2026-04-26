// import { appData } from '@/app-data'
import { AxiosError } from 'axios'
import type { AxiosResponse } from 'axios'
// import { useFlashStore } from 'Store/flash'
// import { useErrorModal } from 'Use/error-modal'

export function useAxiosErrorHandler() {
  // const errorModal = useErrorModal()

  // Safely use the Pinia store to show flash messages after initialization.
  // const flashMessage = (message: string) => {
  //   const { danger } = useFlashStore()

  //   danger(message)
  // }

  const handleSessionExpiration = () => {
    // flashMessage('Session expired, redirecting...')
    // setTimeout(() => location.reload(), 1000)
  }

  const handleUnauthorized = () => {
    // flashMessage('Unauthorized access.')

    window.location.href = '/403'
  }

  const handleNotFound = () => {
    // flashMessage('Oops! Not found.')
    // window.location.href = '/404'
  }

  const handleValidationErrors = (error: AxiosError) => {
    // flashMessage(error.message)
  }

  const handleServiceUnavailable = () => {
    // flashMessage('Service Unavailable. Please try again later.')
  }

  const handleGenericErrors = (response: AxiosResponse) => {
    const errorMessage = response?.data?.message || 'An unexpected error occurred.'

    // appData.debug ? errorModal.error(response) : flashMessage(errorMessage)
  }

  const handleHttpStatusError = (
    status: number,
    error: AxiosError,
    response: AxiosResponse,
  ) => {
    switch (status) {
      case 401:
      case 419:
        handleSessionExpiration()
        break
      case 403:
        handleUnauthorized()
        break
      case 404:
        handleNotFound()
        break
      case 422:
        handleValidationErrors(error)
        break
      case 503:
        handleServiceUnavailable()
        break
      default:
        handleGenericErrors(response)
        break
    }
  }

  const handleError = (error: AxiosError): Promise<AxiosError> => {
    if (error.response) {
      handleHttpStatusError(error.response.status, error, error.response)
    } else {
      // flashMessage(error.message)
    }

    return Promise.reject(error)
  }

  return { handleError }
}
