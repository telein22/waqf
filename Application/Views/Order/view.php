<?php

use Application\Helpers\DateHelper;
use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get(Language::class);
?>

<div class="container">

    <div class="row mt-5 justify-content-center">
        <div class="col-md-8">
            <div class="iq-card position-relative inner-page-bg bg-primary" style="height: 150px;">
                <div class="inner-page-title">
                    <h3 class="text-white"><?= $lang('order'); ?></h3>
                    <p class="text-white">Order #<?= $orderInfo['id'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="iq-card">
                <div class="iq-card-body">
                    <div class="order-container">
                        <span class="c_price pull-right font-size-32 text-primary"><?= $lang('c_price', ['p' => $orderInfo['amount']]) ?></span>
                        <div>
                            <span class="text-secondary"><?= $lang('ordered_on_view', ['date' => DateHelper::butify($orderInfo['created_at'])]) ?></span>
                            <h4 class="font-size-16"><?= $orderInfo['entity']['name'] ?></h4>
                            <span class="badge badge-danger"><?= $lang($orderInfo['entity_type']); ?></span>
                            <span class="badge badge-primary"><?= $lang($orderInfo['status']); ?></span><br />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title font-size-18"><?= $lang('payment_details'); ?></h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="payment-container">
                        <?php foreach ( $payments as $payment ): ?>
                            <div class="payment-wrapper clearfix">
                                <h4 class="font-size-16">
                                    <?= $lang(
                                        'payment_initiated_on_view',
                                        [
                                            'date' => DateHelper::butify($payment['created_at']),
                                            'price' => $payment['paid']
                                        ]
                                    ) ?>
                                </h4>
                                <span class="badge badge-danger"><?= $lang($payment['status']); ?></span>
                                <span class="text-secondary pull-right"><?= $lang('transaction_id') ?> <strong>#<?= $payment['txn_token'] ?></strong></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>