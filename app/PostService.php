<?php

namespace App;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService
{
    public function getFilteredPosts(array $filters): LengthAwarePaginator
    {
        $results = Post::search($filters['search'] ?? '')
            ->when(!empty($filters['category_id']), function ($query) use ($filters) {
                $query->where('category_id', $filters['category_id']);
            })
            ->when(!empty($filters['min_price']), function ($query) use ($filters) {
                $query->where('price', '>=', $filters['min_price']);
            })
            ->when(!empty($filters['max_price']), function ($query) use ($filters) {
                $query->where('price', '<=', $filters['max_price']);
            })
            ->when(!empty($filters['sort_by']), function ($query) use ($filters) {
                $query->orderBy($filters['sort_by'], $filters['sort_direction']);
            })
            ->paginate();

        return $results;
    }
}
