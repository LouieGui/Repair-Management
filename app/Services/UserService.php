<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

/**
 * User Service
 *
 * Handles business logic for user operations.
 * Acts as an intermediary between controllers and repositories.
 */
class UserService
{
    /**
     * Create a new UserService instance.
     *
     * @param  UserRepository  $userRepository  User repository instance
     */
    public function __construct(protected UserRepository $userRepository)
    {
        //
    }

    /**
     * Create a new user with business logic.
     *
     * Automatically hashes password and sets default values.
     *
     * @param  array  $data  User data
     * @return User  Created user
     */
    public function createUser(array $data): User
    {
        // Hash password before storing
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Set default values if not provided
        $data['is_active'] = $data['is_active'] ?? true;

        return $this->userRepository->create($data);
    }

    /**
     * Update an existing user.
     *
     * Handles password hashing if password is being updated.
     *
     * @param  int  $userId  User ID to update
     * @param  array  $data  Data to update
     * @return User  Updated user
     */
    public function updateUser(int $userId, array $data): User
    {
        $user = $this->userRepository->findById($userId);

        // Hash password if it's being updated
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Remove password from data if empty to avoid overwriting
            unset($data['password']);
        }

        return $this->userRepository->update($user, $data);
    }

    /**
     * Get a single user by ID.
     *
     * @param  int  $userId  User ID to retrieve
     * @return User  Found user
     */
    public function getUser(int $userId): User
    {
        return $this->userRepository->findById($userId);
    }

    /**
     * List users with optional filters.
     *
     * @param  array  $filters  Filters to apply
     * @return Collection  Collection of users
     */
    public function listUsers(array $filters = []): Collection
    {
        return $this->userRepository->list($filters);
    }

    /**
     * Toggle user active status (soft delete/restore).
     *
     * @param  int  $userId  User ID to toggle
     * @return User  Updated user
     */
    public function toggleUserActiveStatus(int $userId): User
    {
        $user = $this->userRepository->findById($userId);
        return $this->userRepository->toggleActiveStatus($user);
    }

    /**
     * Find user by email.
     *
     * @param  string  $email  Email to search for
     * @return User|null  Found user or null
     */
    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * Check if email is already taken by another user.
     *
     * @param  string  $email  Email to check
     * @param  int|null  $excludeUserId  User ID to exclude from check
     * @return bool  True if email is available, false if taken
     */
    public function isEmailAvailable(string $email, ?int $excludeUserId = null): bool
    {
        $query = User::where('email', $email);

        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }

        return !$query->exists();
    }
}
