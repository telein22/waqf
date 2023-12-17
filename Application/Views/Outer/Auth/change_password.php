<?php

use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;

$formValidator = FormValidator::instance("change_password");
$lang = Model::get('\Application\Models\Language');
?>
<div class="register-page  pt-5 pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="register">        
                    <h2 class="t-36 mb-4"><?= $lang('change_password_heading') ?></h2>
                    <p class="text-success"><?= $lang('check_email_otp') ?></p>            
                    <form method="POST" action="<?= URL::current() ?>" class="row form login-form">
                        <div class="col-12 mb-3">
                            <label for="otp" class="form-label"><?= $lang('otp') ?></label>
                            <input type="text" name="otp" class="form-control" id="otp">
                            <?php if ($formValidator->hasError('otp')) : ?>
                                <p class="error"><?= $formValidator->getError('otp') ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="password" class="form-label"><?= $lang('password') ?></label>
                            <input type="password" name="password" class="form-control" id="password">
                            <?php if ($formValidator->hasError('password')) : ?>
                                <p class="error"><?= $formValidator->getError('password') ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="confirm_password" class="form-label"><?= $lang('confirm_password') ?></label>
                            <input type="password" name="confirm_password" class="form-control" id="confirm_password">
                            <?php if ($formValidator->hasError('confirm_password')) : ?>
                                <p class="error"><?= $formValidator->getError('confirm_password') ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-12 mb-3 custom-align">
                            <button type="submit" class="btn btn-main"><?= $lang('save') ?></button>
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