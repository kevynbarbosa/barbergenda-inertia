export interface Role {
    id: number;
    name: string;
    display_name: string;
    description?: string;
    created_at: string;
    updated_at: string;
    users_count?: number;
}

export interface RoleFormData {
    name: string;
    display_name: string;
    description?: string;
}