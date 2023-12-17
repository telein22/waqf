<?php

namespace Application\Modules\Invoices;

class ParticipantInvoice extends InvoiceGenerator
{
    protected function getViewName(): string
    {
        return 'Invoices/participant_invoice';
    }

    public function generateInvoiceName(string $invoiceNo): string
    {
        return "participation_invoice_{$invoiceNo}.pdf";
    }


}