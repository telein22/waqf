<?php

use Application\Helpers\UserHelper;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');

?>
<div class="iq-card">
    <div class="iq-card-header d-flex justify-content-between">
        <div class="iq-header-title">
            <h4 class="card-title"><?= $lang('following') ?></h4>
        </div>
        <?php if (!empty($followings)) : ?>
            <div class="iq-card-header-toolbar d-flex align-items-center">
                <p class="m-0"><a href="#" onclick="$('.following-tab-nav').trigger('click');return false;"><?= $lang('view_all') ?></a></p>
            </div>
        <?php endif; ?>
    </div>
    <div class="iq-card-body">
        <ul class="profile-img-gallary d-flex flex-wrap p-0 m-0">
            <?php if (empty($followings)) : ?>
                <li class="col-md-12 col-12 text-center">
                    <?= $lang('no_following'); ?>
                </li>
            <?php else : ?>
                <?php $i = 0; ?>
                <?php foreach ($followings as $follow) : ?>
                    <li class="col-md-4 col-6 pl-2 pr-0 pb-3">
                        <a href="<?= URL::full('profile/' . $follow['follow']['id'] ); ?>">
                            <img src="<?= UserHelper::getAvatarUrl('fit:300,300', $follow['follow']['id']); ?>" alt="gallary-image" class="img-fluid" />
                            <h6 class="mt-2"><?= htmlentities($follow['follow']['name']); ?></h6>
                        </a>
                    </li>
                    <?php $i++; ?>
                    <?php if ( $i == 9  ) break; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>