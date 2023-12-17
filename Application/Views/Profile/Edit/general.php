<?php

use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;
use System\Responses\View;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

$formValidator = FormValidator::instance("edit_general");

?>
<div class="tab-pane active" id="general-information" role="tabpanel">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title"><?= $lang('general_information'); ?></h4>
            </div>
        </div>
        <form method="POST" action="<?= URL::current(); ?>">
            <div class="iq-card-body pb-0">
                <div class=" row align-items-center">
                    <!-- <div class="form-group col-sm-6">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php // echo htmlentities($formValidator->getValue('name', $user['username'])) 
                                                                                                        ?>">
                    </div> -->
                    <div class="form-group col-sm-12">
                        <label for="name"><?= $lang('name'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlentities($formValidator->getValue('name', $user['name'])) ?>">
                        <?php if ($formValidator->hasError('name')) : ?>
                            <p class="text-danger"><?= $formValidator->getError('name'); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if (!$isEntity) : ?>
                    <div class="form-group col-sm-12">
                        <label for="name"><?= $lang('your_entity'); ?></label>
                        <select class="form-control select2" id="entity" name="entity" <?= empty($entities) ? 'disabled' : '' ?>>
                            <option value="0"><?= $lang('select_an_entity') ?></option>
                            <?php foreach ($entities as $entityV) : ?>
                                <option value="<?= $entityV['id'] ?>" <?= $formValidator->getValue('entity', $user['entity_id']) == $entityV['id'] ? 'selected' : '';  ?>><?= htmlentities($entityV['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif ?>
                    <div class="form-group col-sm-6">
                        <label for="email"><?= $lang('email'); ?><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="email" name="email" value="<?= $formValidator->getValue('email', $user['email']) ?>">
                        <?php if ($formValidator->hasError('email')) : ?>
                            <p class="text-danger"><?= $formValidator->getError('email'); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="phone"><?= $lang('phone'); ?>
                            <?php if (!$isEntity) : ?>
                                <span class="text-danger">*</span>
                            <?php endif; ?>
                        </label>
                        <p><?= $lang('phone_description'); ?></p>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= $formValidator->getValue('phone', $phone) ?>">
                        <?php if ($formValidator->hasError('phone')) : ?>
                            <p class="text-danger"><?= $formValidator->getError('phone'); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if (!$isEntity) : ?>
                        <div class="form-group col-sm-6">
                            <label class="d-block"><?= $lang('gender') ?><span class="text-danger">*</span></label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="male" name="gender" class="custom-control-input" value="1" <?= $formValidator->getValue('gender', $gender) == 1 ? 'checked' : ''  ?>>
                                <label class="custom-control-label" for="male"> Male </label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="fmale" name="gender" class="custom-control-input" value="2" <?= $formValidator->getValue('gender', $gender) == 2 ? 'checked' : ''  ?>>
                                <label class="custom-control-label" for="fmale"> Female </label>
                            </div>
                            <?php if ($formValidator->hasError('gender')) : ?>
                                <p class="text-danger"><?= $formValidator->getError('gender'); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="dob"><?= $lang('dob') ?><span class="text-danger">*</span></label>
                            <input class="form-control" name="dob" type="date" id="dob" value="<?= $formValidator->getValue('dob', $dob) ?>">
                            <?php if ($formValidator->hasError('dob')) : ?>
                                <p class="text-danger"><?= $formValidator->getError('dob'); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group col-sm-6">
                        <label><?= $lang('country') ?><span class="text-danger">*</span></label>
                        <?php // var_dump($formValidator->getValue('country', $country)); 
                        ?>
                        <select class="form-control" id="country" name="country">
                            <option value="0"><?= $lang('select_a_country') ?></option>
                            <?php foreach ($countries as $countryV) : ?>
                                <option value="<?= $countryV['id'] ?>" <?= $formValidator->getValue('country', $country) == $countryV['id'] ? 'selected' : '';  ?>><?= htmlentities($countryV[$lang->current() . '_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($formValidator->hasError('country')) : ?>
                            <p class="text-danger"><?= $formValidator->getError('country'); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="city"><?= $lang('city'); ?><span class="text-danger">*</span></label>
                        <select class="form-control" id="city" name="city" <?= empty($cities) ? 'disabled' : '' ?>>
                            <option><?= $lang('select_a_city') ?></option>
                            <?php foreach ($cities as $cityV) : ?>
                                <option value="<?= $cityV['id'] ?>" <?= $formValidator->getValue('city', $city) == $cityV['id'] ? 'selected' : '';  ?>><?= htmlentities($cityV[$lang->current() . '_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($formValidator->hasError('city')) : ?>
                            <p class="text-danger"><?= $formValidator->getError('city'); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="spl"><?= $isEntity ? $lang('entity_specialties') : $lang('specialties') ?><span class="text-danger">*</span></label>
                        <?php $oldSpl = $formValidator->getValue('spl', array_keys($uspl)); ?>
                        <select class="form-control select2" id="spl" name="spl[]" multiple>
                            <?php foreach ($specialties as $specialtyV) : ?>
                                <option value="<?= $specialtyV['id'] ?>" <?= in_array($specialtyV['id'], $oldSpl) ? 'selected' : '';  ?>><?= htmlentities($specialtyV['specialty_' . $lang->current()]); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($formValidator->hasError('spl')) : ?>
                            <p class="text-danger"><?= $formValidator->getError('spl'); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if(!$isEntity): ?>
                        <div class="form-group col-sm-12">
                            <label for="subSpl"><?= $lang('sub_specialists') ?><span class="text-danger">*</span></label>
                            <?php $oldSpl = $formValidator->getValue('subSpl', array_keys($usubspl)); ?>
                            <select class="form-control select2" id="subSpl" name="subSpl[]" multiple>
                                <?php foreach ($subSpecialties as $specialtyV) : ?>
                                    <option value="<?= $specialtyV['id'] ?>" <?= in_array($specialtyV['id'], $oldSpl) ? 'selected' : '';  ?>><?= htmlentities($specialtyV['specialty_' . $lang->current()]); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($formValidator->hasError('subSpl')) : ?>
                                <p class="text-danger"><?= $formValidator->getError('subSpl'); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group col-sm-12">
                        <label for="subSpl"><?= $lang('bio') ?><span class="text-danger">*</span></label>
                        <textarea placeholder="<?= $isEntity ? $lang('about_bio_placeholder_entity') : $lang('about_bio_placeholder') ?>" name="bio" class="form-control"><?= $formValidator->getValue('bio', $bio); ?></textarea>
                        <?php if ($formValidator->hasError('bio')) : ?>
                            <p class="text-danger"><?= $formValidator->getError('bio') ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="achievements"><?= $lang('achievements') ?></label>
                        <textarea id="achievements" placeholder="<?= $lang('about_achievements_placeholder') ?>" name="achievements" class="form-control"><?= $formValidator->getValue('achievements', $achievements); ?></textarea>
                        <?php if ($formValidator->hasError('achievements')) : ?>
                            <p class="text-danger"><?= $formValidator->getError('achievements') ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="iq-card-header d-flex justify-content-between d-none">
                <div class="iq-header-title d-none">
                    <h4 class="card-title"><?= $lang('bank_details'); ?></h4>
                </div>
            </div>
            <div class="iq-card-body">
                <div class="form-group d-none">
                    <label for="b1"><?= $lang('enter_beneficiary_name') ?></label>
                    <input type="text" class="form-control" id="b1" name="b1" value="<?= htmlentities($formValidator->getValue('b1', $bank1)); ?>">
                    <?php if ($formValidator->hasError('b1')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('b1'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group d-none">
                    <label for="b2"><?= $lang('enter_account_number') ?></label>
                    <input type="text" class="form-control" id="b2" name="b2" value="<?= htmlentities($formValidator->getValue('b2', $bank2)); ?>">
                    <?php if ($formValidator->hasError('b2')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('b2'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group d-none">
                    <label for="b3"><?= $lang('enter_bic_code') ?></label>
                    <input type="text" class="form-control" id="b3" name="b3" value="<?= htmlentities($formValidator->getValue('b3', $bank3)); ?>">
                    <?php if ($formValidator->hasError('b3')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('b3'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="<?php if ($lang->current() == 'ar') echo 'text-left'; else echo 'text-right';?>">
                    <button type="submit" class="btn btn-primary mr-2"><?= $lang('save'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<define footer_js>
    <script>
        $('#country').on('change', function(e) {
            e.preventDefault();

            var value = $(this).val();

            var $city = $('#city');

            $.ajax({
                url: URLS.location_get_cities,
                type: 'POST',
                dataType: 'JSON',
                accepts: 'JSON',
                data: {
                    countryId: value
                },
                beforeSend: function() {
                    $city[0].disabled = true;
                },
                success: function(data) {
                    if (data.payload.length >= 1) {
                        var i = 0;
                        $city.html('');
                        for (; i < data.payload.length; i++) {

                            $city.append('<option value="' + data.payload[i].id + '">' + toText(data.payload[i].<?= $lang->current(); ?>_name) + '</option>');
                        }
                        $city[0].disabled = false;
                    }
                }
            });
        })

        $('#spl').on('change', function(e) {
            e.preventDefault();

            var value = $(this).val();

            var $subSpecialty = $('#subSpl');

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
</define>