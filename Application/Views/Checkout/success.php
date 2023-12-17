<?php

use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');
?>
<div class="container">
    <img  id="loader" class="d-none" src="<?= URL::asset("Application/Assets/images/page-img/ajax-loader.gif"); ?>" alt="loader" style="width: 75px; height: 75px; margin: 0 auto; position: fixed; top:50%; left: 50%; z-index:999999" >
    <div class="row justify-content-center">
        <div class="col-md-8 mt-5">
            <div class="iq-card">
                <div class="iq-card-body  p-5">
                    <div class="order-success-upper-image mb-5">
                        <img src="<?= URL::asset('Application\Assets\images\open-envelope.png'); ?>" />
                    </div>
                    <div class="information text-center" id="checkout-waiting">
                        <?= $lang('order_waiting_desc'); ?>
                    </div>
                    <div class="information text-center d-none" id="success-confirmation">
                        <h3 class="mb-2"><?= $lang('order_success'); ?></h3>
                        <p><?= $lang('order_success_desc'); ?></p>
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

<define footer_js>
    <script>
        $('#loader').removeClass('d-none');
         $.ajax({
            url: URLS.accept_order_request,
            data: {
                id: <?= $order['id'] ?>
            },
            beforeSend: function() {
                isRequestBusy = true;

            },
            success: function(data) {
                if (data.info !== 'success') {
                    toast('danger', '<?= $lang('error') ?>', data.payload);
                    return;
                }

                $('#loader').addClass('d-none');
                $('#success-confirmation').removeClass('d-none');
                $('#checkout-waiting').addClass('d-none');
            },
            complete: function() {
                dialog.modal('hide');
                isRequestBusy = false;
            }
        });
    </script>
</define>