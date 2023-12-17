<?php

use Application\Helpers\UserHelper;
use System\Helpers\URL;

?>

<div class="col-md-3 col-6 pl-4 pr-4 pb-3">
    <a href="<?= URL::full('profile/' . $user['id']); ?>">
        <img src="<?= UserHelper::getAvatarUrl('fit:300,300', $user['id']); ?>" alt="gallary-image" class="img-fluid rounded-circle" />
        <h4 class="mt-3 text-center"><?= htmlentities($user['name']); ?><?php if ($user['account_verified']) echo '<i class="fas fa-check-circle color-primary"></i>' ?></h4>
    </a>
</div>