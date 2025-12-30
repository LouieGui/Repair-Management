<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * User Repository
 *
 * Handles database operations for User model.
 * Provides a clean interface for user data access.
 */
class UserRepository
{
    /**
     * Create a new user record.
     *
     * @param  array  $data  User data to create
     * @return User  Created user instance
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Find a user by ID.
     *
     * @param  int  $id  User ID to find
     * @return User  Found user instance
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException  If user not found
     */
    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }

    /**
     * Update a user record.
     *
     * @param  User  $user  User instance to update
     * @param  array  $data  Data to update
     * @return User  Updated user instance
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    /**
     * List users with optional filters.
     *
     * @param  array  $filters  Filters to apply (e.g., ['role' => 'admin', 'is_active' => true])
     * @return Collection  Collection of User instances
     */
    public function list(array $filters = []): Collection
    {
        $query = User::query();

        // Apply filters
        if (isset($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('username', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Toggle user active status (soft delete/restore).
     *
     * @param  User  $user  User instance to toggle
     * @return User  Updated user instance
     */
    public function toggleActiveStatus(User $user): User
    {
        $user->is_active = !$user->is_active;
        $user->save();
        return $user;
    }

    /**
     * Find user by email.
     *
     * @param  string  $email  Email to search for
     * @return User|null  Found user or null if not found
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
