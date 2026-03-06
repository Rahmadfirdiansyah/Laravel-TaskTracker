export interface User {
    id: number;
    name: string;
    email: string;
}

export interface Project {
    id: number;
    name: string;
    description?: string;
    user_id: number;
}

export interface Task {
    id: number;
    title: string;
    description?: string;
    status: 'pending' | 'in_progress' | 'completed' | 'todo' | 'done';
    user_id: number;
    category_id: number;
}

export interface Category {
    id: number;
    name: string;
}