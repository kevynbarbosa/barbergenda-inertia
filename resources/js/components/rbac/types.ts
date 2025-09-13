export interface Permission {
  id: string
  name: string
  description: string
  enabled: boolean
}

export interface Module {
  id: string
  name: string
  description: string
  permissions: Permission[]
}
