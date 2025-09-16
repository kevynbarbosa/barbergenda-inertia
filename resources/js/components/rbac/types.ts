import type { Permission as BasePermission } from '@/types/permission';

export interface Permission extends BasePermission {
  enabled: boolean
}

export interface Module {
  id: string
  name: string
  description: string
  permissions: Permission[]
}
