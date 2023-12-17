<?php

use Application\Helpers\DateHelper;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');

?>
<div class="workshop-book-card" id="workshop_<?= $workshop['id'] ?>" >
    <div class="btn-group">
        <?php if ( $workshop['participated']) : ?> 
            <button type="button" class="btn btn-warning text-white"><i class="ri-check-line"></i> Already participated</button>
        <?php else: ?>            
            <?php if ( $workshop['ordered'] ): ?>
                <a href="javascript:void(0);" onclick="checkout(<?= $workshop['id'] ?>, '<?= Workshop::ENTITY_TYPE ?>')" class="btn btn-secondary">Watting for approval</a>
            <?php else: ?>
                <a href="javascript:void(0);" onclick="checkout(<?= $workshop['id'] ?>, '<?= Workshop::ENTITY_TYPE ?>')" class="btn btn-primary">Book Now</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="book-card-container">
        <h4 class="title"><?= htmlentities($workshop['name']); ?></h4>
        <ul class="clearfix">
            <li><i class="ri-calendar-2-line"></i> <?= DateHelper::butify(strtotime($workshop['date'])); ?></li>
            <li><i class="ri-time-line"></i> <?php  echo $lang('this_minutes', [ 'minute' => $workshop['duration'] ]) ?></li>
            <li><i class="ri-price-tag-3-line"></i> <?= $workshop['price'] ?> SR</li>
            <li><i class="ri-group-line"></i> <?= $workshop['participant_count'] ?>/<?= $workshop['capacity'] ?></li>
            <li><i class="ri-group-line"></i> <?= $lang($workshop['status']); ?></li>
        </ul>
        <?php if ( !empty($workshop['charity']) ): ?>
            <?php
            $charities = [];
            foreach ($workshop['charity'] as $charity) {
                $charities[] = '<img class="rounded" src="' . URL::media('Application/Uploads/' . $charity['img'], 'fit:32,32') . '"> <strong>' . htmlentities($charity[$lang->current() . '_name']) . '</strong>';
            }
            ?>

<!--            <div class="alert alert-danger" role="alert">-->
<!--                <p class="mb-0">-->

<!--                </p>-->
<!--            </div>-->
        <?php endif; ?>
    </div>    
</div>