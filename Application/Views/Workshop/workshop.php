<?php

use Application\Helpers\DateHelper;
use Application\Helpers\ServiceHelper;
use Application\Helpers\UserHelper;
use Application\Helpers\WorkshopHelper;
use Application\Hooks\Service;
use Application\Models\Conversation;
use Application\Models\Invite;
use Application\Models\User;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');

/**
 * @var User
 */
$userM = Model::get(User::class);

?>
<?php if ($workshop['invite'] && $workshop['invite']['id'] == $user['id']) : ?>
    <span class="badge badge-secondary">Invited</span>
<?php endif; ?>
<?php
$isExpired = WorkshopHelper::isExpired($workshop['date'], $workshop['duration']);

$started = false;
$started = $workshop['status'] == Workshop::STATUS_CURRENT;

$showExpired = false;
if ($workshop['status'] === Workshop::STATUS_COMPLETED || $workshop['status'] === Workshop::STATUS_CANCELED) {
    $showExpired = false;
} else if ($started) {
    $showExpired = false;
} else if ($isExpired) {
    $showExpired = true;
}

?>
<div class="workshop-book-card" id="workshop_<?= $workshop['id'] ?>">
    <?php if ($workshop['owner']) : ?>
        <div class="btn-group">
            <?php if ($showExpired) : ?>
                <a href="#" class="badge badge-danger" data-container="body" data-toggle="popover" data-placement="top" data-content="<?= $lang('service_expire_reason') ?>"><?= $lang('expired') ?></a>
            <?php else : ?>
                <?php
                    $showStart = !$started && $workshop['status'] === Workshop::STATUS_NOT_STARTED;
                    $showCanceled = $workshop['status'] === Workshop::STATUS_CANCELED;
                    $showMarkCompleted = $started;
                    $showCompleted = $workshop['status'] === Workshop::STATUS_COMPLETED;
                ?>
                <div>
                    <?php if (!$workshop['orderedBefore']) : ?>
                        <!-- <button type="button" class="cancel-btn btn btn-info <?= !$showStart ? 'd-none' : '' ?>"><?= $lang('cancel'); ?></button> -->
                    <?php endif; ?>
                    <button type="button" class="start-btn btn btn-primary <?= !$showStart ? 'd-none' : '' ?>"><?= $lang('start'); ?></button>

                    <?php if (!$workshop['orderedBefore']) : ?>
                        <button type="button" class="delete-btn btn btn-danger workshop-delete-btn <?= !$showStart ? 'd-none' : '' ?>"><?= $lang('delete'); ?></button>
                    <?php endif; ?>
                </div>
                <button type="button" class="canceled-btn btn btn-danger <?= !$showCanceled ? 'd-none' : '' ?>"><?= $lang('canceled'); ?></button>
                <button type="button" class="mark-completed-btn btn btn-primary mark-complete-btn <?= !$showMarkCompleted ? 'd-none' : '' ?>"><?= $lang('mark_complete'); ?></button>
                <a href="javascript:void(0)" class="btn btn-primary advisor-join-btn ml-2 <?= !$showMarkCompleted ? 'd-none' : '' ?>"><?= $lang('join') ?></a>
                <button type="button" class="completed-btn btn btn-danger workshop-complete-status <?= !$showCompleted ? 'd-none' : '' ?>"><?= $lang('completed'); ?></button>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <div class="pull-right">
            <?php if ( $showExpired ): ?>
                <a href="#" class="badge badge-danger" data-container="body" data-toggle="popover" data-placement="top" data-content="<?= $lang('service_expire_reason') ?>"><?= $lang('expired') ?></a>
            <?php elseif ( $workshop['status'] !== Workshop::STATUS_COMPLETED &&  $workshop['status'] !== Workshop::STATUS_CANCELED ) : ?>
                <a href="javascript:void(0)" class="btn btn-primary join-btn"><?= $lang('join') ?></a>
            <?php else : ?>
                <?php if ($workshop['status'] === Workshop::STATUS_COMPLETED) : ?>
                    <a href="<?= URL::full('review/' . $workshop['id'] . '/' . Workshop::ENTITY_TYPE) ?>">+ <?= $lang('rate_service') ?></a>
                <?php endif; ?>
                <button class="btn btn-danger"><?= $lang($workshop['status']) ?></button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
        <div class="book-card-container">
            <div class="d-flex flex-wrap mb-3">
                <div class="media-support-user-img mr-3">
                    <a href="<?= URL::full('profile/' . $workshop['user']['id']) ?>" class="">
                        <img class="rounded-circle img-fluid h-40" src="<?= UserHelper::getAvatarUrl('fit:300,300', $workshop['user']['id']); ?>" alt="">
                    </a>
                </div>
                <div class="media-support-info mt-2">
                    <h5 class="mb-0 d-inline-block"><a href="<?= URL::full('profile/' . $workshop['user']['id']) ?>" class="">
                            <?= htmlentities($workshop['user']['name']) ?>
                            <?php if ($workshop['user']['account_verified']) echo '<i class="fas fa-check-circle color-primary"></i>' ?>
                        </a></h5>
                    <p class="mb-0 d-inline-block">@<?= htmlentities($workshop['user']['username']) ?></p>
                </div>
            </div>
            <h4 class="title"><?= htmlentities($workshop['name']); ?></h4>
            <p><?= htmlentities($workshop['desc']); ?></p>
            <?php $platformFeesA = $workshop['price'] * $platform_fees / 100 ?>
            <?php $platformFeesA = number_format(round($platformFeesA, 2), 2); ?>
            <?php $priceWithPlatformFees = $workshop['price'] + $platformFeesA ?>

            <ul class="clearfix mb-0">
                <li><i class="ri-calendar-2-line"></i> <?= DateHelper::butify(strtotime($workshop['date'])); ?></li>
                <li><i class="ri-time-line"></i> <?= $lang('this_minutes', ['minute' => $workshop['duration']]) ?></li>
                <li><i class="ri-price-tag-3-line"></i> <?= $lang('c_price', ['p' => number_format($priceWithPlatformFees, 2)]); ?></li>
                <li style="cursor: pointer" onclick="<?= $workshop['owner'] ? 'showModal(' . $workshop['id'] . ')' : ''; ?>"><i class="ri-group-line"></i> <?= $workshop['participant_count'] ?>/<?= $workshop['capacity'] ?></li>
                <li><i class="ri-group-line"></i> <?= $lang($workshop['status']); ?></li>
                <?php if ( $workshop['owner'] ): ?>
                    <li><i class="ri-information-line"></i> <?= $lang('ref_id', [ 'id' => $workshop['id'] ]); ?></li>
                <?php else: ?>
                    <li><i class="ri-information-line"></i> <?= $lang('ref_id', [ 'id' => ServiceHelper::generateRef($userM->getId(), $workshop['id'], Workshop::ENTITY_TYPE) ]); ?></li>
                <?php endif; ?>
            </ul>            
            <?php if (!empty($workshop['charity'])) : ?>
                <?php
                $charities = [];
                foreach ($workshop['charity'] as $charity) {
                    $charities[] = '<img class="rounded" src="' . URL::media('Application/Uploads/' . $charity['img'], 'fit:32,32') . '"> <strong>' . htmlentities($charity[$lang->current() . '_name']) . '</strong>';
                }
                ?>

