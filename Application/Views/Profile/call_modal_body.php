<?php

use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');
?>
<?php if ( !empty($slots) ): ?>
<form method="POST" action="<?= URL::full('calls/checkout'); ?>" onsubmit="onSlotBookingSubmit(this, event);">
    <div class="container">
        <h3 class="font-size-16 mt-3 text-secondary"><?= $lang('call_following_times_are_available'); ?></h3>
        <div class="row">            
            <?php foreach ( $slots as $slot ): ?>
                <div class="col-xs-3">
                    <div class="card mr-3 mt-3 overflow-hidden">
                        <input type="radio" id="call_slot_<?= $slot['id'];  ?>" value="<?= $slot['id'] ?>" name="slot" class="d-none" />
                        <label for="call_slot_<?= $slot['id'];  ?>" class="card-body p-2 m-0 bg-danger">
                            <p class="h5 text-center text-white m-0"><?= date('g:i a', strtotime($slot['time'])); ?></p>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="col-sm-12 p-0 mt-1">
                <div class="button-group pull-right">
                    <button type="submit" class="btn btn-primary"><?= $lang('submit') ?></button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php else: ?>
<div class="container">        
        <div class="row">           
            <div class="col-sm-12">
                <p class="text-center mt-5"><?= $lang('no_data') ?></p>
            </div>
        </div>
    </div>
<?php endif; ?>