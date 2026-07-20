<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::query()->select(['id', 'name', 'email', 'created_at']);

        $sort = $request->query('sort');
        if ($sort) {
            $direction = 'asc';
            if (str_starts_with($sort, '-')) {
                $direction = 'desc';
                $sort = substr($sort, 1);
            }

            if (in_array($sort, ['name', 'email'], true)) {
                $query->orderBy($sort, $direction);
            }
        } else {
            $query->orderBy('id', 'asc');
        }

        $perPage = 10;
        $paginator = $query->paginate($perPage)->appends($request->query());

        $data = [];
        foreach ($paginator as $user) {
            $data[] = [
                'type' => 'users',
                'id' => (string) $user->id,
                'attributes' => [
                    'name' => $user->name,
                    'created_at' => $user->created_at->toIso8601String(),
                ],
            ];
        }

        $pagination = [
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
        ];

        $links = [
            'self' => $paginator->url($paginator->currentPage()),
            'first' => $paginator->url(1),
            'last' => $paginator->url($paginator->lastPage()),
            'prev' => $paginator->previousPageUrl(),
            'next' => $paginator->nextPageUrl(),
        ];

        return response()->json([
            'data' => $data,
            'links' => $links,
            'meta' => [
                'pagination' => $pagination,
            ],
        ]);
    }
}
