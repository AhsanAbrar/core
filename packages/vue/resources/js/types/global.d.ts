interface AppData {
  app_name: string
  csrf_token: string
  debug: boolean
  header_logo: string | null
  is_super_admin: boolean
  prefix: string
  permissions: string[]
  translations: Record<string, string>
  user: {
    id: number
    name: string
    email: string
    avatar: string
  }
}
