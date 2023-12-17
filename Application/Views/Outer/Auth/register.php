<?php

use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;

$formValidator = FormValidator::instance("register");

$lang = Model::get('\Application\Models\Language');

?>
<div class="register-page pt-5 pb-5">
    <div class="container ">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="register">
                    <h2 class="t-36 mb-4"><?= $lang("register_title"); ?></h2>
                    <form action="<?php URL::current() ?>" class="row form register-form" method="POST" onsubmit="process(event)">
<!--                        <div class="col-12 mb-3">-->
<!--                            <input type="radio" id="inputIndividual" name="type" value="subscriber" checked --><?php //= htmlentities($formValidator->getValue('type')) == 'subscriber' ? 'checked' : ''; ?><!-- >-->
<!--                            <label for="inputIndividual" class="form-label individual">--><?php //= $lang("individual"); ?><!--</label>-->
<!--                            <br/>-->
<!--                            <input type="radio" id="inputEntity" name="type" value="entity" --><?php //= htmlentities($formValidator->getValue('type')) == 'entity' ? 'checked' : ''; ?><!-- >-->
<!--                            <label for="inputEntity" class="form-label">--><?php //= $lang("entity"); ?><!--</label>-->
<!--                        </div>-->
                        <div class="col-12 mb-3 form-group required">
                            <label for="inputname" class="form-label"><?= $lang("name"); ?></label>
                            <input type="text" class="form-control" id="inputname" name="name" value="<?= htmlentities($formValidator->getValue('name')); ?>">
                            <?php if ($formValidator->hasError('name')) : ?>
                                <p class="error"><?= $formValidator->getError('name'); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-12 mb-3 form-group required">
                            <label for="inputusername" class="form-label"><?= $lang("username"); ?></label>
                            <div>
                                <input type="text" class="form-control " maxlength="15" id="inputusername" autocomplete="off" name="username" value="<?= htmlentities($formValidator->getValue('username')); ?>">
                                <img id="username-availability" class="d-none" style="color: green; font-size: 24px; float:left; width:40px; height:40px">
                            </div>
                            <?php if ($formValidator->hasError('username')) : ?>
                                <p class="error"><?= $formValidator->getError('username'); ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 mb-3 form-group required" id="phone-div">
                            <label for="inputPhone" class="form-label"><?= $lang("phone"); ?></label>
                        </div>
                        <input type="text" class="d-none" id="international" name="phone"  value="<?= htmlentities($formValidator->getValue('phone')); ?>">
                        <input type="text" class="form-control inputPhone" id="inputPhone" name="phone"  value="<?= htmlentities($formValidator->getValue('phone')); ?>">
                        <?php if ($formValidator->hasError('phone')) : ?>
                            <p class="error"><?= $formValidator->getError('phone'); ?></p>
                        <?php endif; ?>

                        <div class="col-12 mb-3 form-group required">
                            <label for="inputEmail" class="form-label"><?= $lang("email"); ?></label>
                            <input type="email" oninvalid="this.setCustomValidity('<?= $lang("email_pattern"); ?>')" oninput="this.setCustomValidity('')" class="form-control" id="inputEmail" name="email" value="<?= htmlentities($formValidator->getValue('email')); ?>">
                            <?php if ($formValidator->hasError('email')) : ?>
                                <p class="error"><?= $formValidator->getError('email'); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-12 mb-3 form-group required">
                            <label for="inputPassword" class="form-label"><?= $lang("password"); ?></label>
                            <input type="password" class="form-control" id="inputPassword" name="password">
                            <?php if ($formValidator->hasError('password')) : ?>
                                <p class="error"><?= $formValidator->getError('password'); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-12 mb-3 form-group required">
                            <label for="inputConfirmPassword" class="form-label"><?= $lang("confirm_password"); ?></label>
                            <input type="password" class="form-control" id="inputConfirmPassword" name="confirm_password">
                            <?php if ($formValidator->hasError('confirm_password')) : ?>
                                <p class="error"><?= $formValidator->getError('confirm_password'); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-12 mb-3 d-none">
                            <label for="" class="form-label me-4"><?= $lang("select_speciality"); ?></label>
                            <?php $old = $formValidator->getValue('specialties', []); ?>
                            <select multiple name="specialties[]" class="form-control select2" id="specialty">
                                <?php foreach ($specialties as $specialty) : ?>
                                    <option value="<?= $specialty['id'] ?>" <?= in_array($specialty['id'], $old) ? 'selected' : '' ?>><?= htmlentities($specialty['specialty_en']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($formValidator->hasError('specialties')) : ?>
                                <p class="error"><?= $formValidator->getError('specialties'); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-12 mb-3 d-none">
                            <label for="" class="form-label me-4"><?= $lang("select_sub_speciality"); ?></label>
                            <?php $old = $formValidator->getValue('sub_specialties', []); ?>
                            <select multiple name="sub_specialties[]" class="form-control select2" id="subSpecialty">
                                <?php foreach ($subSpecialties as $specialty) : ?>
                                    <option value="<?= $specialty['id'] ?>" <?= in_array($specialty['id'], $old) ? 'selected' : '' ?>><?= htmlentities($specialty['specialty_en']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($formValidator->hasError('sub_specialties')) : ?>
                                <p class="error"><?= $formValidator->getError('sub_specialties'); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-12 mb-3 form-group required">
                            <input type="checkbox" id="checkbox" name="checkbox">
                            <label for="checkbox" class="form-label"><?= $lang("agree_terms_condition"); ?></label>
                            <?php if ($formValidator->hasError('checkbox')) : ?>
                                <p class="error"><?= $formValidator->getError('checkbox'); ?></p>
                            <?php endif; ?>
                        </div>


                        <input type="hidden" id="lang-input" name="lang">


                        <div class="col-12 mb-2 custom-align">
                            <button type="submit" id="register-btn" disabled class="btn btn-main"><?= $lang('register') ?></button>
                        </div>
                        <p class="terms-link"><?= $lang('terms_register', ['url' => URL::full('terms')]) ?></p>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-right custom-text-right">
                <h2 class="t-48 mb-4"><?= $lang('already_have_account'); ?></h2>
                <a href="<?= URL::full('login'); ?>" class="btn-outline-large mb-4 custom-align"><?= $lang('login') ?></a>
                <video src="<?= URL::asset('Application/Assets/Outer/video/telein.m4v'); ?>" controls muted autoplay class="login-img mt-5" alt="">
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
    <script>
        $('#inputEmail').on('keyup touchend', function() {
            $(this).val($(this).val().toLowerCase());

            $('#lang-input').val($('#langHeader').find(':selected').val());
        });

        $("#checkbox").on('change', function(e) {
            if ($(this).is(":checked")) {
                $("#register-btn").prop('disabled', false);
            } else {
                $("#register-btn").prop('disabled', true);
            }
        })

        function toText(string) {
            var elm = document.createElement('div');
            elm.innerText = string;
            return elm.innerText;
        }

        $('#specialty').on('change', function(e) {
            e.preventDefault();

            var value = $(this).val();

            var $subSpecialty = $('#subSpecialty');

            $.ajax({
                url: URLS.specialty_get_sub_specialties,
                type: 'POST',
                dataType: 'JSON',
                accepts: 'JSON',
                data: {
                    specialtyId: value
                },
                beforeSend: function() {},
                success: function(data) {
                    if (data.payload.length >= 1) {
                        var i = 0;
                        $subSpecialty.html('');
                        for (; i < data.payload.length; i++) {
                            $subSpecialty.append('<option value="' + data.payload[i].id + '">' + toText(data.payload[i].specialty_<?= $lang->current(); ?>) + '</option>');
                        }
                        $subSpecialty[0].disabled = false;
                    }
                }
            });
        })
    </script>

    <script>
        const phoneInputField = document.querySelector("#inputPhone");
        const phoneInput = window.intlTelInput(phoneInputField, {
            initialCountry: "sa",
            preferredCountries: ["sa", "qa", "bh", "kw", "om", "ae", "eg", "sy", "jo", "ps", "lb", "iq", "ly", "dz", "sd", "ma", "tn", "mr"],
            utilsScript:
                "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });

        function process(event) {
            const phoneInputField = document.querySelector("#inputPhone");
            const phoneNumber = phoneInput.getNumber();
            phoneInputField.value = phoneNumber;
        }

        $('#inputusername').keydown(function (e) {
            $('#inputusername').css({"width": "100%"})
            $('#username-availability').addClass('d-none');
        });

        $('#inputusername').on('focusout', function () {
            formData = new FormData();
            var username = $(this).val();

            if (username.length <= 0 ) {
                return;
            }

            formData.append('username', username);

            $.ajax({
                url: "<?= URL::full('/ajax/check-username') ?>",
                type: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function(data) {
                    $('#inputusername').css({"width": "87%", "float": "right"})
                    $('#username-availability').css({"float": "left"})
                    $('#username-availability').removeClass('d-none');

                    if (data.info === 'success') {
                        $('#username-availability').attr('src', "<?= URL::asset('Application/Assets/Outer/home/images/tick.png') ?>");
                    } else if (data.info === 'error') {
                        $('#username-availability').attr('src', "<?= URL::asset('Application/Assets/Outer/home/images/cross.png') ?>");
                    }
                },
                complete: function() {

                }
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" integrity="sha512-3j3VU6WC5rPQB4Ld1jnLV7Kd5xr+cq9avvhwqzbH/taCRNURoeEpoPBK9pDyeukwSxwRPJ8fDgvYXd6SkaZ2TA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('#register-btn').on('click', function() {
            $.cookie('inputEmail', $('#inputEmail').val())
        });
    </script>
</define>