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
                                <label for="entity_commission"><?= $lang('entity_commission') ?></label>
                                <input required type="text" value="<?= htmlentities($formValidator->getValue('entity_commission', $commission['entity_commission'])); ?>" name="entity_commission" class="form-control" id="entity_commission" placeholder="<?= $lang('entity_commission') ?>">
                                <?php if ($formValidator->hasError('entity_commission')) : ?>
                                    <p class="error"><?= $formValidator->getError('entity_commission'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="advisor_commission"><?= $lang('advisor_commission') ?></label>
                                <input required type="text" value="<?= htmlentities($formValidator->getValue('advisor_commission', $commission['advisor_commission'])); ?>" name="advisor_commission" class="form-control" id="advisor_commission" placeholder="<?= $lang('advisor_commission') ?>">
                                <?php if ($formValidator->hasError('advisor_commission')) : ?>
                                    <p class="error"><?= $formValidator->getError('advisor_commission'); ?></p>
                                <?php endif; ?>
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
    </script>
</define>