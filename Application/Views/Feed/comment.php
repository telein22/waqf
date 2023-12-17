<?php

use Application\Helpers\DateHelper;
use Application\Helpers\UserHelper;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');

?>
<div class="d-flex flex-wrap mb-3" id="comment_<?= $comment['id']; ?>">
    <div class="user-img">
    <a href="<?= URL::full('profile/' . $comment['user']['id']); ?>">
        <img src="<?= UserHelper::getAvatarUrl('fit:300,300', $comment['user']['id']); ?>" alt="userimg" class="avatar-35 rounded-circle img-fluid">
    </a>
    </div>
    <div class="comment-data-block ml-3">
        <a href="<?= URL::full('profile/' . $comment['user']['id']); ?>"><h6><?= htmlentities($comment['user']['name']); ?></h6></a>
        <?php $data = json_decode($comment['comment'], true); ?>
        <p class="mb-0"><?= htmlentities($data['text']); ?></p>
        <div class="d-flex flex-wrap align-items-center comment-activity">            
                                                 <!--           <a href="javascript:void();">reply</a>
                                                            <a href="javascript:void();">translate</a> -->
            <span class="text-secondary mr-3"><?= DateHelper::butify($comment['created_at']); ?></span>
            <?php if ( isset($userInfo['id']) && $userInfo['id'] === $comment['user']['id'] ): ?>
                <a href="#" class="text-danger delete-btn"><?= $lang('delete'); ?></a>
            <?php  endif; ?>
        </div>
    </div>
</div>