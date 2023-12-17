<?php

use System\Core\Model;
use System\Helpers\URL;

use System\Libs\FormValidator;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

$formValidator = FormValidator::instance("upload_blocked");
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
                                <label for="vat"><?= $lang('csv') ?></label>
                                <input type="file" name="csv" class="form-control-file" id="csv" placeholder="<?= $lang('csv') ?>">
                                <?php if ( $formValidator->hasError('csv') ): ?>
                                    <p class="error"><?= $formValidator->getError('csv'); ?></p>
                                <?php endif; ?>
                            </div>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><?= $lang('submit') ?></button>
                            <a class="btn btn-secondary" download="example" href="<?= URL::asset('Application/Assets/Admin/csv/example-blocked-words.csv') ?>"><?= $lang('example') ?></a>
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