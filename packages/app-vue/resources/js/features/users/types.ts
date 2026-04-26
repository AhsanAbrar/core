import type { Pagination } from '@types'

// ----------------------------------------------------
// Core model
// ----------------------------------------------------

export interface User {
  id: number
  name: string
  email: string

  created_at: string | null
  updated_at: string | null
}

// ----------------------------------------------------
// API responses
// ----------------------------------------------------

export interface UsersIndexResponse {
  data: User[]
  pagination: Pagination
}

export interface UsersIndexParams {
  page?: number
  search?: string
}

// ----------------------------------------------------
// User form types
// ----------------------------------------------------

export interface UserFormData {
  name: string
  email: string
  password?: string
}

// ----------------------------------------------------
// User form response
// ----------------------------------------------------

export interface UserFormFieldsResponse {
  data: {
    name: string | null
    email: string | null
    password: string | null
  }
  options: Record<string, never>
}

// ----------------------------------------------------
// User API response
// ----------------------------------------------------

export interface UserResponse {
  data: User
}
