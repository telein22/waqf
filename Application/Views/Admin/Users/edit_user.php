<?php

use System\Core\Model;
use System\Helpers\URL;
use Application\Models\User;

use System\Libs\FormValidator;

$formValidator = FormValidator::instance("user");

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- <div class="card-header">
                        <h3 class="card-title">DataTable with default features</h3>
                    </div> -->
                    <!-- /.card-header -->
                    <form action="<?= URL::current() ?>" method="post">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="email"><?= $lang('name') ?></label>
                                <input required type="text" value="<?= htmlentities($formValidator->getValue('name', $editInfo['name'])); ?>" name="name" class="form-control" id="name" placeholder="<?= $lang('name') ?>">
                                <?php if ($formValidator->hasError('name')) : ?>
                                    <p class="error"><?= $formValidator->getError('name'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="username"><?= $lang('username') ?></label>
                                <input required type="text" value="<?= htmlentities($formValidator->getValue('username', $editInfo['username'])); ?>" name="username" readonly class="form-control" id="username" placeholder="<?= $lang('username') ?>">
                                <?php if ($formValidator->hasError('username')) : ?>
                                    <p class="error"><?= $formValidator->getError('username'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="email"><?= $lang('email') ?></label>
                                <input required type="email" value="<?= htmlentities($formValidator->getValue('email', $editInfo['email'])); ?>" name="email" class="form-control" id="email" placeholder="<?= $lang('email') ?>">
                                <?php if ($formValidator->hasError('email')) : ?>
                                    <p class="error"><?= $formValidator->getError('email'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="phone"><?= $lang('phone'); ?></label>
                                <input type="text" <?= $editInfo['type'] != User::TYPE_ENTITY ? 'required' : '' ?> class="form-control" id="phone" name="phone" value="<?= $formValidator->getValue('phone', $phone) ?>">
                                <?php if ($formValidator->hasError('phone')) : ?>
                                    <p class="text-danger"><?= $formValidator->getError('phone'); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if ($editInfo['type'] != User::TYPE_ENTITY): ?>
                                <div class="form-group ">
                                    <label class="d-block"><?= $lang('gender') ?></label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="male" name="gender" class="custom-control-input" value="1" <?= $formValidator->getValue('gender', $gender) == 1 ? 'checked' : ''  ?>>
                                        <label class="custom-control-label" for="male"> Male </label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input required type="radio" id="fmale" name="gender" class="custom-control-input" value="2" <?= $formValidator->getValue('gender', $gender) == 2 ? 'checked' : ''  ?>>
                                        <label class="custom-control-label" for="fmale"> Female </label>
                                    </div>
                                    <?php if ($formValidator->hasError('gender')) : ?>
                                        <p class="text-danger"><?= $formValidator->getError('gender'); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group ">
                                    <label for="dob"><?= $lang('dob') ?></label>
                                    <input required class="form-control" name="dob" type="date" id="dob" value="<?= $formValidator->getValue('dob', $dob) ?>">
                                    <?php if ($formValidator->hasError('dob')) : ?>
                                        <p class="text-danger"><?= $formValidator->getError('dob'); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label><?= $lang('country') ?></label>
                                <?php // var_dump($formValidator->getValue('country', $country)); 
                                ?>
                                <select required class="form-control" id="country" name="country">
                                    <option value="0"><?= $lang('select_a_country') ?></option>
                                    <?php foreach ($countries as $countryV) : ?>
                                        <option value="<?= $countryV['id'] ?>" <?= $formValidator->getValue('country', $country) == $countryV['id'] ? 'selected' : '';  ?>><?= htmlentities($countryV[$lang->current() . '_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if ($formValidator->hasError('country')) : ?>
                                    <p class="text-danger"><?= $formValidator->getError('country'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group ">
                                <label for="city"><?= $lang('city'); ?></label>
                                <select required class="form-control" id="city" name="city" <?= empty($cities) ? 'disabled' : '' ?>>
                                    <option><?= $lang('select_a_city') ?></option>
                                    <?php foreach ($cities as $cityV) : ?>
                                        <option value="<?= $cityV['id'] ?>" <?= $formValidator->getValue('city', $city) == $cityV['id'] ? 'selected' : '';  ?>><?= htmlentities($cityV[$lang->current() . '_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if ($formValidator->hasError('city')) : ?>
                                    <p class="text-danger"><?= $formValidator->getError('city'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group ">
                                <label for="spl"><?= $lang('specialties') ?></label>
                                <?php $oldSpl = $formValidator->getValue('spl', array_keys($uspl)); ?>
                                <select required class="form-control select2" id="spl" name="spl[]" multiple>
                                    <?php foreach ($specialties as $specialtyV) : ?>
                                        <option value="<?= $specialtyV['id'] ?>" <?= in_array($specialtyV['id'], $oldSpl) ? 'selected' : '';  ?>><?= htmlentities($specialtyV['specialty_' . $lang->current()]); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if ($formValidator->hasError('spl')) : ?>
                                    <p class="text-danger"><?= $formValidator->getError('spl'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="password"><?= $lang('password'); ?></label>
                                <input type="Password" class="form-control" id="password" name="password" value="">
                                <?php if ($formValidator->hasError('password')) : ?>
                                    <p class="text-danger"><?= $formValidator->getError('password'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="snapchat"><?= $lang('snapchat') ?></label>
                                <input type="text" class="form-control" id="snapchat" name="snapchat" value="<?= htmlentities($formValidator->getValue('snapchat', $snapchat)); ?>">
                                <?php if ($formValidator->hasError('snapchat')) : ?>
                                    <p class="text-danger"><?= $formValidator->getError('snapchat'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="linkedin"><?= $lang('linkedin') ?></label>
                                <input type="text" class="form-control" id="linkedin" name="linkedin" value="<?= htmlentities($formValidator->getValue('linkedin', $linkedIn)); ?>">
                                <?php if ($formValidator->hasError('linkedin')) : ?>
                                    <p class="text-danger"><?= $formValidator->getError('linkedin'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="insta"><?= $lang('insta') ?></label>
                                <input type="text" class="form-control" id="insta" name="insta" value="<?= htmlentities($formValidator->getValue('insta', $insta)); ?>">
                                <?php if ($formValidator->hasError('insta')) : ?>
                                    <p class="text-danger"><?= $formValidator->getError('insta'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="youtube"><?= $lang('youtube') ?></label>
                                <input type="text" class="form-control" id="youtube" name="youtube" value="<?= htmlentities($formValidator->getValue('youtube', $youtube)); ?>">
                                <?php if ($formValidator->hasError('youtube')) : ?>
                                    <p class="text-danger"><?= $formValidator->getError('youtube'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="website"><?= $lang('website') ?></label>
                                <input type="text" class="form-control" id="website" name="website" value="<?= htmlentities($formValidator->getValue('website', $website)); ?>">
                                <?php if ($formValidator->hasError('website')) : ?>
                                    <p class="text-danger"><?= $formValidator->getError('website'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="type"><?= $lang('type') ?></label>
                                <select name="type" required id="type" class="form-control">
                                    <option <?php if ($editInfo['type'] == 'subscriber') echo 'selected' ?> value="subscriber"><?= $lang('subscriber') ?></option>
                                    <option <?php if ($editInfo['type'] == 'admin') echo 'selected' ?> value="admin"><?= $lang('admin') ?></option>
                                    <option <?php if ($editInfo['type'] == 'entity') echo 'selected' ?> value="entity"><?= $lang('entity') ?></option>
                                </select>
                                <?php if ($formValidator->hasError('type')) : ?>
                                    <p class="error"><?= $formValidator->getError('type'); ?></p>
                                <?php endif; ?>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><?= $lang('submit') ?></button>
                        </div>
                    </form>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>

<define footer_js>
    <script>
        $('.select2').select2();
        function toText(string) {
            var elm = document.createElement('div');
            elm.innerText = string;
            return elm.innerText;
        }

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
    </script>
</define>