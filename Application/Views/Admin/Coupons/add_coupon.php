<?php

use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Workshop;
use System\Core\Model;
use System\Helpers\URL;

use System\Libs\FormValidator;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');
$formValidator = FormValidator::instance("coupon");
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
                            <div class="form-group required">
                                <label for="vat"><?= $lang('coupon_code') ?></label>
                                <input type="text" name="code" class="form-control" value="<?= htmlentities($formValidator->getValue('code')); ?>" id="code" placeholder="<?= $lang('coupon_code') ?>">
                                <?php if ( $formValidator->hasError('code') ): ?>
                                    <p class="error"><?= $formValidator->getError('code'); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if($userInfo['type'] == \Application\Models\User::TYPE_ADMIN) : ?>
                                <div class="form-group">
                                    <label for="user"><?= $lang('user') ?></label>
                                    <select class="form-control select2" id="user" name="user_id">
                                        <option value="0"><?= $lang('select_a_user') ?></option>
                                        <?php foreach ($users as $user) : ?>
                                            <option value="<?= $user['id'] ?>"><?= htmlentities($user['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="mr-2" for="workshop"><?= $lang('workshop') ?></label>
                                    <select class="form-control select2" id="workshop" name="workshop_id">
                                        <option value="0"><?= $lang('select_workshop') ?></option>
                                        <?php foreach ($workshops as $workshop) : ?>
                                            <option value="<?= $workshop['id'] ?>"><?= $workshop['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group required">
                                    <label for="type"><?= $lang('type') ?></label>
                                    <select name="type" id="type" class="form-control">
                                        <option <?php if($formValidator->getValue('amount') == 'fixed') echo 'selected' ?> value="fixed"><?= $lang('fixed') ?></option>
                                        <option <?php if($formValidator->getValue('amount') == '1') echo 'selected' ?> value="1"><?= $lang('percentage') ?></option>
                                    </select>
                                    <?php if ( $formValidator->hasError('type') ): ?>
                                        <p class="error"><?= $formValidator->getError('type'); ?></p>
                                    <?php endif; ?>
                                </div>
                        <?php endif ?>

                            <div class="form-group required">
                                <label for="expiry"><?= $lang('expiry_date') ?></label>
                                <input type="date" name="expiry" class="form-control" value="<?= date('Y-m-d', strtotime(htmlentities($formValidator->getValue('expiry', date('Y-m-d'))))) ; ?>" id="expiry" placeholder="<?= $lang('expiry_date') ?>">
                                <?php if ( $formValidator->hasError('expiry') ): ?>
                                    <p class="error"><?= $formValidator->getError('expiry'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group required">
                                <label for="max_use"><?= $lang('maximum_use') ?></label>
                                <input type=" number" name="max_use" class="form-control" value=" <?= htmlentities($formValidator->getValue('max_use')); ?>" id="max_use" placeholder="<?= $lang('maximum_use') ?>">
                                <?php if ( $formValidator->hasError('max_use') ): ?>
                                    <p class="error"><?= $formValidator->getError('max_use'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group required">
                                <?php if($userInfo['type'] == \Application\Models\User::TYPE_ADMIN) : ?>
                                <label for="vat" class="amount-label"><?= $lang('fixed_amount') ?></label>
                                <?php else: ?>
                                    <label for="vat" class="amount-label"><?= $lang('percentage_amount') ?></label>
                                <?php endif; ?>
                                <input type="number" name="amount" class="form-control" value=" <?= htmlentities($formValidator->getValue('amount')); ?>" id="amount" placeholder="<?= $lang('amount') ?>">
                                <?php if ( $formValidator->hasError('amount') ): ?>
                                    <p class="error"><?= $formValidator->getError('amount'); ?></p>
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
        $("#type").on('change', function(e) {
            var val = parseInt($(this).val());

            if( val === 1 ) {
                $(".amount-label").text("<?= $lang("percentage_amount") ?>");
            } else {
                $(".amount-label").text("<?= $lang("fixed_amount") ?>");
            }
        })

        $(function() {
            $("#table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            })
        });
    </script>
</define>