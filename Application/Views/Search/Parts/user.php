<?php

use Application\Helpers\UserHelper;
use Application\Models\Language;
use Application\Models\User;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get(Language::class);

/**
 * @var User
 */
$userM = Model::get(User::class);
?>
<li class="d-flex align-items-center">
    <div class="user-img img-fluid">
        <a href="<?= !$userM->isLoggedIn() ? URL::full('outer-profile/' . $user['id']) : URL::full('profile/' . $user['id']) ?>">
            <img src="<?= UserHelper::getAvatarUrl('fit:300,300', $user['id']) ?>" alt="story-img" class="rounded-circle avatar-40">
        </a>
    </div>
    <div class="media-support-info ml-3">
        <a href="<?= !$userM->isLoggedIn() ? URL::full('outer-profile/' . $user['id']) : URL::full('profile/' . $user['id']) ?>">
            <h6><?= htmlentities($user['name']); ?>
                <?php if ($user['account_verified']) echo '<i class="fas fa-check-circle color-primary"></i>' ?>
            </h6>
        </a>
        <p class="mb-0">
            @<?= $user['username'] ?>
            <?php if ($user['rate'] > 0) : ?>
                <span class=" text-danger"><i class="ri-star-fill"></i> <?= number_format($user['rate'], 1); ?> (<?= $user['totalRate']; ?>)</span>
            <?php endif; ?>
        </p>
    </div>
    <div class="d-flex align-items-center">
        <a href="<?= !$userM->isLoggedIn() ? URL::full('outer-profile/' . $user['id']) : URL::full('profile/' . $user['id']) ?>" class="mr-3 btn btn-primary rounded"><?= $lang('view') ?></a>
    </div>
</li>