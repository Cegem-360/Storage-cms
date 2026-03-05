<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Invoice;

final class InvoiceObserver
{
    public function created(Invoice $invoice): void
    {
        $this->refreshCustomerBalance($invoice);
    }

    public function updated(Invoice $invoice): void
    {
        if ($invoice->isDirty('status') || $invoice->isDirty('total_amount')) {
            $this->refreshCustomerBalance($invoice);
        }
    }

    public function deleted(Invoice $invoice): void
    {
        $this->refreshCustomerBalance($invoice);
    }

    private function refreshCustomerBalance(Invoice $invoice): void
    {
        $invoice->customer?->refreshBalance();
    }
}
