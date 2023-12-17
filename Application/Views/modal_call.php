<?php

use Application\Models\User;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');

/**
 * @var User
 */
$userM = Model::get(User::class);
?>
<div class="modal fade nav-modal" id="call-nav-modal" tabindex="-1" role="dialog" aria-labelledby="call-nav-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="call-nav-modal-label"><?= $lang('calls') ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
          <div class="list-group">
            <?php if ( $userM->canCreateWorkshop() ): ?>
            <div class="list-item">
              <a href="<?php  echo URL::full('calls/a'); ?>" class="py-3 px-3 d-block iq-bg-warning-hover">
                <h6 class="mb-0"><?= $lang('advisor'); ?></h6>
                <p class="mb-0 font-size-12"><?= $lang('advisor_desc'); ?></p>
              </a>
            </div>
            <?php endif; ?>
              <div class="list-item">
                <a href="<?php  echo URL::full('calls/b'); ?>" class="py-3 px-3  d-block iq-bg-warning-hover">
                  <h6 class="mb-0"><?= $lang('beneficiary'); ?></h6>
                  <p class="mb-0 font-size-12"><?= $lang('beneficiary_desc'); ?></p>
                </a>
              </div>
          </div>
      </div>      
    </div>
  </div>
</div>