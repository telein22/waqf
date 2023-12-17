<?php

use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;
use System\Responses\View;

$lang = Model::get('\Application\Models\Language');
$formValidator = FormValidator::instance("settings");
?>

<div class="container">
    <div class="row mt-5 mb-2">
        <div class="col-md-12 text-ar-right">
            <a href="<?= URL::full('dashboard') ?>" class="text-bold text-secondary"><i class="ri-arrow-left-s-line"></i> <?= $lang('back_to_home') ?></a> / <?= $lang('settings') ?>
        </div>
    </div>
    <form method="POST" action="<?= URL::current(); ?>">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="iq-card">
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="lang"><?= $lang('language') ?></label>
                                    <select class="form-control workshop-name-finder" id="lang" name="lang">
                                        <option <?php if( $userLang == 'en' ) echo 'selected' ?> value="en">English</option>
                                        <option <?php if( $userLang == 'ar' ) echo 'selected' ?> value="ar">Arabic</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="iq-card">
                    <div class="iq-card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="msg"><?= $lang('customer_support') ?></label>
                                    <textarea id="msg" name="msg" rows="5" class="form-control workshop-name-finder"></textarea>
                                    <?php if ($formValidator->hasError('msg')) : ?>
                                        <p class="error-msg"><?= $formValidator->getError('msg') ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="whatsapp" class="mr-3">
                                        <input type="radio" checked name="type" id="whatsapp" value="1"/>
                                        <?= $lang('whatsapp') ?>
                                    </label>
                                    <label for="email">
                                        <input type="radio" name="type" id="email" value="2"/>
                                        <?= $lang('email') ?>
                                    </label>
                                    <?php if ($formValidator->hasError('type')) : ?>
                                        <p class="error-msg"><?= $formValidator->getError('type') ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-12 text-ar-right">
                                <button type="submit" class="btn btn-primary"><?= $lang('submit') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<define footer_js>
    <script>
        $("#lang").on('change', function(e) {
            var lang = $(this).val();
            $.ajax({
                url: '<?= URL::full('/ajax/change-lang'); ?>',
                type: 'POST',
                dataType: 'JSON',
                accepts: 'JSON',
                data: {
                    userId: "<?= $userInfo['id'] ?>",
                    lang: lang
                },
                success: function(data) {
                    window.location.reload();
                },
                complete: function() {

                }
            });
        });


    </script>
</define>