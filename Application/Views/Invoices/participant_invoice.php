<?php

use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get(Language::class);
?>

<html dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans; }
        th, td {
            text-align: right;
        }

        table {
            border-collapse: collapse;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table .thead-light th {
            color: #495057;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .border-0 {
            border: 0 !important;
        }

        .float-left {
            float: left !important;
        }

        .float-right {
            float: right !important;
        }

        .clearfix::after {
            display: block;
            clear: both;
            content: "";
        }


    </style>
</head>

<body>
<div class="float-right">
    <img src="var:logo" width="75px" height="50px" />
</div>
<div class="float-left">
    <p><?= $lang('inv_title') ?></p>
</div>
<div class="clearfix"></div>
<div class="float-right">
    <p><?= $lang('inv_company_title') ?></p>
</div>
<div class="clearfix"></div>
<div class="float-right">
    <p><?= $lang('inv_registration_no') ?>: <?= $registrationNumber ?></p>
</div>
<div class="clearfix"></div>


<hr/>

<div>

    <div>
        <p><?= $lang('inv_invoice_to') ?>: <span><?= $userName ?></span></p>
    </div>
    <div>
        <p><span><?= $lang('inv_invoice_no') ?>: <?= $invoiceNo ?>#</span></p>
    </div>
    <div>
        <p><?= $lang('inv_invoice_date') ?>: <span><?= $invoiceDate ?></span></p>
    </div>
</div>
<div>
    <table dir="rtl" class="table table-bordered">
        <thead class="thead-light">
        <tr>
            <th><?= $lang('inv_item') ?></th>
            <th><?= $lang('inv_description') ?></th>
            <th><?= $lang('inv_fee') ?></th>
            <th><?= $lang('inv_qty') ?></th>
            <th><?= $lang('inv_sum') ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?= $itemName?></td>
            <td><?= $itemDesc?></td>
            <td><?= $fee?></td>
            <td><?= $qty?></td>
            <td><?= $fee?></td>
        </tr>
        <tr>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td><?= $lang('inv_platform_fee') ?></td>
            <td><?= $platformFee?>%</td>

        </tr>
        <?php if ($vat > 0) : ?>
        <tr>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td><?= $lang('inv_vat') ?></td>
            <td><?= $vat?>%</td>

        </tr>
        <?php endif; ?>
        <?php if($isDiscount): ?>
            <tr>
                <td class="border-0"></td>
                <td class="border-0"></td>
                <td class="border-0"></td>
                <td><?= $lang('inv_discount_percentage') ?></td>
                <td><?= $discount ?></td>

            </tr>
        <?php endif; ?>
        <tr>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td><?= $lang('inv_total') ?></td>
            <td><?= $amountPaid?></td>

        </tr>
        <tr>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td><?= $lang('inv_paid') ?></td>
            <td><?= $amountPaid?></td>

        </tr>
        <tr>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td class="border-0"></td>
            <td><?= $lang('inv_amount_due') ?></td>
            <td><?= $amountDue ?></td>

        </tr>
        </tbody>
    </table>
</div>
</body>
</html>