<?php

use Application\Helpers\DateHelper;
use Application\Helpers\UserHelper;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;
use Application\Models\Notification as ModelsNotification;
use Application\Models\UserSettings;
$lang = Model::get('\Application\Models\Language');

$params = implode(', ', $notification['actionParams']);
$time = DateHelper::butify($notification['created_at']);
$lang = Model::get('\Application\Models\Language');

?>
<a href="javascript:void(0)" onclick="notification.open('<?= $notification['action_type']; ?>', <?= $params ?>)">
    <div class="notification-list-item clearfix <?php if ($notification['read'] == 0):?>unread<?php endif; ?>">
        <div class="notification-list-img-wrapper pull-left text-center">
            <?php if ( $notification['sender_id'] != 0 ): ?>
                <img class="notification-icon" src="<?= UserHelper::getAvatarUrl('fit:300,300', $notification['sender_id']) ?>" alt="...">
            <?php elseif ( isset($notification['icon']) ): ?>
                <img class="notification-icon" src="<?= URL::asset('Application/Assets/images/service-'. $notification['icon'] .'.png') ?>" alt="...">
            <?php else: ?>
                <img class="notification-icon" src="<?= URL::asset('Application/Assets/images/service-star.png') ?>" alt="...">
            <?php endif; ?>
        </div>
        <div>
            <h5>
                <?php if( $notification['sender_id'] != 0 ): ?>
                    <?= $lang('notification_' . $notification['action_type'], array('name' => htmlentities($notification['sender']['name']))); ?>
                <?php else: ?>
                    <?= $lang('notification_' . $notification['action_type']); ?>
                <?php endif; ?>
            </h5>
            <small><?= $time ?></small>
            <?php if (!empty($notification['preparedData'])) : ?>
                <div class="notification-list-content">
                    <?php $template = 'Notification/Templates/' . str_replace('.', '_', $notification['action_type']); ?>
                    <?php View::include($template, array('notification' => $notification)); ?>
                </div>
            <?php endif; ?>            

        </div>
    </div>
</a>