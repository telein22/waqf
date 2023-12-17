<?php

use System\Core\Model;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');
?>
<div class="modal fade" id="call-modal" tabindex="-1" role="dialog" aria-labelledby="call-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="call-modal-label"><?= $lang('book_a_call'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="#" method="POST" id="call-modal-search-form">
                                <div class="input-group">
                                    <label for="calldate" class="calldate sr-only"><?= $lang('date') ?></label>
                                    <input type="date" name="date" id="calldate" class="form-control" min="<?= date('Y-m-d', strtotime('tomorrow')); ?>"/>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit"><?= $lang('submit'); ?></button>
                                    </div>
                                </div>
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>" />
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
            .call-search-content input:checked + label {
                background-color: var(--iq-primary) !important;
            }
        </style>
    </define>
    <define footer_js>
        <script>
            $('#call-modal-search-form').on('submit', function(e) {
                e.preventDefault();

                var $btn = $(this).find('button');

                $.ajax({
                    url: URLS.search_call_slots,
                    data: $(this).serialize(),
                    beforeSend: function() {

                        $btn.text('<?= $lang('loading') ?>');

                    },
                    success: function(data) {
                        if (data.info !== 'success') {
                            return;
                        }

                        // else
                        $('.call-search-content').html(data.payload);
                    },
                    complete: function() {
                        $btn.text('<?= $lang('submit') ?>');
                    }
                });
            });

            function onSlotBookingSubmit( elm, event ) {                                
                $(elm).submit();
            }
        </script>
    </define>