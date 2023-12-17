<?php

use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');
$formValidator = FormValidator::instance("edit_pwd");

?>
<div class="tab-pane active" id="chang-pwd" role="tabpanel">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title"><?= $lang('change_password'); ?></h4>
            </div>
        </div>
        <div class="iq-card-body">
            <form method="POST" action="<?= URL::current() ?>">
                <div class="form-group">
                    <label for="cpass"><?= $lang('current_password'); ?></label>
                    <!-- <a href="javascripe:void();" class="float-right">Forgot Password</a> -->
                    <input type="Password" class="form-control" id="cpass" name="cpass" value="">
                    <?php if ($formValidator->hasError('cpass')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('cpass'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="npass"><?= $lang('new_password'); ?></label>
                    <input type="Password" class="form-control" id="npass" name="npass" value="">
                    <?php if ($formValidator->hasError('npass')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('npass'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="vpass"><?= $lang('confirm_password'); ?></label>
                    <input type="Password" class="form-control" id="vpass" name="vpass" value="">
                    <?php if ($formValidator->hasError('vpass')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('vpass'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="text-ar-right">
                    <button type="submit" class="btn btn-primary mr-2"><?= $lang('submit'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>