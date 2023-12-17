<?php

use Application\Helpers\UserHelper;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

?>
<div class="request-item clearfix" id="request-item-<?= $item['id'] ?>">
    <div class="request-item-left">
        <img src="<?= UserHelper::getAvatarUrl('fit:300,300', $item['user']['id']) ?>" alt="Workshop" class="rounded-circle img-fluid" />
    </div>
    <div class="request-item-right">
        <h5><?= htmlentities($item['user']['name']); ?>
            <?php if ($item['user']['account_verified']) echo '<i class="fas fa-check-circle color-primary"></i>' ?>
        </h5>
        <p class="m-0"><?= $lang('order_request_for_' . $item['entity_type'], ['name' => htmlentities($item['entity']['name'])]) ?></p>
    </div>
    <div class="request-item-button">
        <button type="button" class="btn btn-primary" onclick="requestAction('accept', <?= $item['id']; ?>)"><i class="ri-check-line"></i> <?= $lang('accept'); ?></button>
        <button type="button" class="btn btn-secondary" onclick="requestAction('decline', <?= $item['id']; ?>)"><i class="ri-close-line"></i> <?= $lang('decline'); ?></button>
        <p class="accepted-status d-none"><i class="ri-check-line"></i> <?= $lang('accepted'); ?></p>
        <p class="declined-status d-none"><i class="ri-close-line"></i> <?= $lang('declined'); ?></p>
    </div>
</div>