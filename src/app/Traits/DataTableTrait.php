<?php

namespace App\Traits;

trait DataTableTrait
{
    /**
     * @param $query
     * @param array $searchBy
     * @return mixed
     */
    public function toTable($query, $searchBy = ['name'])
    {
        $count = request()->get('count', 10);
        $search = request()->get('search');
        $sortBy = request()->get('sort_by');
        $sort = request()->get('sort', 'desc');

        if ($search) {
            foreach ($searchBy as $index => $key) {
                if ($index === 0) {
                    $query = $query->where($key, 'LIKE', '%' . $search . '%');
                } else {
                    $query = $query->orWhere($key, 'LIKE', '%' . $search . '%');
                }
            }
        }

        if ($sortBy) {
            $query = $query->orderBy($sortBy, $sort);
        }

        return $query->paginate($count);
    }
}
