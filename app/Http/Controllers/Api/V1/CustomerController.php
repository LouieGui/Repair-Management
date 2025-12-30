<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Customer Controller
 *
 * Handles API requests for customer management.
 * Follows RESTful conventions and industry standards.
 */
class CustomerController extends Controller
{
    /**
     * Create a new CustomerController instance.
     *
     * @param  CustomerService  $customerService  Customer service instance
     */
    public function __construct(protected CustomerService $customerService)
    {
        //
    }

    /**
     * List all customers.
     *
     * GET /api/v1/customers
     *
     * @param  UpdateCustomerRequest  $request  Request with optional filters
     * @return AnonymousResourceCollection  Collection of customers
     */
    public function index(UpdateCustomerRequest $request): AnonymousResourceCollection
    {
        $filters = $request->only(['is_active', 'search']);

        $customers = $this->customerService->listCustomers($filters);

        return CustomerResource::collection($customers);
    }

    /**
     * Create a new customer.
     *
     * POST /api/v1/customers
     *
     * @param  StoreCustomerRequest  $request  Validated request data
     * @return CustomerResource  Created customer resource
     */
    public function store(StoreCustomerRequest $request): CustomerResource
    {
        $customer = $this->customerService->createCustomer($request->validated());

        return new CustomerResource($customer);
    }

    /**
     * Show a specific customer.
     *
     * GET /api/v1/customers/{customer}
     *
     * @param  int  $customer  Customer ID
     * @return CustomerResource  Customer resource
     */
    public function show(int $customer): CustomerResource
    {
        $customer = $this->customerService->getCustomer($customer);

        return new CustomerResource($customer);
    }

    /**
     * Update a customer.
     *
     * PUT/PATCH /api/v1/customers/{customer}
     *
     * @param  UpdateCustomerRequest  $request  Validated request data
     * @param  int  $customer  Customer ID
     * @return CustomerResource  Updated customer resource
     */
    public function update(UpdateCustomerRequest $request, int $customer): CustomerResource
    {
        $updatedCustomer = $this->customerService->updateCustomer($customer, $request->validated());

        return new CustomerResource($updatedCustomer);
    }

    /**
     * Soft delete a customer (toggle active status).
     *
     * DELETE /api/v1/customers/{customer}
     *
     * @param  int  $customer  Customer ID
     * @return JsonResponse  Success response
     */
    public function destroy(int $customer): JsonResponse
    {
        $updatedCustomer = $this->customerService->toggleCustomerActiveStatus($customer);

        return response()->json([
            'message' => 'Customer status updated successfully',
            'customer' => new CustomerResource($updatedCustomer),
            'status' => $updatedCustomer->is_active ? 'activated' : 'deactivated'
        ]);
    }
}
