<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * User Controller
 *
 * Handles API requests for user management.
 * Follows RESTful conventions and industry standards.
 */
class UserController extends Controller
{
    /**
     * Create a new UserController instance.
     *
     * @param  UserService  $userService  User service instance
     */
    public function __construct(protected UserService $userService)
    {
        //
    }

    /**
     * List all users.
     *
     * GET /api/v1/users
     *
     * @param  UpdateUserRequest  $request  Request with optional filters
     * @return AnonymousResourceCollection  Collection of users
     */
    public function index(UpdateUserRequest $request): AnonymousResourceCollection
    {
        $filters = $request->only(['role', 'is_active', 'search']);

        $users = $this->userService->listUsers($filters);

        return UserResource::collection($users);
    }

    /**
     * Create a new user.
     *
     * POST /api/v1/users
     *
     * @param  StoreUserRequest  $request  Validated request data
     * @return UserResource  Created user resource
     */
    public function store(StoreUserRequest $request): UserResource
    {
        $user = $this->userService->createUser($request->validated());

        return new UserResource($user);
    }

    /**
     * Show a specific user.
     *
     * GET /api/v1/users/{user}
     *
     * @param  int  $user  User ID
     * @return UserResource  User resource
     */
    public function show(int $user): UserResource
    {
        $user = $this->userService->getUser($user);

        return new UserResource($user);
    }

    /**
     * Update a user.
     *
     * PUT/PATCH /api/v1/users/{user}
     *
     * @param  UpdateUserRequest  $request  Validated request data
     * @param  int  $user  User ID
     * @return UserResource  Updated user resource
     */
    public function update(UpdateUserRequest $request, int $user): UserResource
    {
        $updatedUser = $this->userService->updateUser($user, $request->validated());

        return new UserResource($updatedUser);
    }

    /**
     * Soft delete a user (toggle active status).
     *
     * DELETE /api/v1/users/{user}
     *
     * @param  int  $user  User ID
     * @return JsonResponse  Success response
     */
    public function destroy(int $user): JsonResponse
    {
        $updatedUser = $this->userService->toggleUserActiveStatus($user);

        return response()->json([
            'message' => 'User status updated successfully',
            'user' => new UserResource($updatedUser),
            'status' => $updatedUser->is_active ? 'activated' : 'deactivated'
        ]);
    }
}