<!--                <div class="alert alert-danger" role="alert">-->
<!--                    <p class="mb-0">-->

<!--                    </p>-->
<!--                </div>-->
            <?php endif; ?>

        </div>
</div>
<!-- <div class="workshop-card" id="workshop_<?= $workshop['id'] ?>">
    <h5 class="title mb-2"><?= htmlentities($workshop['name']); ?></h5>
    <?php if ($workshop['invite'] && $workshop['invite']['id'] == $user['id']) : ?>
        <span class="badge badge-secondary">Invited</span>
    <?php endif; ?>
    <?php
    $isExpired = WorkshopHelper::isExpired($workshop['date'], $workshop['duration']);

    $started = false;
    $started = $workshop['status'] == Workshop::STATUS_CURRENT;

    $showExpired = false;
    if ($workshop['status'] === Workshop::STATUS_COMPLETED || $workshop['status'] === Workshop::STATUS_CANCELED) {
        $showExpired = false;
    } else if ($started) {
        $showExpired = false;
    } else if ($isExpired) {
        $showExpired = true;
    }

    ?>

    <ul class="mt-2">
        <li><i class="ri-calendar-2-line"></i> <?= DateHelper::butify(strtotime($workshop['date'])); ?></li>
        <li><i class="ri-time-line"></i> <?= $lang('this_minutes', ['minute' => $workshop['duration']]) ?></li>
        <li><i class="ri-price-tag-3-line"></i> <?= $workshop['price'] ?> SR</li>
        <?php if ($workshop['owner']) : ?>
            <li><i class="ri-group-line"></i> <a href="#" onclick="showModal(<?= $workshop['id']; ?>)"><?= $workshop['participant_count'] ?>/<?= $workshop['capacity'] ?></a></li>
        <?php else : ?>
            <li><i class="ri-group-line"></i> <?= $workshop['participant_count'] ?>/<?= $workshop['capacity'] ?></li>
        <?php endif; ?>
        <?php if (!empty($workshop['charity'])) : ?>
            <li><i class="ri-group-line"></i> <?= $lang('for_charity'); ?></li>
        <?php else : ?>
            <li><i class="ri-group-line"></i> <?= $lang('not_for_charity'); ?></li>
        <?php endif; ?>
    </ul>

    <?php if ($workshop['owner']) : ?>
        <?php if ($showExpired) : ?>
            <a href="#" class="badge badge-danger" data-container="body" data-toggle="popover" data-placement="top" data-content="<?= $lang('service_expire_reason') ?>"><?= $lang('expired') ?></a>
        <?php else : ?>
            <?php
            $showStart = !$started && $workshop['status'] === Workshop::STATUS_NOT_STARTED;
            $showCanceled = $workshop['status'] === Workshop::STATUS_CANCELED;
            $showMarkCompleted = $started;
            $showCompleted = $workshop['status'] === Workshop::STATUS_COMPLETED;
            ?>
            <div class="btn-group">
                <button type="button" class="cancel-btn btn btn-info <?= !$showStart ? 'd-none' : '' ?>"><?= $lang('cancel'); ?></button>
                <button type="button" class="start-btn btn btn-primary <?= !$showStart ? 'd-none' : '' ?>"><?= $lang('start'); ?></button>
            </div>
            <button type="button" class="canceled-btn btn btn-danger <?= !$showCanceled ? 'd-none' : '' ?>"><?= $lang('canceled'); ?></button>
            <button type="button" class="mark-completed-btn btn btn-primary <?= !$showMarkCompleted ? 'd-none' : '' ?>"><?= $lang('mark_complete'); ?></button>
            <button type="button" class="completed-btn btn btn-danger <?= !$showCompleted ? 'd-none' : '' ?>"><?= $lang('completed'); ?></button>
        <?php endif; ?>
    <?php else : ?>
        <?php if (!$isExpired && $workshop['status'] !== Workshop::STATUS_COMPLETED &&  $workshop['status'] !== Workshop::STATUS_CANCELED) : ?>
            <a href="javascript:void(0)" class="btn btn-primary join-btn"><?= $lang('join') ?></a>
        <?php else : ?>
            <?php if ($workshop['status'] === Workshop::STATUS_COMPLETED) : ?>
                <a href="<?= URL::full('review/' . $workshop['id'] . '/' . Workshop::ENTITY_TYPE) ?>">+ <?= $lang('rate_service') ?></a>
            <?php endif; ?>
            <button class="btn btn-danger"><?= $lang($workshop['status']) ?></button>
        <?php endif; ?>
    <?php endif; ?>
</div> -->
<define header_css>
    <style>
        .workshop-list .col-md-12 {
            border-bottom: 1px solid var(--iq-border-light);
        }

        .workshop-list .col-md-12:last-child {
            border-bottom: none;
        }
    </style>
</define>
<define footer_js>
    <script>
        new Workshop(<?= $workshop['id'] ?>);
    </script>
</define>