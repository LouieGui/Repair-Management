<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

/**
 * Customer Service
 *
 * Handles business logic for customer operations.
 * Acts as an intermediary between controllers and repositories.
 */
class CustomerService
{
    /**
     * Create a new CustomerService instance.
     *
     * @param  CustomerRepository  $customerRepository  Customer repository instance
     */
    public function __construct(protected CustomerRepository $customerRepository)
    {
        //
    }

    /**
     * Create a new customer with business logic.
     *
     * Automatically generates unique ID and sets default values.
     *
     * @param  array  $data  Customer data
     * @return Customer  Created customer
     */
    public function createCustomer(array $data): Customer
    {
        // Set default values if not provided
        $data['is_active'] = $data['is_active'] ?? true;

        return $this->customerRepository->create($data);
    }

    /**
     * Update an existing customer.
     *
     * @param  int  $customerId  Customer ID to update
     * @param  array  $data  Data to update
     * @return Customer  Updated customer
     */
    public function updateCustomer(int $customerId, array $data): Customer
    {
        $customer = $this->customerRepository->findById($customerId);
        return $this->customerRepository->update($customer, $data);
    }

    /**
     * Get a single customer by ID.
     *
     * @param  int  $customerId  Customer ID to retrieve
     * @return Customer  Found customer
     */
    public function getCustomer(int $customerId): Customer
    {
        return $this->customerRepository->findById($customerId);
    }

    /**
     * List customers with optional filters.
     *
     * @param  array  $filters  Filters to apply
     * @return Collection  Collection of customers
     */
    public function listCustomers(array $filters = []): Collection
    {
        return $this->customerRepository->list($filters);
    }

    /**
     * Toggle customer active status (soft delete/restore).
     *
     * @param  int  $customerId  Customer ID to toggle
     * @return Customer  Updated customer
     */
    public function toggleCustomerActiveStatus(int $customerId): Customer
    {
        $customer = $this->customerRepository->findById($customerId);
        return $this->customerRepository->toggleActiveStatus($customer);
    }

    /**
     * Find customer by email.
     *
     * @param  string  $email  Email to search for
     * @return Customer|null  Found customer or null
     */
    public function findCustomerByEmail(string $email): ?Customer
    {
        return $this->customerRepository->findByEmail($email);
    }

    /**
     * Find customer by contact number.
     *
     * @param  string  $contact  Contact number to search for
     * @return Customer|null  Found customer or null
     */
    public function findCustomerByContact(string $contact): ?Customer
    {
        return $this->customerRepository->findByContact($contact);
    }

    /**
     * Find customer by unique ID.
     *
     * @param  string  $uniqueId  Unique ID to search for
     * @return Customer|null  Found customer or null
     */
    public function findCustomerByUniqueId(string $uniqueId): ?Customer
    {
        return $this->customerRepository->findByUniqueId($uniqueId);
    }

    /**
     * Check if contact number is already taken by another customer.
     *
     * @param  string  $contact  Contact number to check
     * @param  int|null  $excludeCustomerId  Customer ID to exclude from check
     * @return bool  True if contact is available, false if taken
     */
    public function isContactAvailable(string $contact, ?int $excludeCustomerId = null): bool
    {
        $query = Customer::where('contact', $contact);

        if ($excludeCustomerId) {
            $query->where('id', '!=', $excludeCustomerId);
        }

        return !$query->exists();
    }

    /**
     * Check if email is already taken by another customer.
     *
     * @param  string  $email  Email to check
     * @param  int|null  $excludeCustomerId  Customer ID to exclude from check
     * @return bool  True if email is available, false if taken
     */
    public function isEmailAvailable(string $email, ?int $excludeCustomerId = null): bool
    {
        $query = Customer::where('email', $email);

        if ($excludeCustomerId) {
            $query->where('id', '!=', $excludeCustomerId);
        }

        return !$query->exists();
    }

    /**
     * Generate a unique customer ID.
     *
     * @return string  Generated unique ID
     */
    public function generateUniqueCustomerId(): string
    {
        return $this->customerRepository->generateUniqueId();
    }
}
