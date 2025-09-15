import type { Role } from './role';

// Re-export Role for convenience
export type { Role } from './role';

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    roles: Role[];
}

export interface UserFormData {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    roles: number[];
}

export interface PaginatedUsers {
    current_page: number;
    data: User[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}