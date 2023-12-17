<?php
if ( !isset($notification['preparedData']['workshop']) ) return;

use System\Core\Model;
use System\Helpers\Strings;

$lang = Model::get('\Application\Models\Language');

?>
<p class="text-danger text-bold">
    <?= $lang('notification_workshop_reminder', ['name' => $notification['preparedData']['workshop']['name'] ]); ?>
</p>