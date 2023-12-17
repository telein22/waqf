<?php

use Application\Helpers\DateHelper;
use Application\Helpers\UserHelper;
use Application\Models\User;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\Strings;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get('\Application\Models\Language');

$userM = Model::get(User::class);

?>
<div class="iq-card">
    <div class="iq-card-body">
        <div class="workshop-book-card" id="workshop_<?= $workshop['id'] ?>">
            <div class="d-flex flex-wrap mb-3">
                <div class="media-support-user-img mr-3">
                    <a href="<?= URL::full('profile/' . $workshop['user']['id']) ?>" class="">
                        <img class="rounded-circle img-fluid" src="<?= UserHelper::getAvatarUrl('fit:300,300', $workshop['user']['id']); ?>" alt="">
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
            <div class="btn-group">
                <?php if ( $userM->isLoggedIn() ): ?>
                    <?php if ($workshop['participated']) : ?>
                        <button type="button" class="btn btn-warning text-white"><i class="ri-check-line"></i> <?= $lang('already_participated'); ?></button>
                    <?php else : ?>
                        <?php if ($workshop['ordered']) : ?>
                            <a href="javascript:void(0);" class="btn btn-secondary"><?= $lang('workshop_waiting_approval') ?></a>
                        <?php else : ?>
                            <?php
                            /**
                             * @var \Application\Models\Participant
                             */
                            $partiM = Model::get('\Application\Models\Participant');
                            $count = $partiM->count($workshop['id'], Workshop::ENTITY_TYPE);
                            $count = isset($count[$workshop['id']]) ? $count[$workshop['id']] : 0;

                            if ($workshop['capacity'] <= $count) : ?>
                                <a href="javascript:void(0);" class="btn btn-danger"><?= $lang('slot_full_badge') ?></a>
                            <?php else: ?>
                                <a href="javascript:void(0);" onclick="checkout(<?= $workshop['id'] ?>, '<?= Workshop::ENTITY_TYPE ?>')" class="btn btn-primary"><?= $lang('book_now'); ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= URL::full('login'); ?>" class="btn btn-primary"><?= $lang('book_now'); ?></a>
                <?php endif; ?>
            </div>
            <div class="book-card-container clearfix">
                <h4 class="title"><?= htmlentities($workshop['name']); ?> <span class="badge badge-secondary">#<?= $workshop['id'] ?></span></h4>
                <p class="card-subtitle mb-2"><?= Strings::limit(htmlentities($workshop['desc']), 300) ?></p>
                <?php $platformFeesA = $workshop['price'] * $platform_fees / 100 ?>
                <?php $platformFeesA = number_format(round($platformFeesA, 2), 2); ?>
                <?php $priceWithPlatformFees = $workshop['price'] + $platformFeesA ?>
                <ul class="clearfix">
                    <li><i class="ri-calendar-2-line"></i> <?= DateHelper::butify(strtotime($workshop['date'])); ?></li>
                    <li><i class="ri-time-line"></i> 
                    <span class="text-left"><?= $lang('this_minutes', ['minute' => $workshop['duration']]) ?></span>
                    </li>
                    <li><i class="ri-price-tag-3-line"></i> <?= $lang('c_price', ['p' => number_format($priceWithPlatformFees, 2)]); ?></li>
                    <li><i class="ri-group-line"></i> <?= $workshop['participant_count'] ?>/<?= $workshop['capacity'] ?></li>
                    <li><i class="ri-group-line"></i> <?= $lang($workshop['status']); ?></li>
                </ul>
                <?php if (!empty($workshop['charity'])) : ?>
                    <?php
                    $charities = [];
                    foreach ($workshop['charity'] as $charity) {
                        $charities[] = '<img class="rounded" src="' . URL::media('Application/Uploads/' . $charity['img'], 'fit:32,32') . '"> <strong>' . htmlentities($charity[$lang->current() . '_name']) . '</strong>';
                    }
                    ?>

<!--                    <div class="alert alert-danger" role="alert">-->
<!--                        <p class="mb-0">-->

<!--                        </p>-->
<!--                    </div>-->
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>