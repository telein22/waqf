<?php

use Application\Helpers\ConversationHelper;
use Application\Helpers\DateHelper;
use Application\Helpers\ServiceHelper;
use Application\Helpers\UserHelper;
use Application\Models\Conversation;
use Application\Models\User;
use System\Core\Model;
use System\Helpers\Strings;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');

$isExpired = ConversationHelper::isExpired($conversation['created_at']);

/**
 * @var User
 */
$userM = Model::get(User::class);

?>
<div class="conversation-card" id="conversation_<?= $conversation['id'] ?>">
    <div class="d-flex justify-content-between flex-wrap">
        <?php if ($isAdvisor) : ?>
            <div class="d-flex flex-wrap mb-3">
                <div class="media-support-user-img mr-3">
                    <a href="<?= URL::full('profile/' . $conversation['creator']['id']) ?>" class="">
                        <img class="rounded-circle img-fluid h-40" src="<?= UserHelper::getAvatarUrl('fit:300,300', $conversation['creator']['id']); ?>" alt="">
                    </a>
                </div>
                <div class="media-support-info">
                    <h5 class="mb-0 d-inline-block"><a href="<?= URL::full('profile/' . $conversation['creator']['id']) ?>" class="">
                            <?= htmlentities($conversation['creator']['name']) ?>
                            <?php if ($conversation['creator']['account_verified']) echo '<i class="fas fa-check-circle color-primary"></i>' ?>
                        </a></h5>
                    <p class="mb-0 d-inline-block">@<?= htmlentities($conversation['creator']['username']) ?></p>
                    <?php if ( $conversation['status'] == Conversation::STATUS_COMPLETED ): ?>
                        <a class="mb-0 d-block" href="#" class=" pull-right"><span class="badge badge-danger"><?= $lang('answered') ?></span></a>
                    <?php else: ?>
                        <a class="mb-0 d-block" href="#" class=" pull-right"><span class="badge badge-danger"><?= $lang($conversation['status']) ?></span></a>
                        <?php if ( $conversation['status'] == Conversation::STATUS_CURRENT && !$isExpired ): ?>
                            <p class="mb-0 mt-2 d-block" href="#" class=" pull-right">
                                <?= $lang('message_expires_in', array('time' => $conversation['remaining'])) ?>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php else : ?>
            <div class="d-flex flex-wrap mb-3">
                <div class="media-support-user-img mr-3">
                    <a href="<?= URL::full('profile/' . $conversation['owner']['id']) ?>" class="">
                        <img class="rounded-circle img-fluid h-40" src="<?= UserHelper::getAvatarUrl('fit:300,300', $conversation['owner']['id']); ?>" alt="">
                    </a>
                </div>
                <div class="media-support-info">
                    <h5 class="mb-0 d-inline-block"><a href="<?= URL::full('profile/' . $conversation['owner']['id']) ?>" class="">
                            <?= htmlentities($conversation['owner']['name']) ?>
                            <?php if ($conversation['owner']['account_verified']) echo '<i class="fas fa-check-circle color-primary"></i>' ?>
                        </a></h5>
                    <p class="mb-0 d-inline-block">@<?= htmlentities($conversation['owner']['username']) ?></p>
                    <?php if ( $conversation['status'] == Conversation::STATUS_COMPLETED ): ?>
                        <a class="mb-0 d-block" href="#" class=" pull-right"><span class="badge badge-danger"><?= $lang('answered') ?></span></a>
                    <?php else: ?>
                        <a class="mb-0 d-block" href="#" class=" pull-right"><span class="badge badge-danger"><?= $lang($conversation['status']) ?></span></a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="conversation-button-group">
            <?php if ($isAdvisor) : ?>
                <?php

                $canExpire = $conversation['status'] == Conversation::STATUS_CURRENT;

                ?>
                <?php if ($canExpire && $isExpired) : ?>
                    <a href="#" class="badge badge-danger" data-container="body" data-toggle="popover" data-placement="top" data-content="<?= $lang('conversation_expired') ?>"><?= $lang('expired') ?></a>
                <?php else : ?>
                    <div class="btn-group">
                        <?php if ($isAdvisor && $conversation['status'] == Conversation::STATUS_CURRENT) : ?>
                            <a href="<?= URL::full('messaging/view/' . $conversation['id']); ?>" class="started-btn btn btn-primary"><?= $lang('answer'); ?></a>
                        <?php else : ?>
                            <a href="<?= URL::full('messaging/view/' . $conversation['id']); ?>" class="started-btn btn btn-primary"><?= $lang('view'); ?></a>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>
            <?php else : ?>
                <!-- Normal user only can view conversations -->
                <a href="<?= URL::full('messaging/view/' . $conversation['id']); ?>" class="started-btn btn btn-primary"><?= $lang('view'); ?></a>
            <?php endif; ?>
        </div>
    </div>
    <p> <?= Strings::limit($conversation['first_message'], 300) ?></p>
    <p class="badge badge-secondary">
        <?php if ( $isAdvisor ): ?>
            <?= $lang('ref_id', [ 'id' => $conversation['id'] ]); ?>
        <?php else: ?>
            <?= $lang('ref_id', [ 'id' => ServiceHelper::generateRef($userM->getId(), $conversation['id'], Conversation::ENTITY_TYPE) ]); ?>
        <?php endif; ?>            
    </p>
    <p class="badge badge-secondary">        
        <i class="ri-time-line"></i> <?= DateHelper::butify($conversation['created_at']); ?>
    </p>
    <?php if ( $conversation['last_message'] ): ?>
        <p class="badge badge-secondary">        
                <i class="ri-question-answer-line"></i> <?= DateHelper::butify($conversation['last_message']['created_at']); ?>        
        </p>
    <?php endif; ?>
</div>
<define footer_js>
    <!-- <script>
        new Workshop(<?php // echo $workshop['id']
                        ?>);
    </script> -->
</define>