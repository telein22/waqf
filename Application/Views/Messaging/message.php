<?php

use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');

?>
<div class="message-container <?= $user['id'] == $message['sender_id'] ? 'self bg-primary' : ' bg-secondary ' ?>">
    <p class="m-0 name font-size-14">
        <a href="<?= URL::full('profile/' . $message['sender_id']) ?>" style="color: inherit;">
            <strong><?= $user['id'] == $message['sender_id'] ? $lang('you') : htmlentities($opponent['name']); ?></strong>
        </a>
    </p>
    <p><?php  echo htmlentities($message['message']); ?></p>
</div>