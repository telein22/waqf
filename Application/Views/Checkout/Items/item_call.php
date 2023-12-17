<?php

use Application\Helpers\DateHelper;
use Application\Helpers\UserHelper;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');
?>
<div class="call-details">
    <img src="<?= UserHelper::getAvatarUrl('fit:300,300', $item['from']['id']); ?>" class="img-fluid" />
    <div class="call-text">
        <h4><?= htmlentities($item['from']['name']); ?></h4>
        <p class="m-0"><i class="ri-calendar-event-line"></i> <?= DateHelper::butify(strtotime($item['date'])); ?></p>
    </div>
</div>
<?php if (!empty($item['charity'])) : ?>
    <?php
        $charities = [];
        foreach ($item['charity'] as $charity) {
            $charities[] = '<img class="rounded" src="' . URL::media('Application/Uploads/' . $charity['img'], 'fit:32,32') . '"> <strong>' . htmlentities($charity[$lang->current() . '_name']) . '</strong>';
        }
    ?>
<!--    <p class="font-size-12 charity-p m-0 mt-2">-->

<!--    </p>-->
<?php endif; ?>

<define header_css>
    <style>
        .call-details {
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
        }

        .call-details h4 {
            color: var(--iq-white);
        }

        .call-details img {
            border-radius: 50%;
            width: 40px;
            margin-right: 15px;
        }

        .call-details .call-text {
            /* flex: 1; */
        }

        .charity-p img {
            width: 20px;
        }

        .checkout-card-call {
            background-color: var(--iq-danger);
            color: var(--iq-white);
        }
    </style>
</define>