<?php

use Application\Helpers\DateHelper;
use Application\Helpers\ServiceHelper;
use Application\Helpers\UserHelper;
use Application\Helpers\WorkshopHelper;
use Application\Models\Call;
use Application\Models\User;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');

/**
 * @var User
 */
$userM = Model::get(User::class);

// var_dump($call);

?>

<?php
$isExpired = WorkshopHelper::isExpired($call['date'], $call['duration']);

$started = false;
$started = $call['status'] == Call::STATUS_CURRENT;

$showExpired = false;
if ($call['status'] === Call::STATUS_COMPLETED || $call['status'] === call::STATUS_CANCELED) {
    $showExpired = false;
} else if ($started) {
    $showExpired = false;
} else if ($isExpired) {
    $showExpired = true;
}

?>

<div class="workshop-card" id="call_<?= $call['id'] ?>">
    <div class="d-flex justify-content-between flex-wrap">
        <?php if (!$call['its_mine']) : ?>
            <div class="d-flex flex-wrap mb-3">
                <div class="media-support-user-img mr-3">
                <a href="<?= URL::full('profile/' . $call['from']['id']) ?>" class="">
                    <img class="rounded-circle img-fluid h-40" src="<?= UserHelper::getAvatarUrl('fit:300,300', $call['from']['id']); ?>" alt="">
                </a>
                </div>
                <div class="media-support-info mt-2">
                    <h5 class="mb-0 d-inline-block"><a href="<?= URL::full('profile/' . $call['from']['id']) ?>" class="">
                            <?= htmlentities($call['from']['name']) ?>
                            <?php if ($call['from']['account_verified']) echo '<i class="fas fa-check-circle color-primary"></i>' ?>
                        </a></h5>
                    <p class="mb-0 d-inline-block">@<?= htmlentities($call['from']['username']) ?></p>
                </div>
            </div>
        <?php else : ?>
            <div class="d-flex flex-wrap mb-3">
                <div class="media-support-user-img mr-3">
                    <a href="<?= URL::full('profile/' . $call['for']['id']) ?>" class="">
                        <img class="rounded-circle img-fluid h-40" src="<?= UserHelper::getAvatarUrl('fit:300,300', $call['for']['id']); ?>" alt="">
                    </a>
                </div>
                <div class="media-support-info mt-2">
                    <h5 class="mb-0 d-inline-block"><a href="<?= URL::full('profile/' . $call['for']['id']) ?>" class="">
                            <?= htmlentities($call['for']['name']) ?>
                            <?php if ($call['for']['account_verified']) echo '<i class="fas fa-check-circle color-primary"></i>' ?>
                        </a></h5>
                    <p class="mb-0 d-inline-block">@<?= htmlentities($call['for']['username']) ?></p>
                </div>
            </div>
        <?php endif; ?>
        <div class="mb-4 mt-2 call-button-group">
            <?php if ($call['its_mine']) : ?>
                <?php if ($showExpired) : ?>
                    <a href="#" class="badge badge-danger" data-container="body" data-toggle="popover" data-placement="top" data-content="<?= $lang('service_expire_reason') ?>"><?= $lang('expired') ?></a>
                <?php else : ?>
                    <?php
                    $showStart = !$started && $call['status'] === call::STATUS_NOT_STARTED;
                    $showCanceled = $call['status'] === Call::STATUS_CANCELED;
                    $showMarkCompleted = $started;
                    $showCompleted = $call['status'] === Call::STATUS_COMPLETED;
                    ?>
                    <div class="btn-group">
                        <!-- <button type="button" class="cancel-btn btn btn-info <?= !$showStart ? 'd-none' : '' ?>"><?= $lang('cancel'); ?></button> -->
                        <button type="button" class="start-btn btn btn-primary <?= !$showStart ? 'd-none' : '' ?>"><?= $lang('start'); ?></button>
                    </div>
                    <button type="button" class="canceled-btn btn btn-danger <?= !$showCanceled ? 'd-none' : '' ?>"><?= $lang('canceled'); ?></button>
                    <button type="button" class="mark-completed-btn btn btn-primary <?= !$showMarkCompleted ? 'd-none' : '' ?>"><?= $lang('mark_complete'); ?></button>
                    <a href="javascript:void(0)" class="btn btn-primary advisor-join-btn <?= !$showMarkCompleted ? 'd-none' : '' ?>"><?= $lang('join') ?></a>
                    <button type="button" class="completed-btn btn btn-danger <?= !$showCompleted ? 'd-none' : '' ?>"><?= $lang('completed'); ?></button>
                <?php endif; ?>
            <?php else : ?>
                <?php if ( $showExpired ): ?>
                    <a href="#" class="badge badge-danger" data-container="body" data-toggle="popover" data-placement="top" data-content="<?= $lang('service_expire_reason') ?>"><?= $lang('expired') ?></a>
                <?php elseif ( $call['status'] !== Call::STATUS_COMPLETED &&  $call['status'] !== Call::STATUS_CANCELED) : ?>
                    <div class="btn-group">
                        <!-- <a href="javascript:void(0)" class="btn btn-info b-cancel-btn"><?= $lang('cancel') ?></a> -->
                        <a href="javascript:void(0)" class="btn btn-primary join-btn"><?= $lang('join') ?></a>
                    </div>
                    <button type="button" class="canceled-btn btn btn-danger d-none"><?= $lang('canceled'); ?></button>
                <?php else : ?>
                    <?php if ($call['status'] === Call::STATUS_COMPLETED) : ?>
                        <a href="<?= URL::full('review/' . $call['id'] . '/' . Call::ENTITY_TYPE) ?>">+ <?= $lang('rate_service') ?></a>
                    <?php endif; ?>
                    <button class="btn btn-danger"><?= $lang($call['status']) ?></button>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <ul class="call-ul">
        <li><i class="ri-calendar-2-line"></i> <?= DateHelper::butify(strtotime($call['date'])); ?></li>
        <li><i class="ri-time-line"></i> <?= $lang('this_minutes', ['minute' => $call['duration']]) ?></li>
        <li><i class="ri-price-tag-3-line"></i> <?= $lang('c_price', ['p' => $call['price']]); ?> </li>
        <li><i class="ri-group-line"></i> <?= $lang($call['status']); ?></li>
        <?php if ( $call['its_mine'] ): ?>
            <li><i class="ri-information-line"></i> <?= $lang('ref_id', [ 'id' => $call['id'] ]); ?></li>
        <?php else: ?>
            <li><i class="ri-information-line"></i> <?= $lang('ref_id', [ 'id' => ServiceHelper::generateRef($userM->getId(), $call['id'], Call::ENTITY_TYPE) ]); ?></li>
        <?php endif; ?>
        <?php if ($call['its_mine']) : ?>
            <!-- <li><a href="#" onclick="showInviteModal(<?php // echo $call['id'];
                                                            ?>, '<?php // echo Call::ENTITY_TYPE
                                                                    ?>', '<?php // echo Invite::JOIN_TYPE_FREE;
                                                                            ?>')">+ <?php // echo $lang('invite_free_users')
                                                                                    ?></a></li> -->
        <?php endif; ?>
    </ul>
    <?php if (!empty($call['charity'])) : ?>
        <?php
        $charities = [];
        foreach ($call['charity'] as $charity) {
            $charities[] = '<img class="rounded" src="' . URL::media('Application/Uploads/' . $charity['img'], 'fit:32,32') . '"> <strong>' . htmlentities($charity[$lang->current() . '_name']) . '</strong>';
        }
        ?>

<!--        <div class="alert alert-danger" role="alert">-->
<!--            <p class="mb-0">-->

<!--            </p>-->
<!--        </div>-->
    <?php endif; ?>

</div>
<define footer_js>
    <script>
        new Call(<?= $call['id'] ?>);
    </script>
</define>

<define header_css>
    <style>
        .call-ul li {
            display: inline-block;
            margin-right: 10px;
        }

        .call-ul li i {
            color: var(--iq-warning);
        }
    </style>
</define>