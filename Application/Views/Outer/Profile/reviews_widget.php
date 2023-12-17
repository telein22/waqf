<?php

use Application\Helpers\UserHelper;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');
?>
<div class="iq-card">
    <div class="iq-card-body">
        <?php if( $reviews['avg'] != 0 ) : ?>
            <ul class="profile-img-gallary justify-content-center  d-flex flex-wrap p-0 m-0">
                <div class="profile-review-wrapper d-flex">
                    <div class="profile-star-wrapper d-block ">
                        <ul class="inactive">
                            <li><i class="ri-star-line"></i></li>
                            <li><i class="ri-star-line"></i></li>
                            <li><i class="ri-star-line"></i></li>
                            <li><i class="ri-star-line"></i></li>
                            <li><i class="ri-star-line"></i></li>
                        </ul>
                        <ul class="active" style="width: <?= $reviews['percent'] ?>%;">
                            <li><i class="ri-star-fill"></i></li>
                            <li><i class="ri-star-fill"></i></li>
                            <li><i class="ri-star-fill"></i></li>
                            <li><i class="ri-star-fill"></i></li>
                            <li><i class="ri-star-fill"></i></li>
                        </ul>
                    </div>
                </div>
            </ul>
            <div class="text-center">
                <?= $lang('review_profile_text', array(
                    'avg' => round($reviews['avg'], 1),
                    'total' => $reviews['count']
                )) ?>
            </div>
        <?php else: ?>
            <div class="text-center"><?= $lang('not_yet_rated') ?></div>
        <?php endif; ?>
    </div>
</div>