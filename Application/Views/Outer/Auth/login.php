<?php

use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;

$formValidator = FormValidator::instance("login");

$lang = Model::get('\Application\Models\Language');
?>
<div class="register-page  pt-5 pb-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="register">                            
                            <h2 class="t-36 mb-4"><?= $lang('login_title') ?></h2>
                            <form method="POST" action="<?= URL::current() ?>" class="row form login-form">
                                <div class="col-12 mb-3">
                                    <label for="inputEmail" class="form-label"><?= $lang('email') ?></label>
                                    <input type="email"  oninvalid="this.setCustomValidity('<?= $lang("email_pattern"); ?>')" oninput="this.setCustomValidity('')" name="email" class="form-control" id="inputEmail">
                                    <?php if ( $formValidator->hasError('email') ): ?>
                                        <p class="error"><?= $formValidator->getError('email') ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="inputPassword" class="form-label"><?= $lang('password') ?></label>
                                    <input type="password"  name="password" class="form-control" id="inputPassword">
                                    <?php if ( $formValidator->hasError('password') ): ?>
                                        <p class="error"><?= $formValidator->getError('password') ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 mb-3">
                                    <input type="checkbox"  name="rememberMe" id="remember" >
                                    <label for="remember" class="form-label"><?= $lang('remember_me') ?></label>
                                </div>
                                <div class="col-12 mb-3 custom-align">
                                    <button type="submit" id="submit" class="btn btn-main"><?= $lang('login') ?></button>
                                </div>
                            </form>
                            <p class="forget-pass"> <a href="<?= URL::full('forgot-password') ?>"><?php  echo $lang('forgot_password_auth'); ?></a></p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-right custom-text-right">
                        <h2 class="t-48 mb-4"><?= $lang('dont_have_account') ?></h2>
                        <a href="<?= URL::full('register'); ?>" class="btn-outline-large mb-4"><?= $lang('register_now') ?></a>
                        <video src="<?= URL::asset('Application/Assets/Outer/video/telein.m4v'); ?>" controls muted autoplay  class="login-img mt-5" alt="">
                    </div>
                </div>
            </div>
        </div>

        <define header_css>
            <style>
                video {
                    width: 100%;
                }
            </style>
        </define>
        <define footer_js>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" integrity="sha512-3j3VU6WC5rPQB4Ld1jnLV7Kd5xr+cq9avvhwqzbH/taCRNURoeEpoPBK9pDyeukwSxwRPJ8fDgvYXd6SkaZ2TA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script>
                $('#inputEmail').on('keyup touchend', function() {
                    $(this).val($(this).val().toLowerCase());
                });

                $('#submit').on('click', function() {
                    if ($('#remember').is(':checked')) {
                        $.cookie('inputEmail', $('#inputEmail').val())
                    } else {
                        $.cookie('inputEmail', "")
                    }
                });

                if ($.cookie('inputEmail') !== undefined) {
                    $('#inputEmail').val($.cookie('inputEmail'))
                }
            </script>
        </define>