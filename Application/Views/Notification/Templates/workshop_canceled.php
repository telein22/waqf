<?php
if ( !isset($notification['preparedData']['coupon']) ) return;

use System\Core\Model;
use System\Helpers\Strings;

$lang = Model::get('\Application\Models\Language');

?>
<p class="text-danger text-bold">
    <?= $lang('notification_workshop_cancel_coupon', ['coupon' => $notification['preparedData']['coupon'] ]); ?>
</p>