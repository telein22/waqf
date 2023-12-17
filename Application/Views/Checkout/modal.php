<?php

use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');
?>

<div class="modal fade" id="checkout-modal" tabindex="-1" role="dialog" aria-labelledby="checkout-modal-label" aria-hidden="true" >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <?= $lang('checkout_wait_preparing'); ?>
      </div>     
    </div>
  </div>
</div>
<define footer_js>
    <script>
        function checkout( id, type )
        {
            var $modal = $('#checkout-modal');
            $modal.modal({backdrop: 'static', keyboard: false});
            

            $.ajax({
                url: URLS.prepare_checkout,
                data: {
                    id: id,
                    type: type
                },
                beforeSend: function() {
                    $modal.modal('show');        
                },
                success: function(data) {
                  if( data.info !== 'success' ) {
                    toast('danger','<?= $lang('error') ?>' ,data.payload);
                    return;
                  }
                    window.location.href = '<?= URL::full('checkout'); ?>'
                },
                complete: function () {
                    $modal.modal('hide');
                }
            });
        }
    </script>
</define>