<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;

class UserController
{
    public function getAllUsers()
    {
        $pagination = self::pagination(User::all());

        return response()->json([
            'data' => UserResource::collection($pagination->paginated),
            'total' => $pagination->total,
            'currentPage' => $pagination->currentPage,
            'last_page' => $pagination->last_page,
            'perPage' => $pagination->perPage,
            'status' => true,
            'message' => 'success'
        ]);
    }

    private static function pagination($collection, $perPage = 10)
    {
        $page = (int)request('page');

        $start = ($page == 1) ? 0 : ($page - 1) * $perPage;

        $total = $collection->count();

        $pages_count = (int)$total / (int)$perPage;

        $page_counter = is_double($pages_count) ? (int)$pages_count + 1 : $pages_count;

        $data['total'] = $total;

        $data['paginated'] = $page == 0 ? $collection : $collection->slice($start, $perPage);

        $data['currentPage'] = $page == 0 ? 1 : $page;

        $data['perPage'] = $perPage;

        $data['last_page'] = $pages_count >= 1 ? $page_counter : 1;

        return (object)$data;
    }
}
