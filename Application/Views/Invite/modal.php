<?php

?>
<?php

use Application\Models\Invite;
use System\Core\Model;

$lang = Model::get('\Application\Models\Language');

?>
<div class="modal fade" id="invite-modal" tabindex="-1" role="dialog" aria-labelledby="invite-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="invite-modal-label"><?= $title; ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="invite-form">
      <div class="modal-body">
          <div class="form-group">
              <label for="invite"><?= $lang('usernames'); ?></label>
              <input type="text" name="invite" class="form-control" />
              <p><?= $lang('invite_user_desc') ?></p>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="eId" id="invite-eId-input"/>
        <input type="hidden" name="eType" id="invite-eType-input"/>
        <input type="hidden" name="type" id="invite-type-input"/>

        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= $lang('close'); ?></button>        
        <button type="submit" class="btn btn-primary"><?= $lang('submit'); ?></button>        
      </div>
      </form>
    </div>
  </div>
</div>
<define footer_js>
  <script>
      (function(_scope) {
        
        _scope.showInviteModal = _scope.showInviteModal || showInviteModal;

        var $modal = $('#invite-modal');
        var $body = $modal.find('.modal-body');
        var $form = $('#invite-form');

        function showInviteModal( id, eType, type )
        {
            $modal.modal('show');

            $form[0].reset();

            type = type === '<?= Invite::JOIN_TYPE_FREE ?>' ? type : '<?= Invite::JOIN_TYPE_NORMAL ?>';

            $('#invite-eId-input').val(id);
            $('#invite-eType-input').val(eType);
            $('#invite-type-input').val(type);

        }

        var isInviting = false;

        $form.on('submit', function(e){
          if ( isInviting ) return;

          e.preventDefault();
          
          $.ajax({
                url: URLS.invite,
                beforeSend: function() {
                  isInviting = true;
                },
                data: $(this).serialize(),
                complete: function() {                    
                    isInviting = false;
                },
                success: function( data ) {
                  if ( data.info !== 'success' ) {
                    toast('danger', '<?= $lang('error') ?>', data.payload);
                    return;
                  }

                  toast('primary', '<?= $lang('success') ?>', '<?= $lang('invite_successful') ?>');

                  // else close the modal
                  $modal.modal('hide');
                }
            });  
   
        });


      })(window)
    </script>
</define>