export interface Permission {
    id: number;
    name: string;
    module: string;
    display_name: string;
    description?: string;
    created_at: string;
    updated_at: string;
}

export interface PermissionFormData {
    name: string;
    module: string;
    display_name: string;
    description?: string;
}

export interface PermissionsByModule {
    [module: string]: Permission[];
}