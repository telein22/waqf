<?php

use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');
?>
<div class="modal fade" id="apply-coupon-modal" tabindex="-1" role="dialog" aria-labelledby="apply-coupon-modal-label" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="apply-coupon-modal-label"><?= $lang('apply_coupon_btn') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="#" id="coupon-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="coupon"><?= $lang('coupon'); ?></label>
                        <input type="text" class="form-control" id="coupon" name="coupon" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?= $lang('submit'); ?></button>
                    <button type="button" id="btn-close" class="btn btn-secondary" data-dismiss="modal"><?= $lang('close'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<define footer_js>
    <script>

        $('#coupon-form').on('submit', function(e) {            
            e.preventDefault();

            var $form = $(this);
            var $btn = $form.find('button[type=submit]');
         
            // else  run ajax.
            $.ajax({
                url: URLS.checkout_apply_coupon,
                data: $(this).serialize(),
                beforeSend: function() {
                },
                success: function( data ) {                    
                    if ( data.info !== 'success' ) {
                        toast('danger', '<?= $lang('error') ?>', data.payload);
                        return;
                    }

                    window.location.reload();

                },
                complete: function() {

                }
            });

        });

        $('#btn-close').on('click', function () {
            $('#coupon').val("");
        })

    </script>
</define>