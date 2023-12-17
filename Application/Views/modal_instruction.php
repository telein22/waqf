<?php

use System\Core\Model;
use Application\Models\UserSettings;
use System\Helpers\URL;

$settingsM = Model::get('\Application\Models\UserSettings');
$instruction = $settingsM->take($user['id'], UserSettings::KEY_SKIP_INSTRUCTION);

$lang = Model::get("\Application\Models\Language");

?>
<div class="modal" tabindex="-1" role="dialog" id="instruction">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $lang('instruction_header'); ?></h5>
                <button type="button" id="skip-instruction" class="close" data-dismiss="modal" aria-label="Close">
                    <?= $lang('skip'); ?> <i class="ri-arrow-right-line side-right-icon"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="instruction-wrapper text-center owl-carousel owl-theme">
                    <div class="instruction-items item">
                        <div class="img-wrapper mx-auto">
                            <img src="<?= URL::asset('Application/Assets/images/logo.png') ?>" alt="">
                        </div>
                        <div class="instruction-text mt-4">
                            <p><?= $lang('instruction_desc_1') ?></p>
                        </div>
                    </div>
                    <div class="instruction-items item">
                        <div class="img-wrapper mx-auto">
                            <img src="<?= URL::asset('Application/Assets/images/logo.png') ?>" alt="">
                        </div>
                        <div class="instruction-text mt-4">
                            <p><?= $lang('instruction_desc_2') ?></p>
                        </div>
                    </div>
                    <div class="instruction-items item">
                        <div class="img-wrapper mx-auto">
                            <img src="<?= URL::asset('Application/Assets/images/logo.png') ?>" alt="">
                        </div>
                        <div class="instruction-text mt-4">
                            <p><?= $lang('instruction_desc_3') ?></p>
<!--                            <button type="button" id="skip-instruction2" class="close" data-dismiss="modal" aria-label="Close">-->
<!--                                --><?//= $lang('skip'); ?><!-- <i class="ri-arrow-right-line side-right-icon"></i>-->
<!--                            </button>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<define header_css>
    <style>
        #instruction .modal-header {
            justify-content: space-between;
            align-items: center;
        }

        .instruction-items .img-wrapper {
            width: 200px;
        }

        .instruction-items .img-wrapper img {
            max-width: 100%;
        }

        .instruction-wrapper.owl-carousel .active span {
            margin: 5px 7px !important;
            background: var(--iq-primary) !important;
            display: block;

        }

        .instruction-wrapper.owl-carousel span {
            margin: 5px 7px !important;
            background: none !important;
            border: 0.5px solid black;
            display: block;

        }

        .instruction-wrapper .owl-nav {
            bottom: 50px !important;
            top: unset !important;
            font-weight: bold;
        }

        .instruction-wrapper .owl-nav .owl-prev {
            color: var(--iq-primary) !important;
        }

        .instruction-wrapper .owl-nav .owl-next {
            color: var(--iq-primary) !important;
        }

        .instruction-wrapper.owl-carousel .nav-button {
            height: 50px;
            width: 25px;
            cursor: pointer;
            position: absolute;
            top: 110px !important;
        }

        .instruction-wrapper.owl-carousel .owl-prev {
            left: 15px;
        }

        .instruction-wrapper.owl-carousel .owl-next {
            right: 15px;
        }

        .instruction-items {
            margin: 40px 0;
        }

        .instruction-wrapper {
            height: 455px;
            overflow: hidden;
        }

        #skip-instruction, #skip-instruction2 {
            font-size: 16px;
            font-weight: normal;
             color: red;
            opacity: 1;
            text-shadow: 0 1px 0 #000;
        }

        /*#skip-instruction2 {*/
             /*color: red;*/
            /*font-weight: bolder;*/
        /*}*/

        @media screen and (max-width: 990px) {
            .instruction-items .img-wrapper {
                width: 150px;
            }
        }

        @media screen and (max-width: 450px) {
            .instruction-items .img-wrapper {
                width: 100px;
            }
        }
    </style>
</define>

<define footer_js>
    <script>
        $('.instruction-wrapper').on('initialized.owl.carousel', function() {
            $(this).find('.item').removeClass('d-none');
            // carousel.find('.item').show();
            // carousel.find('.loading-placeholder').hide();
        }).owlCarousel({
            loop: false,
            margin: 10,
            nav: true,
            dots: true,
            rtl: <?= $lang->current() == 'en' ? 'false' : 'true' ?>,
            navText: ["<?= $lang('previous') ?>", "<?= $lang('next') ?>"],
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        });

        var sendSkip = function(e) {
            $.ajax({
                url: '<?= URL::full('/ajax/skip-instruction'); ?>',
                type: 'POST',
                dataType: 'JSON',
                accepts: 'JSON',
                data: {
                    userId: "<?= $user['id'] ?>"
                },
                success: function(data) {
                    // window.location.reload();
                },
                complete: function() {

                }
            });
        };


        $("#skip-instruction, #skip-instruction2").on('click', sendSkip)
        // $("#instruction").on('hidden.bs.modal', sendSkip);

        // $("#lang").on('change', function(e) {
        //     var lang = $(this).val();
        //     $.ajax({
        //         url: '<?php // echo URL::full('/ajax/change-lang-cookie'); ?>',
        //         type: 'POST',
        //         dataType: 'JSON',
        //         accepts: 'JSON',
        //         data: {
        //             lang: lang
        //         },
        //         success: function(data) {
        //             window.location.reload();
        //         },
        //         complete: function() {

        //         }
        //     });
        // })

        <?php if (!$instruction) : ?>
            $("#instruction").modal({backdrop: 'static', keyboard: false}, 'show');
        <?php endif; ?>
    </script>
</define>