<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService
{
    public function getFilteredPosts(array $filters): LengthAwarePaginator
    {
        $query = Post::search($filters['search'] ?? '')
            ->filter($filters);

        if (!empty($filters['sort_by'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_direction'] ?? 'asc');
        }

        return $query->paginate($filters['per_page'] ?? 10);
    }
}
