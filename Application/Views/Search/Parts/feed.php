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
<?php $data = $feed['data']; ?>
<li>
    <div class="d-flex align-items-center">        
        <div class="user-img img-fluid">
        <a href="<?= !$userM->isLoggedIn() ? URL::full('outer-profile/' . $feed['user']['id']) : URL::full('profile/' . $feed['user']['id']) ?>">
            <img src="<?= UserHelper::getAvatarUrl('fit:300,300', $feed['user']['id']) ?>" alt="story-img" class="rounded-circle avatar-40"></div>
        </a>
        <div class="media-support-info ml-3">
            <a href="<?= !$userM->isLoggedIn() ? URL::full('outer-profile/' . $feed['user']['id']) : URL::full('profile/' . $feed['user']['id']) ?>">
                <h6><?= htmlentities($feed['user']['name']); ?></h6>
            </a>
            <p class="mb-0">
                @<?= $feed['user']['username'] ?>
            </p>
        </div>
        <div class="d-flex align-items-center">
            <a href="<?= URL::full('/feed/' . $feed['id']) ?>" class="mr-3 btn btn-primary rounded"><?= $lang('view') ?></a>
        </div>
    </div>
    <p class="mt-3 alert alert-danger">
        <?= htmlentities($feed['text']); ?>
        <?php if (!empty($data['workshop'])) : ?>
            <br />
            <?php if ( $data['workshop'] == 'deleted' ): ?>
                <span class="text-info"><?= $lang('feed_workshop_is_deleted') ?></span>
            <?php else: ?>
                <span class="text-info"><?= $lang('feed_has_workshop', [ 'name' => $data['workshop']['name'] ]); ?></span>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (!empty($data['image'])) : ?>
            <br />
            <span class="text-info">This feed have an image.</span>
        <?php endif; ?>
    </p>
</li>