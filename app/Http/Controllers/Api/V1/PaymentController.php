<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Payment Controller
 *
 * Handles API requests for payment management.
 * Follows RESTful conventions and industry standards.
 */
class PaymentController extends Controller
{
    /**
     * Create a new PaymentController instance.
     *
     * @param  PaymentService  $paymentService  Payment service instance
     */
    public function __construct(protected PaymentService $paymentService)
    {
        //
    }

    /**
     * List all payments.
     *
     * GET /api/v1/payments
     *
     * @return AnonymousResourceCollection  Collection of payments
     */
    public function index(): AnonymousResourceCollection
    {
        $payments = $this->paymentService->getAllPayments();
        return PaymentResource::collection($payments);
    }

    /**
     * Create a new payment.
     *
     * POST /api/v1/payments
     *
     * @param  StorePaymentRequest  $request  Validated request data
     * @return PaymentResource  Created payment resource
     */
    public function store(StorePaymentRequest $request): PaymentResource
    {
        $payment = $this->paymentService->createPayment($request->validated());
        return new PaymentResource($payment);
    }

    /**
     * Show a specific payment.
     *
     * GET /api/v1/payments/{payment}
     *
     * @param  int  $id  Payment ID
     * @return PaymentResource  Payment resource
     */
    public function show(int $id): PaymentResource
    {
        $payment = $this->paymentService->getPaymentById($id);
        return new PaymentResource($payment);
    }

    /**
     * Update a payment.
     *
     * PUT/PATCH /api/v1/payments/{payment}
     *
     * @param  UpdatePaymentRequest  $request  Validated request data
     * @param  int  $id  Payment ID
     * @return PaymentResource  Updated payment resource
     */
    public function update(UpdatePaymentRequest $request, int $id): PaymentResource
    {
        $payment = $this->paymentService->updatePayment($id, $request->validated());
        return new PaymentResource($payment);
    }

    /**
     * Toggle active status of a payment.
     *
     * PUT /api/v1/payments/{payment}/active
     *
     * @param  int  $id  Payment ID
     * @return PaymentResource  Updated payment resource
     */
    public function toggleActive(int $id): PaymentResource
    {
        $payment = $this->paymentService->togglePaymentActiveStatus($id);
        return new PaymentResource($payment);
    }

    /**
     * Soft delete a payment (toggle active status).
     *
     * DELETE /api/v1/payments/{payment}
     *
     * @param  int  $id  Payment ID
     * @return JsonResponse  Success response
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->paymentService->deletePayment($id);
        return response()->json(['success' => $result], JsonResponse::HTTP_OK);
    }

    /**
     * Get payments by repair ID.
     *
     * GET /api/v1/repairs/{repair}/payments
     *
     * @param  int  $repairId  Repair ID
     * @return AnonymousResourceCollection  Collection of payments for the repair
     */
    public function getByRepair(int $repairId): AnonymousResourceCollection
    {
        $payments = $this->paymentService->getPaymentsByRepair($repairId);
        return PaymentResource::collection($payments);
    }

    /**
     * Get payments by status.
     *
     * GET /api/v1/payments/status/{status}
     *
     * @param  string  $status  Payment status
     * @return AnonymousResourceCollection  Collection of payments with the specified status
     */
    public function getByStatus(string $status): AnonymousResourceCollection
    {
        $payments = $this->paymentService->getPaymentsByStatus($status);
        return PaymentResource::collection($payments);
    }

    /**
     * Get payments by payment method.
     *
     * GET /api/v1/payments/method/{paymentMethod}
     *
     * @param  string  $paymentMethod  Payment method
     * @return AnonymousResourceCollection  Collection of payments using the specified method
     */
    public function getByMethod(string $paymentMethod): AnonymousResourceCollection
    {
        $payments = $this->paymentService->getPaymentsByMethod($paymentMethod);
        return PaymentResource::collection($payments);
    }

    /**
     * Get successful payments.
     *
     * GET /api/v1/payments/successful
     *
     * @return AnonymousResourceCollection  Collection of successful payments
     */
    public function getSuccessful(): AnonymousResourceCollection
    {
        $payments = $this->paymentService->getSuccessfulPayments();
        return PaymentResource::collection($payments);
    }

    /**
     * Get failed payments.
     *
     * GET /api/v1/payments/failed
     *
     * @return AnonymousResourceCollection  Collection of failed payments
     */
    public function getFailed(): AnonymousResourceCollection
    {
        $payments = $this->paymentService->getFailedPayments();
        return PaymentResource::collection($payments);
    }

    /**
     * Get pending payments.
     *
     * GET /api/v1/payments/pending
     *
     * @return AnonymousResourceCollection  Collection of pending payments
     */
    public function getPending(): AnonymousResourceCollection
    {
        $payments = $this->paymentService->getPendingPayments();
        return PaymentResource::collection($payments);
    }

    /**
     * Process payment and update repair status.
     *
     * POST /api/v1/payments/process
     *
     * @param  StorePaymentRequest  $request  Validated request data
     * @return JsonResponse  Payment processing results
     */
    public function processPayment(StorePaymentRequest $request): JsonResponse
    {
        $results = $this->paymentService->processPayment($request->validated());

        return response()->json([
            'message' => 'Payment processed successfully',
            'results' => $results
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Refund payment.
     *
     * POST /api/v1/payments/{payment}/refund
     *
     * @param  int  $paymentId  Payment ID
     * @param  \Illuminate\Http\Request  $request  Request with refund reason
     * @return JsonResponse  Refund results
     */
    public function refundPayment(int $paymentId, \Illuminate\Http\Request $request): JsonResponse
    {
        $reason = $request->input('reason', 'Customer requested refund');
        $results = $this->paymentService->refundPayment($paymentId, $reason);

        return response()->json([
            'message' => 'Payment refunded successfully',
            'results' => $results
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Get payment statistics.
     *
     * GET /api/v1/payments/statistics
     *
     * @return JsonResponse  Payment statistics
     */
    public function getStatistics(): JsonResponse
    {
        $statistics = $this->paymentService->getPaymentStatistics();

        return response()->json([
            'message' => 'Payment statistics retrieved successfully',
            'statistics' => $statistics
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Calculate total revenue.
     *
     * GET /api/v1/payments/revenue/{status?}
     *
     * @param  string|null  $status  Filter by status (optional)
     * @return JsonResponse  Total revenue calculation
     */
    public function calculateRevenue(string $status = null): JsonResponse
    {
        $totalRevenue = $this->paymentService->calculateTotalRevenue($status);

        return response()->json([
            'total_revenue' => $totalRevenue,
            'formatted_revenue' => number_format($totalRevenue, 2),
            'status_filter' => $status
        ], JsonResponse::HTTP_OK);
    }
}
