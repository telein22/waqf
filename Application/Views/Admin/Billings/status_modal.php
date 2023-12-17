<?php

use Application\Models\Language;
use Application\Models\Order;
use System\Core\Model;

$lang = Model::get(Language::class);
?>
<form method="POST" action="#" class="status-form">
    <div class="col-md-12">
        <div class="form-group">
            <label for="cancel" class="font-weight-normal">
                <input type="radio" value="<?= Order::STATUS_CANCELED ?>" name="status" id="cancel" /> <?= $lang('cancel') ?>
            </label>&nbsp;&nbsp;
            <label for="hold" class="font-weight-normal">
                <input type="radio" value="<?= Order::STATUS_HOLD ?>" name="status" id="hold" /> <?= $lang('hold') ?>
            </label>      
        </div>
        <input type="hidden" name="order_id" value="<?= $order['id']; ?>" />
        <p class="text-danger">
            <?= $lang('order_status_change_warning') ?>
        </p>
    </div>
    <div class="col-md-6">
        <button type="submit" class="btn btn-primary"><?= $lang('submit') ?></button>
    </div>
</form>
<script>
    var isBusy = false;
    $('.status-form').on('submit', function(e){
        e.preventDefault();

        if ( isBusy ) return;
        
        var isSelected = false;
        $(this).find('input[type=radio]').each(function(i, v){
            if ( !isSelected ) {
                isSelected = v.checked;
            }
        });

        if ( !isSelected ) return;

        var $form = $(this);

        cConfirm("<?= $lang('are_you_sure') ?>", function() {
            $("#show-status").modal('hide');
            var dialog = bootbox.dialog({
                message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> <?= $lang('please_wait') ?></p>',
                closeButton: false,
                centerVertical: true,
            });

            $.ajax({
                url: URLS.update_order_status,
                data: $form.serialize(),
                type: 'POST',
                accepts: 'JSON',
                dataType: 'JSON',
                beforeSend: function() {
                    isBusy = true;
                },
                success: function(data) {
                    if ( data.info !== 'success' ) return;
                    
                    window.location.reload();
                },
                complete: function () {
                    isBusy = false;
                    // do something in the background
                    dialog.modal('hide');
                }
            });
        });
    });
</script>