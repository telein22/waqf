<?php

use Application\Helpers\DateHelper;
use Application\Models\Language;
use Application\Models\Order;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get(Language::class);
?>
<div class="row">
    <div class="col-md-8">
        <div class="order-container">
            <div class="order-id-container mt-2 text-secondary">
                <?= $lang('your_order_id', ['id' => $order['id']]); ?>
            </div>
            <span class="text-secondary"><?= $lang('ordered_on', ['date' => DateHelper::butify($order['created_at']), 'price' => $order['amount']]) ?></span>
            <h4 class="font-size-16"><?= htmlentities( $order['entity']['name'] ) ?></h4>
            <span class="badge badge-danger"><?= $lang($order['entity_type']); ?></span>
            <span class="badge badge-primary"><?= $lang($order['status']); ?></span><br />
        </div>
    </div>
    <div class="col-md-4">
        <div class="order-right clearfix">
            <div class="pull-right  text-right">
                <?php if ( $order['can_user_cancel'] ): ?>
                    <!-- <a class="btn btn-info" href="<?php // echo URL::full('order/cancel/' . $order['id']) ?>"><?php // echo $lang('cancel') ?></a> -->
                <?php endif; ?>
                <!-- <a class="btn btn-info" href="<?= URL::full('order/invoice/' . $order['id']) ?>"><?= $lang('invoice') ?></a> -->
                <a class="btn btn-primary" href="<?= URL::full('order/view/' . $order['id']) ?>"><?= $lang('view') ?></a>
            </div>
        </div>
    </div>
</div>
<hr>