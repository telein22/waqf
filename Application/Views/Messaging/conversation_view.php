<?php

use Application\Helpers\ConversationHelper;
use Application\Helpers\DateHelper;
use Application\Helpers\UserHelper;
use Application\Models\Conversation;
use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;
use System\Responses\View;

$lang = Model::get('\Application\Models\Language');

$isExpired = ConversationHelper::isExpired($conversation['created_at']);

?>
<div class="container">
    <div class="row mt-5 mb-2 justify-content-center">
        <div class="col-md-8">            
            <a class="lang-ar-right" href="<?= URL::full('messaging/' . ($isAdvisor ? 'a' : 'b')); ?>" class="text-bold text-secondary"><i class="ri-arrow-left-s-line"></i> <?= $lang('back_to_messaging'); ?></a>
            <?php if ($conversation['status'] == Conversation::STATUS_CURRENT ) : ?>
                <p class="expiry-class" style="display: inline;float: right;margin-bottom: 0;">
                    <?php if ( $isExpired ): ?>
                        <a href="#!" class="badge badge-danger"><?= $lang('expired') ?></a>
                    <?php else: ?>
                        <?= $lang('message_expires_in', array('time' => $conversation['remaining'])) ?>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <a class="lang-ar-img" href="<?= URL::full('profile/' . $opponent['id']) ?>">
                            <img src="<?= UserHelper::getAvatarUrl('fit:300,300', $opponent['id']) ?>" />
                        </a>
                        <a href="<?= URL::full('profile/' . $opponent['id']) ?>">
                            <h4 class="card-title"><?= htmlentities($opponent['name']); ?></h4>
                        </a>
                    </div>
                </div>
                <div class="iq-card-body">
                    <?php foreach ($messages as $message) : ?>
                        <?php View::include('Messaging/message', ['message' => $message, 'opponent' => $opponent, 'user' => $user]); ?>
                    <?php endforeach; ?>

                    <?php if ($conversation['status'] === Conversation::STATUS_CURRENT && !$isExpired ) : ?>
                        
                        <?php if ($isAdvisor) : ?>
                            <hr>
                            <form method="POST" action="<?= URL::full('messaging/submit-answer'); ?>" id="answer-form">
                                <div class="form-group">
                                    <label for="answer"><?= $lang('write_answer') ?></label>
                                    <textarea class="form-control" id="answer" name="answer"></textarea>
                                </div>

                                <input type="hidden" name="sender_id" value="<?= $user['id'] ?>" />
                                <input type="hidden" name="receiver_id" value="<?= $opponent['id'] ?>" />
                                <input type="hidden" name="conversation_id" value="<?= $conversation['id'] ?>" />

                                <div class="btn-group">
                                    <button type="submit" class="btn btn-primary" disabled><?= $lang('submit'); ?></button>
                                </div>
                            </form>
                        <?php else : ?>
                            <!-- <div class="alert alert-primary"><?= $lang('messaging_warning') ?></div> -->
                        <?php endif; ?>
                    <?php elseif ($conversation['status'] === Conversation::STATUS_COMPLETED) : ?>
                        <?php if (!$isAdvisor) : ?>
                            <hr>
                            <a href="<?= URL::full('review/' . $conversation['id'] . '/' . Conversation::ENTITY_TYPE) ?>"><?= $lang('rate_service') ?></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<define header_css>
    <style>
        .iq-card-header img {
            width: 40px;
            height: 40px;
            margin-right: 20px;
            border-radius: 50%;
        }

        .iq-card-header .iq-header-title {
            display: flex !important;
            flex-direction: row;
            justify-content: center;
            align-items: center;

        }

        .message-container {
            padding: 15px;
            border-radius: 10px;
        }

        .message-container:not(:last-child) {
            margin-bottom: 20px;
        }
    </style>
</define>

<define footer_js>
    <script>
        $('#answer').on('input', function(e) {
            if ($(this).val().trim() === '') $('#answer-form .btn')[0].disabled = true;
            else $('#answer-form .btn')[0].disabled = false;
        });
        var isSubmitting = false;
        $('#answer-form .btn').on('click', function(e) {
            e.preventDefault();

            if ( isSubmitting ) return;

            cConfirm('<?= $lang('warning_before_message_answer') ?>', function() {
                
                // $('#answer-form .btn')[0].disable = true;
                isSubmitting = true;
                $('#answer-form').trigger('submit');
            });
        });

        function timer() {
            const dateTime = new Date();

            var currentTime = Math.floor(dateTime.getTime() / 1000);

            var remainingTime = '<?= $conversation['expiryTime'] ?>' - currentTime;

            if ( remainingTime <= 0 ) {
                $(".expiry-class").html('<a href="#!" class="badge badge-danger"><?= $lang('expired') ?></a>');
            } else {

                var h = remainingTime / 3600;
                var m = (remainingTime % 3600) / 60;
                var s = (remainingTime % 3600) % 60;

                var time = Math.floor(h) + ':' + Math.floor(m) + ':' + Math.floor(s);

                $(".expiry-class span").html(time);

            }

            setTimeout(() => {
                timer();
            }, 1000);
        }

        timer();
    </script>

</define>