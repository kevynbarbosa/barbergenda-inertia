export interface Solicitation {
    id: number;
    status: 'pending' | 'approved' | 'rejected' | 'in_review';
    person?: {
        id: number;
        name: string;
        document: string;
    };
    stage?: {
        id: number;
        name: string;
        description: string;
        sla: number;
    };
    created_at: string;
    updated_at: string;
    estimated_completion_at?: string;
    is_overdue: boolean;
    sla_status: 'on_time' | 'overdue' | 'unknown';
    sla_status_color: string;
}

export interface PaginatedSolicitations {
    current_page: number;
    data: Solicitation[];
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

export interface SolicitationsFilters {
    search: string | null;
}