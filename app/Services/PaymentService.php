<?php

namespace App\Services;

use App\Repositories\PaymentRepository;
use App\Repositories\RepairRepository;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        protected PaymentRepository $paymentRepository,
        protected RepairRepository $repairRepository
    ) {}

    /**
     * Create a new payment record with business logic
     *
     * @param array $data Payment data
     * @return Payment Created payment
     */
    public function createPayment(array $data)
    {
        // Set default values and business logic
        $data['is_active'] = true;
        $data['status'] = $data['status'] ?? 'pending';

        // Validate repair exists and get current paid amount
        $repair = $this->repairRepository->findById($data['repair_id']);
        $currentPaidAmount = $repair->paid_amount ?? 0;

        // Validate payment amount doesn't exceed remaining amount
        $remainingAmount = $repair->total_amount - $currentPaidAmount;
        if ($data['amount'] > $remainingAmount) {
            throw new \Exception('Payment amount exceeds remaining repair amount');
        }

        return $this->paymentRepository->create($data);
    }

    /**
     * Update payment record
     *
     * @param int $paymentId Payment ID
     * @param array $data Updated data
     * @return Payment Updated payment
     */
    public function updatePayment($paymentId, array $data)
    {
        $payment = $this->paymentRepository->findById($paymentId);

        // If status is being updated to completed, update repair paid amount
        if (isset($data['status']) && $data['status'] === 'completed' && $payment->status !== 'completed') {
            $repair = $this->repairRepository->findById($payment->repair_id);

            // Update repair paid amount
            $repair->paid_amount = ($repair->paid_amount ?? 0) + $payment->amount;

            // If fully paid, update repair status
            if ($repair->paid_amount >= $repair->total_amount) {
                $repair->status = 'completed';
            }

            $repair->save();
        }

        return $this->paymentRepository->update($payment, $data);
    }

    /**
     * Get all payments
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of payments
     */
    public function getAllPayments()
    {
        return $this->paymentRepository->getAll();
    }

    /**
     * Get payment by ID
     *
     * @param int $paymentId Payment ID
     * @return Payment Payment model
     */
    public function getPaymentById($paymentId)
    {
        return $this->paymentRepository->findById($paymentId);
    }

    /**
     * Get payments by repair ID
     *
     * @param int $repairId Repair ID
     * @return \Illuminate\Database\Eloquent\Collection Collection of payments
     */
    public function getPaymentsByRepair($repairId)
    {
        return $this->paymentRepository->findByRepair($repairId);
    }

    /**
     * Get payments by status
     *
     * @param string $status Payment status
     * @return \Illuminate\Database\Eloquent\Collection Collection of payments
     */
    public function getPaymentsByStatus($status)
    {
        return $this->paymentRepository->findByStatus($status);
    }

    /**
     * Get payments by payment method
     *
     * @param string $paymentMethod Payment method
     * @return \Illuminate\Database\Eloquent\Collection Collection of payments
     */
    public function getPaymentsByMethod($paymentMethod)
    {
        return $this->paymentRepository->findByPaymentMethod($paymentMethod);
    }

    /**
     * Get successful payments
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of successful payments
     */
    public function getSuccessfulPayments()
    {
        return $this->paymentRepository->findSuccessful();
    }

    /**
     * Get failed payments
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of failed payments
     */
    public function getFailedPayments()
    {
        return $this->paymentRepository->findFailed();
    }

    /**
     * Get pending payments
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of pending payments
     */
    public function getPendingPayments()
    {
        return $this->paymentRepository->findPending();
    }

    /**
     * Calculate total revenue
     *
     * @param string|null $status Filter by status
     * @return float Total revenue
     */
    public function calculateTotalRevenue($status = null)
    {
        return $this->paymentRepository->calculateTotalRevenue($status);
    }

    /**
     * Calculate revenue by payment method
     *
     * @param string $paymentMethod Payment method
     * @return float Total revenue
     */
    public function calculateRevenueByMethod($paymentMethod)
    {
        return $this->paymentRepository->calculateRevenueByMethod($paymentMethod);
    }

    /**
     * Toggle active status (soft delete/restore)
     *
     * @param int $paymentId Payment ID
     * @return Payment Updated payment
     */
    public function togglePaymentActiveStatus($paymentId)
    {
        $payment = $this->paymentRepository->findById($paymentId);
        return $this->paymentRepository->toggleActiveStatus($payment);
    }

    /**
     * Permanently delete payment
     *
     * @param int $paymentId Payment ID
     * @return bool Deletion result
     */
    public function deletePayment($paymentId)
    {
        $payment = $this->paymentRepository->findById($paymentId);
        return $this->paymentRepository->delete($payment);
    }

    /**
     * Process payment and update repair status
     *
     * @param array $data Payment data
     * @return array Processing results
     */
    public function processPayment(array $data)
    {
        DB::beginTransaction();

        try {
            // Create the payment
            $data['status'] = 'completed';
            $payment = $this->createPayment($data);

            // Update repair paid amount and status
            $repair = $this->repairRepository->findById($data['repair_id']);

            // Update paid amount
            $repair->paid_amount = ($repair->paid_amount ?? 0) + $payment->amount;

            // If fully paid, update repair status
            if ($repair->paid_amount >= $repair->total_amount) {
                $repair->status = 'completed';
            }

            $repair->save();

            DB::commit();

            return [
                'payment' => $payment,
                'repair' => $repair,
                'remaining_amount' => $repair->total_amount - $repair->paid_amount,
                'fully_paid' => $repair->paid_amount >= $repair->total_amount
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Refund payment
     *
     * @param int $paymentId Payment ID
     * @param string $reason Refund reason
     * @return array Refund results
     */
    public function refundPayment($paymentId, $reason)
    {
        DB::beginTransaction();

        try {
            $payment = $this->paymentRepository->findById($paymentId);

            if ($payment->status !== 'completed') {
                throw new \Exception('Only completed payments can be refunded');
            }

            // Update payment status
            $payment->status = 'refunded';
            $payment->notes = 'Refunded: ' . $reason;
            $payment->save();

            // Update repair paid amount
            $repair = $this->repairRepository->findById($payment->repair_id);
            $repair->paid_amount = max(0, ($repair->paid_amount ?? 0) - $payment->amount);

            // If repair was completed but now has remaining amount, update status
            if ($repair->status === 'completed' && $repair->paid_amount < $repair->total_amount) {
                $repair->status = 'ready_for_pickup';
            }

            $repair->save();

            DB::commit();

            return [
                'payment' => $payment,
                'repair' => $repair,
                'refund_amount' => $payment->amount,
                'remaining_amount' => $repair->total_amount - $repair->paid_amount
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment refund failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get payment statistics
     *
     * @return array Payment statistics
     */
    public function getPaymentStatistics()
    {
        $totalRevenue = $this->calculateTotalRevenue('completed');
        $pendingRevenue = $this->calculateTotalRevenue('pending');
        $failedRevenue = $this->calculateTotalRevenue('failed');

        $cashRevenue = $this->calculateRevenueByMethod('cash');
        $cardRevenue = $this->calculateRevenueByMethod('card');
        $onlineRevenue = $this->calculateRevenueByMethod('online');

        return [
            'total_revenue' => $totalRevenue,
            'pending_revenue' => $pendingRevenue,
            'failed_revenue' => $failedRevenue,
            'revenue_by_method' => [
                'cash' => $cashRevenue,
                'card' => $cardRevenue,
                'online' => $onlineRevenue
            ],
            'payment_method_distribution' => [
                'cash_percentage' => $totalRevenue > 0 ? ($cashRevenue / $totalRevenue) * 100 : 0,
                'card_percentage' => $totalRevenue > 0 ? ($cardRevenue / $totalRevenue) * 100 : 0,
                'online_percentage' => $totalRevenue > 0 ? ($onlineRevenue / $totalRevenue) * 100 : 0
            ]
        ];
    }
}
