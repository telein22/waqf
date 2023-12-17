<?php

use Application\Helpers\DateHelper;
use System\Core\Model;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

?>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="iq-card">
            <div class="iq-card-body">
                <div class="order-container">
                    <div>
                        <span class="text-secondary"><?= $lang('ordered_on_view', ['date' => DateHelper::butify($order['created_at'])]) ?></span>
                        <h4 class="font-size-16"><?= $lang('personal_improvement') ?></h4>
                        <span class="badge badge-danger"><?= $lang($order['entity_type']); ?></span>
                        <span class="badge badge-primary"><?= $lang($order['status']); ?></span><br />
                    </div>
                    <span style="font-size: 30px;" class="c_price pull-right font-size-32 text-primary"><?= $lang('c_price_earning', ['p' => $order['amount']]) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h2 class="card-title payment-details-header"><?= $lang('payment_details'); ?></h2>
                </div>
            </div>
            <div class="iq-card-body">
                <div class="payment-container">
                    <?php foreach ($payments as $payment) : ?>
                        <div class="payment-wrapper">
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
<hr>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
                <div class="iq-header-title">
                    <h2 class="card-title payment-details-header"><?= $lang('bank_details'); ?></h2>
                </div>
            </div>
            <div class="iq-card-body">
                <div class="payment-container">
                    <?php if ( !empty($bankDetails[0]) ): ?><?= $lang('bank_details_name', [ 'name' => htmlentities($bankDetails[0])]); ?><?php endif; ?><br />
                        <?php if ( !empty($bankDetails[1]) ): ?><?= $lang('bank_details_account_number', [ 'number' => htmlentities($bankDetails[1])]); ?><?php endif; ?><br />
                            <?php if ( !empty($bankDetails[2]) ): ?><?= $lang('bank_details_bic_code', [ 'bic' => htmlentities($bankDetails[2])]); ?><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>