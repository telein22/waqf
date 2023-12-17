<?php

use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;

$formValidator = FormValidator::instance("forgot_password");

$lang = Model::get(Language::class);

?>
<div class="register-page  pt-5 pb-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="register">                            
                            <h2 class="t-36 mb-4"><?= $lang('reset_password_heading') ?></h2>
                            <form method="POST" action="<?= URL::current() ?>" class="row form login-form">
                                <div class="col-12 mb-3">
                                    <label for="inputEmail" class="form-label"><?= $lang('email') ?></label>
                                    <input type="email"  oninvalid="this.setCustomValidity('<?= $lang("email_pattern"); ?>')"  oninput="this.setCustomValidity('')"  name="email" class="form-control" id="inputEmail">
                                    <?php if ( $formValidator->hasError('email') ): ?>
                                        <p class="error"><?= $formValidator->getError('email') ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 mb-3 custom-align">
                                    <button type="submit" class="btn btn-main"><?= $lang('submit') ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-right custom-text-right">
                        <h2 class="t-48 mb-4"><?= $lang('change_mind') ?></h2>
                        <a href="<?= URL::full('login'); ?>" class="btn-outline-large mb-4"><?= $lang('login') ?></a>
                        <img src="<?= URL::asset('Application/Assets/Outer/images/Messaging-pana.svg'); ?>" class="login-img mt-5" alt="">
                    </div>
                </div>
            </div>
        </div>

        <define footer_js>
            <script>
                var isBusy = false;
                $('.login-form').on('submit', function(e) {
                    if ( isBusy ) {
                        e.preventDefault();
                        return;
                    }

                    isBusy = true;

                    $(this).find('button')[0].disable = true;
                });
            </script>
        </define>