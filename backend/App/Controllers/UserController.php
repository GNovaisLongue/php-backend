<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Models\User;

class UserController
{
    private User $users;

    public function __construct(?User $users = null)
    {
        $this->users = $users ?? new User();
    }

    public function index(): Response
    {
        return Response::json($this->users->getAllUsers());
    }

    public function show(int|string $id): Response
    {
        $user = $this->users->getUserById($id);
        if ($user === null) {
            return Response::error('User not found', 404);
        }

        return Response::json($user);
    }

    public function store(Request $request): Response
    {
        $id = $this->users->insertUser($request->getBody());

        return Response::json(['id' => $id], 201);
    }

    public function destroy(int|string $id): Response
    {
        $deleted = $this->users->deleteUser($id);
        if ($deleted === 0) {
            return Response::error('User not found', 404);
        }

        return Response::json(['deleted' => $deleted]);
    }
}
