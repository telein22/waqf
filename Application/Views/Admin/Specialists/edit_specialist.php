<?php

use System\Core\Model;
use System\Helpers\URL;

use System\Libs\FormValidator;

$formValidator = FormValidator::instance("specialty");

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
                                <label for="vat"><?= $lang('specialty_en_name') ?></label>
                                <input type="text" value="<?= htmlentities($formValidator->getValue('specialty_en', $specialInfo['specialty_en'])); ?>" name="specialty_en" class="form-control" id="specialty_en" placeholder="Specialty En Name">
                                <?php if ( $formValidator->hasError('specialty_en') ): ?>
                                    <p class="error"><?= $formValidator->getError('specialty_en'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="vat"><?= $lang('specialty_ar_name') ?></label>
                                <input type="text" value="<?= htmlentities($formValidator->getValue('specialty_ar', $specialInfo['specialty_ar'])); ?>" name="specialty_ar" class="form-control" id="specialty_ar" placeholder="Specialty Ar Name">
                                <?php if ( $formValidator->hasError('specialty_ar') ): ?>
                                    <p class="error"><?= $formValidator->getError('specialty_ar'); ?></p>
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