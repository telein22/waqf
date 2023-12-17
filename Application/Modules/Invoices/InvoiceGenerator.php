<?php

namespace Application\Modules\Invoices;

use Application\Models\Coupons;
use Application\Models\Settings;
use Application\Modules\SendibleAsAttachment;
use Application\ThirdParties\AWS\AWS;
use Application\Values\Invoice as InvoiceValue;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use System\Core\Config;
use System\Core\Model;
use System\Responses\View;

abstract class InvoiceGenerator implements SendibleAsAttachment
{
    protected string $fileName;

    abstract protected function getViewName(): string;

    abstract protected function generateInvoiceName(string $invoiceNo): string;

    public static function generate(InvoiceValue $invoice): InvoiceGenerator
    {
        $invoiceGenerator = new static();
        $invoiceNo = $invoiceGenerator->generateInvoiceNumber($invoice->order->getId());

        $view = new View();
        $view->set($invoiceGenerator->getViewName(), [
            'registrationNumber' => $invoiceGenerator->getRegistrationNumber(),
            'userName' => $invoice->order->getUserName(),
            'invoiceNo' => $invoiceNo,
            'invoiceDate' => date('m-d-Y'),
            'itemName' => $invoice->item->getName(),
            'itemDesc' => $invoice->item->getDescription(),
            'fee' => $invoice->order->getAmount(),
            'platformFee' => $invoiceGenerator->getPlatformFee(),
            'vat' => $invoiceGenerator->getVat(),
            'isDiscount' => !is_null($invoice->order->getCoupon()),
            'amountPaid' => $invoice->order->getPayable(),
            'qty' => 1,
            'discount' => $invoiceGenerator->getDiscount($invoice),
            'amountDue' => 0,
        ]);

        $mpdf = new Mpdf();
        $mpdf->imageVars['logo'] = file_get_contents('Application/Assets/images/logo.png'); // This is how to include image inside the PDF
        $mpdf->WriteHTML($view->content());

        $invoiceGenerator->setFileName($invoiceGenerator->generateInvoiceName($invoiceNo));
        $fullPath = $invoiceGenerator->getDirectory() . DIRECTORY_SEPARATOR . $invoiceGenerator->getFileName();
        $mpdf->Output($fullPath , Destination::FILE);

        AWS::syncFileWithS3($invoiceGenerator->getFileName(), $fullPath, AWS::INVOICE_DIRECTORY);

        return $invoiceGenerator;
    }

    public function setFileName($name): void
    {
        $this->fileName = $name;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getFileCaption(): string
    {
        return 'هذه الفاتورة لتأكيد عملية الدفع';
    }

    public function getFullPath(): string
    {
        return $this->getDirectory() . DIRECTORY_SEPARATOR . $this->getFileName();
    }

    private function getDirectory(): string
    {
        return dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'Storage' . DIRECTORY_SEPARATOR . 'Invoices';
    }

    private function getRegistrationNumber(): string
    {
        $config = Config::get('Website');
        return $config->telein_registration_number;
    }

    private function generateInvoiceNumber(string $seed): string
    {
        return str_pad($seed, 8, 0, STR_PAD_LEFT);
    }

    private function getPlatformFee(): float
    {
        $settingM = Model::get(Settings::class);
        return $settingM->take(Settings::KEY_PLATFORM_FEES);
    }

    private function getDiscount(InvoiceValue $invoice)
    {
        $coupon = $invoice->order->getCoupon();
        if (!is_null($coupon)) {
            $discount = $coupon->amount;
            return $coupon->type == Coupons::TYPE_PERCENT ? "$discount%" : $discount;
        }

        return 0;
    }

    private function getVat() {
        $settingM = Model::get(Settings::class);
        return $settingM->take(Settings::KEY_VAT);
    }
}