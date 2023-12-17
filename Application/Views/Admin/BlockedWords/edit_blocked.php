<?php

use System\Core\Model;
use System\Helpers\URL;

use System\Libs\FormValidator;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');
$formValidator = FormValidator::instance("blockedWords");
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
                                <label for="vat"><?= $lang('word') ?></label>
                                <input type="text" name="word" value="<?= htmlentities($formValidator->getValue('word', $blockedInfo['word'])); ?>" class="form-control" id="word" placeholder="<?= $lang('word') ?>">
                                <?php if ( $formValidator->hasError('word') ): ?>
                                    <p class="error"><?= $formValidator->getError('word'); ?></p>
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