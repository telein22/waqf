<?php

use Application\Helpers\UserHelper;
use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get(Language::class);

// Prepare sub special ty
$spcString = '';
if (isset($user['sub_specialty'])) {
    $specs = [];
    foreach ($user['sub_specialty'] as $spec) {
        $specs[] = $spec['specialty_en'] . ',' . $spec['specialty_ar'];
    }

    $spcString = implode(",", $specs);
}

?>
<div class="col-md-4 user-column-dashboard" data-name="<?= $user['name'] ?>" data-username="<?= $user['username'] ?>" data-specialty="<?= htmlentities($spcString); ?>">
    <div class="iq-card">
        <div class="iq-card-body profile-page p-0">
            <div class="profile-header-image">
                <div class="cover-container">                    
                    <img src="<?= UserHelper::getCoverUrl('fit:313,117', $user['id']); ?>" alt="profile-bg" class=" img-fluid w-100">
                </div>
                <div class="profile-info p-4">
                    <div class="user-detail">
                        <div class="text-center">
                            <div class="profile-detail">
                                <div class="profile-img">
                                    <a href="<?= URL::full('profile/' . $user['id']); ?>">
                                        <img src="<?= UserHelper::getAvatarUrl('fit:300,300', $user['id']); ?>" alt="profile-img" class="avatar-130 img-fluid" />
                                    </a>
                                </div>
                                <div class="user-data-block mb-3">
                                    <h4 class="text-nowrap w-100"><a href="<?= URL::full('profile/' . $user['id']); ?>">
                                            <?= htmlentities($user['name']); ?></a>
                                        <?php if ($user['account_verified']) echo '<i class="fas fa-check-circle color-primary"></i>' ?>
                                    </h4>
                                    <h6>@<?= htmlentities($user['username']); ?></h6>
                                    <?php if ($user['sub_specialty']) : ?>
                                        <?php foreach ($user['sub_specialty'] as $spec) : ?>
                                            <span class="badge badge-primary"><?= $spec['specialty_' . $lang->current()]; ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-secondary d-none"><?= $lang('following') ?></button>
                            <?php if ( !in_array($user['id'], $followingIds) ): ?>
                                <button type="submit" class="btn btn-primary" onclick="onclickFollow(this, <?= $user['id'] ?>)"><?= $lang('follow') ?></button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>