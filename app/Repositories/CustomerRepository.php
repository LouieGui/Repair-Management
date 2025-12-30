<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

/**
 * Customer Repository
 *
 * Handles database operations for Customer model.
 * Provides a clean interface for customer data access.
 */
class CustomerRepository
{
    /**
     * Create a new customer record.
     *
     * @param  array  $data  Customer data to create
     * @return Customer  Created customer instance
     */
    public function create(array $data): Customer
    {
        // Generate unique ID if not provided
        if (empty($data['unique_id'])) {
            $data['unique_id'] = $this->generateUniqueId();
        }

        return Customer::create($data);
    }

    /**
     * Find a customer by ID.
     *
     * @param  int  $id  Customer ID to find
     * @return Customer  Found customer instance
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException  If customer not found
     */
    public function findById(int $id): Customer
    {
        return Customer::findOrFail($id);
    }

    /**
     * Update a customer record.
     *
     * @param  Customer  $customer  Customer instance to update
     * @param  array  $data  Data to update
     * @return Customer  Updated customer instance
     */
    public function update(Customer $customer, array $data): Customer
    {
        $customer->update($data);
        return $customer;
    }

    /**
     * List customers with optional filters.
     *
     * @param  array  $filters  Filters to apply (e.g., ['is_active' => true, 'search' => 'John'])
     * @return Collection  Collection of Customer instances
     */
    public function list(array $filters = []): Collection
    {
        $query = Customer::query();

        // Apply filters
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('fullname', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('contact', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('unique_id', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Toggle customer active status (soft delete/restore).
     *
     * @param  Customer  $customer  Customer instance to toggle
     * @return Customer  Updated customer instance
     */
    public function toggleActiveStatus(Customer $customer): Customer
    {
        $customer->is_active = !$customer->is_active;
        $customer->save();
        return $customer;
    }

    /**
     * Find customer by email.
     *
     * @param  string  $email  Email to search for
     * @return Customer|null  Found customer or null if not found
     */
    public function findByEmail(string $email): ?Customer
    {
        return Customer::where('email', $email)->first();
    }

    /**
     * Find customer by contact number.
     *
     * @param  string  $contact  Contact number to search for
     * @return Customer|null  Found customer or null if not found
     */
    public function findByContact(string $contact): ?Customer
    {
        return Customer::where('contact', $contact)->first();
    }

    /**
     * Find customer by unique ID.
     *
     * @param  string  $uniqueId  Unique ID to search for
     * @return Customer|null  Found customer or null if not found
     */
    public function findByUniqueId(string $uniqueId): ?Customer
    {
        return Customer::where('unique_id', $uniqueId)->first();
    }

    /**
     * Generate a unique customer ID.
     *
     * Format: CUS-YYYYMMDD-XXXX (e.g., CUS-20231230-0001)
     *
     * @return string  Generated unique ID
     */
    public function generateUniqueId(): string
    {
        $datePrefix = 'CUS-' . now()->format('Ymd');
        $lastCustomer = Customer::where('unique_id', 'like', $datePrefix . '%')
                              ->orderBy('unique_id', 'desc')
                              ->first();

        if ($lastCustomer) {
            $lastNumber = (int) substr($lastCustomer->unique_id, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $datePrefix . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
