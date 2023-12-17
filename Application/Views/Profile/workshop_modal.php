<?php

use System\Core\Model;
use System\Responses\View;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');
?>
<div class="modal fade" id="workshop-profile-modal" tabindex="-1" role="dialog" aria-labelledby="workshop-profile-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="workshop-profile-modal-label"><?= $lang('book_a_workshop'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="#" method="POST" class="text-center" id="workshop-profile-modal-search-form">
                                <div class="form-group">
                                    <label for="workshopDate" class="workshopDate sr-only"><?= $lang('date') ?></label>
                                    <input type="date" name="date" id="workshopDate" class="form-control" min="<?= date('Y-m-d', strtotime('tomorrow')); ?>"/>
                                </div>
                                <div class="input-group mt-2">
                                    <button class="btn btn-primary submit-form" onclick="filterProfileWorkshop(this, event)" type="submit"><?= $lang('submit'); ?></button>
                                    <a onclick="filterProfileWorkshop(this, event, true)" class="btn btn-primary show-all-workshops text-white ml-2"><?= $lang('all'); ?></a>
                                </div>
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>" />
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="workshop-search-content">

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php View::include('Checkout/modal'); ?>
    <define header_css>
        <style>
            #checkout-modal .modal-body{
                background-color: #6153e6 !important;
                color: white !important;
            }
            .workshop-search-content {
                margin-top: 40px;
                width: 100%;
            }

            .workshop-search-content label {
                cursor: pointer;
            }
            .workshop-search-content input:checked + label {
                background-color: var(--iq-primary) !important;
            }
        </style>
    </define>
    <define footer_js>
        <script>
            function filterProfileWorkshop( elm, e, all = null ) {
                e.preventDefault();

                if( all ) {
                    $("#workshopDate").val('');
                }

                var $btn = $(elm);
                var $btnText = $btn.text();

                $.ajax({
                    url: URLS.search_profile_workshop,
                    data: $("#workshop-profile-modal-search-form").serialize(),
                    beforeSend: function() {

                        $btn.text('<?= $lang('loading') ?>');

                    },
                    success: function(data) {
                        if (data.info !== 'success') {
                            return;
                        }

                        // else
                        $('.workshop-search-content').html(data.payload);
                    },
                    complete: function() {
                        $btn.text($btnText);
                    }
                });
            };

            function onSlotBookingSubmit( elm, event ) {                                
                $(elm).submit();
            }
        </script>
    </define>