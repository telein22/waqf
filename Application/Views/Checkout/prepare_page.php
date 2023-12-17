<?php

use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');
?>

<div class="container">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="iq-card">
                <div class="iq-card-body">
                    <p class="text-center text-waiting"><?= $lang('checkout_wait_preparing') ?></p>
                    <a href="<?= URL::full('dashboard') ?>"><i class="ri-arrow-left-s-line"></i> <?= $lang('back_to_home') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<define footer_js>
    <script>
        setTimeout(function() {
            $.ajax({
                url: URLS.prepare_checkout,
                data: {
                    id: <?= $id; ?>,
                    type: '<?= $type; ?>'
                },
                beforeSend: function() {
                    // $modal.modal('show');        
                },
                success: function(data) {
                    if( data.info !== 'success' ) {
                        $(".text-waiting").text(data.payload);

                        return;
                    }

                    window.location.href = '<?= URL::full('checkout'); ?>'
                },
                complete: function () {
                    // $modal.modal('hide');
                }
            });
        }, 2000);
    </script>
</define>