<?php

/**
 * @var \Application\Models\Language
 */

use Application\Helpers\DateHelper;
use Application\Helpers\WorkshopHelper;
use Application\Models\Order as ModelsOrder;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');
?>
<?php if ( $workshop == 'deleted' ): ?>
    <div class="card">
        <hr>
        <div class="card-body">
            <p class="text-danger m-0"><?= $lang('feed_workshop_is_deleted') ?></p>
        </div>
    </div>
<?php return; ?>
<?php endif; ?>
<div class="card feed-workshop">
    <hr>
    <div class="card-body">

        <?php

        // Check mandatory ideas for workshops.
        $isExpired = WorkshopHelper::isExpired($workshop['date'], $workshop['duration']);

        $started = false;
        $started = $workshop['status'] == Workshop::STATUS_CURRENT;

        ?>
        <?php if (!$isOwner) : ?>

            <?php if ($workshop['status'] === Workshop::STATUS_COMPLETED || $workshop['status'] === Workshop::STATUS_CANCELED ) : ?>
                <?php if( $workshop['status'] == Workshop::STATUS_COMPLETED ) : ?>
                    <button class="d-md-block btn btn-danger workshop-completed-status pull-right"><?= $lang($workshop['status']) ?></button>
                <?php else: ?>
                    <button class="d-md-block btn btn-danger pull-right"><?= $lang($workshop['status']) ?></button>
                <?php endif; ?>
            <?php elseif ($isExpired) : ?>
                <a href="#" class="badge badge-danger workshop-expired-status pull-right"><?= $lang('expired') ?></a>
            <?php elseif ($workshop['status'] === Workshop::STATUS_NOT_STARTED || $started) : ?>

                <?php $order = $workshop['order_details']; ?>

                <?php
                    /**
                     * @var \Application\Models\Participant
                     */
                    $partiM = Model::get('\Application\Models\Participant');
                    $count = $partiM->count($workshop['id'], Workshop::ENTITY_TYPE);
                    $count = isset($count[$workshop['id']]) ? $count[$workshop['id']] : 0;

                    if ($workshop['capacity'] <= $count) : ?>
                        <a href="#" class="badge badge-danger pull-right"><?= $lang('slot_full_badge') ?></a>
                    <?php else: ?>
                        <!-- THEN WE NEED TO CHECK IF THE USER ORDERED -->
                        <?php if (!$order) : ?>
                            <?php $platformFeesA = $workshop['price'] * $platform_fees / 100 ?>
                            <?php $platformFeesA = number_format(round($platformFeesA, 2), 2); ?>
                            <?php $priceWithPlatformFees = $workshop['price'] + $platformFeesA ?>
                            <a href="javascript:void(0);" onclick="checkout(<?= $workshop['id'] ?>, '<?= Workshop::ENTITY_TYPE ?>')" class="d-md-block btn btn-primary pull-right"><?= $lang('workshop_feed_price', ['price' => number_format($priceWithPlatformFees, 2)]); ?></a>
                        <?php elseif ($order['status'] === ModelsOrder::STATUS_APPROVED) : ?>
                            <button type="button" class="d-md-block btn btn-warning pull-right text-white"><i class="ri-check-line"></i> <?= $lang('already_participated'); ?></button>
                        <?php elseif ($order['status'] === ModelsOrder::STATUS_PENDING) : ?>
                            <a href="javascript:void(0);" class="d-md-block btn btn-secondary pull-right">Watting for approval</a>
                        <?php else : ?>
                            <button class="d-md-block btn btn-danger pull-right"><?= $lang($workshop['status']) ?></button>
                        <?php endif; ?>
                    <?php endif; ?>
            <?php endif; ?>
        <?php else : ?>
            <div class="pull-right">
                <h3 class=" text-primary"><?= $lang('c_price', ['p' => $workshop['price']]); ?></h3>
                <?php if ($workshop['status'] !== Workshop::STATUS_COMPLETED && $workshop['status'] !== Workshop::STATUS_CANCELED && !$started && $isExpired) : ?>
                    <a href="#" class="badge badge-danger workshop-expired-status  pull-right"><?= $lang('expired') ?></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <h5 class="card-title"><?= htmlentities($workshop['name']); ?> <span class="badge badge-secondary">#<?= $workshop['id'] ?></span></h5>
        <p class="card-subtitle mb-2"><?= htmlentities($workshop['desc']); ?></p>
        <h6 class="card-subtitle mb-2 text-muted">
            <?= DateHelper::butify(strtotime($workshop['date'])); ?>
            <span><i class="ri-time-line"></i> <?= $lang('this_minutes', ['minute' => $workshop['duration']]); ?></span>
            <i class="ri-group-line"></i><span class="badge <?= $workshop['status'] == 'current' ? 'bg-success' : 'bg-secondary' ?>"><?= $lang($workshop['status']); ?></span>

        </h6>

        <?php if ($workshop['invite']) : ?>
            <p class="card-text"><?= $lang('workshop_feed_invited', [
                                        'profile_link' => URL::full('profile/' . $workshop['invite']['id']),
                                        'user' => $workshop['invite']['username']
                                    ]); ?></p>
        <?php endif; ?>
        <?php if (!empty($workshop['charity'])) : ?>

            <?php
            $charities = [];
            foreach ($workshop['charity'] as $charity) {
                $charities[] = '<strong>' . htmlentities($charity[$lang->current() . '_name']) . '</strong>';
            }
            ?>

<!--            <div class="alert alert-danger" role="alert">-->
<!--                <p class="mb-0">-->

<!--                </p>-->
<!--            </div>-->

        <?php endif; ?>
    </div>
</div>
<!-- <div class="card workshop-card">
                <div class="card-body">
                    <div class="card-title border-bottom"><?php // echo $lang('workshop');
                                                            ?></div>
                    <h3 class="text-white"><?php // echo htmlentities($workshop['name']);
                                            ?></h3>
                </div>
            </div> -->