import { http } from '@utils/http'
import type {
  UsersIndexResponse,
  UsersIndexParams,
  UserFormFieldsResponse,
  UserFormData,
  UserResponse,
} from './types'

/**
 * Fetch paginated users list.
 */
export async function usersIndex(params: UsersIndexParams): Promise<UsersIndexResponse> {
  const { data } = await http.get('users', { params })
  return data
}

/**
 * Fetch create user form fields.
 */
export async function usersCreate(): Promise<UserFormFieldsResponse> {
  const { data } = await http.get('users/create')
  return data
}

/**
 * Fetch edit user form fields with current values.
 */
export async function usersEdit(id: number | string): Promise<UserFormFieldsResponse> {
  const { data } = await http.get(`users/${id}/edit`)
  return data
}

/**
 * Create a new user.
 */
export async function usersStore(payload: UserFormData): Promise<UserResponse> {
  const { data } = await http.post('users', payload)
  return data
}

/**
 * Update an existing user.
 */
export async function usersUpdate(
  id: number | string,
  payload: UserFormData,
): Promise<UserResponse> {
  const { data } = await http.put(`users/${id}`, payload)
  return data
}

/**
 * Remove a user.
 */
export async function usersRemove(id: number | string): Promise<UserResponse> {
  const { data } = await http.delete(`users/${id}`)
  return data
}
