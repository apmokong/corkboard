
export interface Blog {

    id: number;
    title: string;
    content: string;
    status: 'published' | 'draft' | 'archived';
    created_by: number;
    created_at: string;
    formatted_created_at: string;
    updated_at: string;
    deleted_at?: string | null;
    creator: {
        name: string;
    }
}