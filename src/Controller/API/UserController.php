<?php

namespace TinyFramework\Controller\API;

use TinyFramework\Models\Http\JsonResponse;
use TinyFramework\Models\User;

// TODO: Implement Swagger annotations
class UserController
{
    public function index(): JsonResponse
    {
        return json_response([
            'data' => User::all()
        ]);
    }

    public function store(): JsonResponse
    {
        $user = new User(
            username: request()->get('username'),
            email: request()->get('email'),
        );

        $insertedId = $user->save();
        $user->id = $insertedId;

        return json_response($user);
    }

    public function show(int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return abort('User not found', 404);
        }

        return json_response($user);
    }

    public function update(int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return abort('User not found', 404);
        }

        $user->username = request()->get('username');
        $user->email = request()->get('email');
        $user->save();

        return json_response($user);
    }

    public function delete(int $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return abort('User not found', 404);
        }

        $user->delete();

        return json_response([
            'message' => 'User deleted successfully'
        ]);
    }
}
