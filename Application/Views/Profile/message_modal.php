<?php

use Application\Models\Conversation;
use System\Core\Model;
use System\Responses\View;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');
?>
<div class="modal fade" id="message-modal" tabindex="-1" role="dialog" aria-labelledby="message-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="message-modal-label">
                    <?= $lang('message_modal_title', ['name'  => htmlentities($user['name'])]); ?>
                    <?php if ($user['account_verified']) echo '<i class="fas fa-check-circle color-primary"></i>' ?>

                    <span class="pull-right"><?= $lang('c_price', ['p' => $messagingPrice]) ?></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="#" method="POST" id="message-modal-form">
                                <div class="form-group">
                                    <label for="message" class="message sr-only"><?= $lang('message') ?></label>
                                    <textarea type="text" name="message" id="message" class="form-control" rows="5"></textarea>
                                </div>
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>" />
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit"><?= $lang('send'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="call-search-content">

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<define header_css>
    <style>
        .call-search-content {
            width: 100%;
        }

        .call-search-content label {
            cursor: pointer;
        }

        .call-search-content input:checked+label {
            background-color: var(--iq-primary) !important;
        }

        #message-modal h5 {
            width: 100%;
        }
    </style>
</define>
<define footer_js>
    <script>
        $('#message-modal-form').on('submit', function(e) {
            e.preventDefault();

            var $btn = $(this).find('button');

            $.ajax({
                url: URLS.book_message,
                data: $(this).serialize(),
                beforeSend: function() {

                    $btn.text('<?= $lang('loading') ?>');

                },
                success: function(data) {
                    if (data.info !== 'success') {
                        toast('danger', '<?= $lang('error') ?>', data.payload);
                        return;
                    }

                    $('#message-modal').modal('hide');

                    // else will found the if of the new conversation
                    // so send it to checkout prepare
                    checkout(data.payload.id, '<?= Conversation::ENTITY_TYPE; ?>');

                    // else
                    $('.call-search-content').html(data.payload);
                },
                complete: function() {
                    $btn.text('<?= $lang('submit') ?>');
                }
            });
        });

        function onSlotBookingSubmit(elm, event) {
            $(elm).submit();
        }
    </script>
</define>