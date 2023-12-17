<?php

use Application\Models\User;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

/**
 * @var User
 */
$userM = Model::get(User::class);

?>
<div class="container">
    <div class="row mt-5 justify-content-center">
        <div class="col-lg-8 row m-0 p-0">
            <?php if ( $userM->isLoggedIn() ): ?>
            <?php View::include('Feed/feed', [
                'userInfo' => $userInfo,
                'feed' => $feed,
                'iFrame' => $iFrame,
                'platform_fees' => $platform_fees
            ]) ?>
            <?php View::include('Checkout/modal'); ?>
            <?php else: ?>
                <?php View::include('Feed/static_feed', [
                    'userInfo' => $userInfo,
                    'feed' => $feed,
                    'iFrame' => $iFrame,
                    'platform_fees' => $platform_fees
                ]) ?>
            <?php endif; ?>
        </div>
    </div>
</div>