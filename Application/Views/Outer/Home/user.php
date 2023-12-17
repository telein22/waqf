<?php

use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get(Language::class);
?>

<div class="card border-0 shadow-sm position-relative">
    <?php $url = $isLoggedIn ? URL::full('profile/' . $user['id']) : URL::full('outer-profile/' . $user['id']); ?>
    <a href="<?= $url ?>">
        <div class="text-center p-3">
            <img src="<?= $user['avatarUrl'] ?>" class="rounded-circle" alt="Profile Image" width="100" height="100" style="border: 1px solid #3F4AAA; padding: 3px;">

            <h5 class="card-title mb-1 mt-3"><?= $user['name'] ?> <?php if ($user['account_verified']) echo '<i class="fa-regular fa-circle-check"></i>'; ?> </h5>
            <p class="card-text">@<?= $user['username'] ?></p>
<!--            <p class="card-text">-->
<!--                --><?php //if (isset($user['specialty'])) : ?>
<!--                    --><?php //echo $lang('specialties') ?><!--:-->
<!--                    --><?php //foreach ($user['specialty'] as $specialty) : ?>
<!--                        <span class="username-specialty">--><?php //echo $specialty['specialty_' . $lang->current()]; ?><!--</span>-->
<!--                    --><?php //endforeach; ?>
<!--                --><?php //endif; ?>
<!--            </p>-->
        </div>
    </a>
</div>