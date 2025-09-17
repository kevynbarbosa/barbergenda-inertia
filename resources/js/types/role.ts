import type { Permission } from './permission';

export interface Role {
    id: number;
    display_name: string;
    description?: string;
    created_at: string;
    updated_at: string;
    users_count?: number;
    permissions?: Permission[];
}

export interface RoleFormData {
    display_name: string;
    description?: string;
    permissions?: string[];
}