<?php

use System\Core\Model;
use System\Helpers\URL;

use System\Libs\FormValidator;


/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

$formValidator = FormValidator::instance("charity");
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
                    <form action="<?= URL::current() ?>" method="post" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="vat"><?= $lang('en_name') ?></label>
                                <input type="text" name="en_name" class="form-control" value="<?= htmlentities($formValidator->getValue('en_name')); ?>" id="en_name" placeholder="<?= $lang('en_name') ?>">
                                <?php if ($formValidator->hasError('en_name')) : ?>
                                    <p class="error"><?= $formValidator->getError('en_name'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="vat"><?= $lang('ar_name') ?></label>
                                <input type="text" name="ar_name" class="form-control" value="<?= htmlentities($formValidator->getValue('ar_name')); ?>" id="ar_name" placeholder="<?= $lang('ar_name') ?>">
                                <?php if ($formValidator->hasError('ar_name')) : ?>
                                    <p class="error"><?= $formValidator->getError('ar_name'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="vat"><?= $lang('b_name') ?></label>
                                <input type="text" name="b_name" class="form-control" value="<?= htmlentities($formValidator->getValue('b_name')); ?>" id="b_name" placeholder="<?= $lang('b_name') ?>">
                                <?php if ($formValidator->hasError('b_name')) : ?>
                                    <p class="error"><?= $formValidator->getError('b_name'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="vat"><?= $lang('b_account_number') ?></label>
                                <input type="text" name="b_account_number" class="form-control" value="<?= htmlentities($formValidator->getValue('b_account_number')); ?>" id="b_account_number" placeholder="<?= $lang('b_account_number') ?>">
                                <?php if ($formValidator->hasError('b_account_number')) : ?>
                                    <p class="error"><?= $formValidator->getError('b_account_number'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="vat"><?= $lang('bank_bic_code') ?></label>
                                <input type="text" name="bank_bic_code" class="form-control" value="<?= htmlentities($formValidator->getValue('bank_bic_code')); ?>" id="bank_bic_code" placeholder="<?= $lang('bank_bic_code') ?>">
                                <?php if ($formValidator->hasError('bank_bic_code')) : ?>
                                    <p class="error"><?= $formValidator->getError('bank_bic_code'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="vat"><?= $lang('address_line_1') ?></label>
                                <textarea name="address_line_1" class="form-control" id="address_line_1" placeholder="<?= $lang('address_line_1') ?>"><?= htmlentities($formValidator->getValue('address_line_1')); ?></textarea>
                                <?php if ($formValidator->hasError('address_line_1')) : ?>
                                    <p class="error"><?= $formValidator->getError('address_line_1'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="vat"><?= $lang('address_line_2') ?></label>
                                <textarea name="address_line_2" class="form-control" id="address_line_2" placeholder="<?= $lang('address_line_2') ?>"><?= htmlentities($formValidator->getValue('address_line_2')); ?></textarea>
                                <?php if ($formValidator->hasError('address_line_2')) : ?>
                                    <p class="error"><?= $formValidator->getError('address_line_2'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="vat"><?= $lang('address_line_3') ?></label>
                                <textarea name="address_line_3" class="form-control" id="address_line_3" placeholder="<?= $lang('address_line_3') ?>"><?= htmlentities($formValidator->getValue('address_line_3')); ?></textarea>
                                <?php if ($formValidator->hasError('address_line_3')) : ?>
                                    <p class="error"><?= $formValidator->getError('address_line_3'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="vat"><?= $lang('image') ?></label>
                                <input type="file" name="img" class="form-control-file mb-3" id="img">
                                <?php if ($formValidator->hasError('img')) : ?>
                                    <p class="error"><?= $formValidator->getError('img'); ?></p>
                                <?php endif; ?>
                            </div>
                            <!-- /.card-body -->

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
        $(function() {
            $("#table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            })
        });
    </script>
</define>