<?php

use Application\Helpers\DateHelper;
use Application\Helpers\UserHelper;
use Application\Models\User;
use System\Core\Model;

$lang = Model::get('\Application\Models\Language');
?>
<div class="message-details mb-3">
    <img src="<?= UserHelper::getAvatarUrl('fit:300,300', $item['owner']['id']); ?>" class="img-fluid" />
    <div class="message-text">
        <h5><?= $lang('book_message_for', ['name' => htmlentities($item['owner']['name']) ]); ?></h5>        
    </div>    
</div>
<div class="alert alert-primary">
    <strong><?= $lang('message') ?>:</strong><br />
    <?php  echo htmlentities($item['first_message']); ?>
</div>


<div class="alert alert-danger">
    <?= $lang('messaging_warning'); ?>
</div>


<define header_css>
    <style>
        .message-details {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
        }

        .message-details img {
            border-radius: 50%;
            width: 40px;
            margin-right: 15px;
        }

        .message-details .message-text {
            /* flex: 1; */
        }

    </style>
</define>