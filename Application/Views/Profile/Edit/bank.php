<?php

use System\Core\Model;
use System\Helpers\URL;
use System\Libs\FormValidator;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

$formValidator = FormValidator::instance("edit_bank");

?>
<div class="tab-pane active" id="chang-pwd" role="tabpanel">
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title"><?= $lang('bank_details'); ?></h4>
            </div>
        </div>
        <div class="iq-card-body">
            <form method="POST" action="<?= URL::current() ?>">
                <div class="form-group">
                    <label for="b1"><?= $lang('enter_beneficiary_name') ?></label>
                    <input type="text" class="form-control" id="b1" name="b1" value="<?= htmlentities($formValidator->getValue('b1', $bank1)); ?>">
                    <?php if ($formValidator->hasError('b1')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('b1'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="b2"><?= $lang('enter_account_number') ?></label>
                    <input type="text" class="form-control" id="b2" name="b2" value="<?= htmlentities($formValidator->getValue('b2', $bank2)); ?>">
                    <?php if ($formValidator->hasError('b2')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('b2'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="b3"><?= $lang('enter_bic_code') ?></label>
                    <input type="text" class="form-control" id="b3" name="b3" value="<?= htmlentities($formValidator->getValue('b3', $bank3)); ?>">
                    <?php if ($formValidator->hasError('b3')) : ?>
                        <p class="text-danger"><?= $formValidator->getError('b3'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="text-ar-right">
                    <button type="submit" class="btn btn-primary mr-2"><?= $lang('submit'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>