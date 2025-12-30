<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository
{
    /**
     * Create a new payment record
     *
     * @param array $data Payment data
     * @return Payment Created payment
     */
    public function create(array $data)
    {
        return Payment::create($data);
    }

    /**
     * Find payment by ID
     *
     * @param int $id Payment ID
     * @return Payment Payment model
     */
    public function findById($id)
    {
        return Payment::with(['repair'])->findOrFail($id);
    }

    /**
     * Update payment record
     *
     * @param Payment $payment Payment model
     * @param array $data Updated data
     * @return Payment Updated payment
     */
    public function update(Payment $payment, array $data)
    {
        $payment->update($data);
        return $payment;
    }

    /**
     * Get all payments with relationships
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of payments
     */
    public function getAll()
    {
        return Payment::with(['repair'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find payments by repair ID
     *
     * @param int $repairId Repair ID
     * @return \Illuminate\Database\Eloquent\Collection Collection of payments
     */
    public function findByRepair($repairId)
    {
        return Payment::with(['repair'])
            ->where('repair_id', $repairId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find payments by status
     *
     * @param string $status Payment status
     * @return \Illuminate\Database\Eloquent\Collection Collection of payments
     */
    public function findByStatus($status)
    {
        return Payment::with(['repair'])
            ->where('status', $status)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find payments by payment method
     *
     * @param string $paymentMethod Payment method
     * @return \Illuminate\Database\Eloquent\Collection Collection of payments
     */
    public function findByPaymentMethod($paymentMethod)
    {
        return Payment::with(['repair'])
            ->where('payment_method', $paymentMethod)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find successful payments
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of successful payments
     */
    public function findSuccessful()
    {
        return Payment::with(['repair'])
            ->where('status', 'completed')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find failed payments
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of failed payments
     */
    public function findFailed()
    {
        return Payment::with(['repair'])
            ->where('status', 'failed')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find pending payments
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of pending payments
     */
    public function findPending()
    {
        return Payment::with(['repair'])
            ->where('status', 'pending')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Calculate total revenue from payments
     *
     * @param string|null $status Filter by status
     * @return float Total revenue
     */
    public function calculateTotalRevenue($status = null)
    {
        $query = Payment::where('is_active', true);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->sum('amount');
    }

    /**
     * Calculate total revenue by payment method
     *
     * @param string $paymentMethod Payment method
     * @return float Total revenue
     */
    public function calculateRevenueByMethod($paymentMethod)
    {
        return Payment::where('payment_method', $paymentMethod)
            ->where('status', 'completed')
            ->where('is_active', true)
            ->sum('amount');
    }

    /**
     * Toggle active status (soft delete/restore)
     *
     * @param Payment $payment Payment model
     * @return Payment Updated payment
     */
    public function toggleActiveStatus(Payment $payment)
    {
        $payment->is_active = !$payment->is_active;
        $payment->save();
        return $payment;
    }

    /**
     * Permanently delete payment
     *
     * @param Payment $payment Payment model
     * @return bool Deletion result
     */
    public function delete(Payment $payment)
    {
        return $payment->delete();
    }
}
