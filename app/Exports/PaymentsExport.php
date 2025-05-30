<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bankAccountId;
    protected $startDate;
    protected $endDate;

    /**
     * Accept filter parameters in the constructor.
     */
    public function __construct($bankAccountId = null, $startDate = null, $endDate = null)
    {
        $this->bankAccountId = $bankAccountId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    /**
     * Fetch the collection of payments to export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Payment::query()->with(['payable', 'bankAccount']);

        if (!empty($this->bankAccountId)) {
            $query->where('bank_account_id', $this->bankAccountId);
        }

        if (!empty($this->startDate)) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if (!empty($this->endDate)) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query->get();
    }

    /**
     * Define the headings for the exported file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Date of Payment',
            'Reference',
            'Bank Account',
            'Amount',
        ];
    }

    /**
     * Map the data to match the Blade file format.
     *
     * @param Payment $payment
     * @return array
     */
    public function map($payment): array
    {
        return [
            formatDate($payment->date_of_payment),
            $payment->payable ? class_basename($payment->payable) . ' #' . $payment->payable->id : '',
            $payment->bank_account_id ? $payment->bankAccount->account_name : '',
            formatCurrency($payment->amount),
        ];
    }
}
