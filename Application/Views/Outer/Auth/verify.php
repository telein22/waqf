<?php

use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;

$formValidator = FormValidator::instance("verify");

$lang = Model::get(Language::class);

?>
<div class="register-page  pt-5 pb-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="register">                            
                            <h2 class="t-36 mb-4"><?= $lang('verify_email_header') ?></h2>
                            <form action="<?= URL::current() ?>" method="POST" class="row form login-form">
                                <div class="col-12 mb-3">
                                    <label for="inputEmail" class="form-label"><?= $lang('otp') ?></label>
                                    <input type="text" name="email_token" class="form-control" id="inputEmail">
                                    <?php if ( $formValidator->hasError('email_token') ): ?>
                                        <p class="error"><?= $formValidator->getError('email_token'); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 mb-3">
                                    <a href="#" class="pull-right resend-otp" style="text-decoration: underline; color: #3f4aaa;"><?= $lang('resend_otp') ?></a>
                                    <button type="submit" class="pull-left btn btn-main"><?= $lang('verify') ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-right">
                        <img src="<?= URL::asset('Application/Assets/Outer/images/Messaging-pana.svg'); ?>" class="login-img mt-5" alt="">
                    </div>
                </div>
            </div>
        </div>

        <define footer_js>
            <script>
                var isBusy = false;
                $('.resend-otp').on('click', function(e) {
                    e.preventDefault();

                    if ( isBusy ) return;

                    var oldText = $(this).text();
                    var sendText = '<?= $lang('otp_sent') ?>';
                    var $self = $(this);

                    $.ajax({
                        url: URLS.auth_resend_otp,
                        type: 'POST',
                        beforeSend: function() {
                            isBusy = true;
                        },
                        success: function(data) {
                            $self.text(sendText);

                            setTimeout(function() {
                                $self.text(oldText);
                                isBusy = false;
                            }, 5000);
                        },
                        complete: function() {
                            
                        }
                    });
                });
            </script>
        </define>