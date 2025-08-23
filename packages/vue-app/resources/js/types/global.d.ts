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

interface SidebarNav {
  label: string
  uri: string
  icon: FunctionalComponent<HTMLAttributes & VNodeProps>
  permission?: string
  create?: string
  createPermission?: string
  activeCollapsible?: string[]
  items?: {
    label: string
    uri: string
    permission?: string
  }[]
}

interface SettingsNav {
  label: string
  uri: string
  permission?: string
}
