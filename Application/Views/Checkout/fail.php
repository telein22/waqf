<?php

use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mt-5">
            <div class="iq-card">
                <div class="iq-card-body  p-5">
                    <div class="order-success-upper-image mb-5">
                        <img src="<?= URL::asset('Application\Assets\images\error-envelope.png'); ?>" />
                    </div>
                    <div class="information text-center">
                        <h3 class="mb-2"><?= $lang('order_fail'); ?></h3>
                        <p><?= $lang('order_fail_desc'); ?></p>
                        <p class="mt-5">
                            <a class="btn btn-primary" href="<?= URL::full('order/my'); ?>"><?= $lang('go_to_my_orders') ?></a>
                        </p>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<define header_css>
    <style>
        .order-success-upper-image {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .order-success-upper-image img {
            max-width: 200px;
        }
    </style>
</define>