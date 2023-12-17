<?php

use Application\Helpers\UserHelper;
use Application\Models\Conversation;
use Application\Models\Message;
use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;
use System\Responses\View;

$lang = Model::get('\Application\Models\Language');

?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="iq-card">
                <div class="iq-card-body">
                    <?php foreach ($messages as $message) : ?>
                        <div class="message-container <?= $conversation['owner_id'] == $message['sender_id'] ? 'self bg-primary' : ' bg-secondary ' ?>">
                            <p class="m-0 name font-size-14"><strong><?= $message['sender']['name'] ?></strong></p>
                            <p><?= htmlentities($message['message']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<define footer_js>
    <script>
        $('#answer').on('input', function(e) {
            if ($(this).val().trim() === '') $('#answer-form .btn')[0].disabled = true;
            else $('#answer-form .btn')[0].disabled = false;
        });
        $('#answer-form .btn').on('click', function(e) {
            e.preventDefault();
            cConfirm('<?= $lang('warning_before_message_answer') ?>', function() {
                $('#answer-form').trigger('submit');
            });
        });
    </script>
</define>