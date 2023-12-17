<?php

use System\Core\Model;
use System\Helpers\URL;

use System\Libs\FormValidator;


/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

$formValidator = FormValidator::instance("entity");
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form action="<?= URL::current() ?>" method="post" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="form-group required">
                                <label for="username"><?= $lang('username') ?></label>
                                <input type="text" name="username" class="form-control" value="<?= htmlentities($formValidator->getValue('username')); ?>" id="username" placeholder="<?= $lang('username') ?>">
                                <?php if ($formValidator->hasError('username')) : ?>
                                    <p class="error"><?= $formValidator->getError('username'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group required">
                                <label for="name"><?= $lang('name') ?></label>
                                <input type="text" name="name" class="form-control" value="<?= htmlentities($formValidator->getValue('name')); ?>" id="name" placeholder="<?= $lang('name') ?>">
                                <?php if ($formValidator->hasError('name')) : ?>
                                    <p class="error"><?= $formValidator->getError('name'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group required">
                                <label for="email"><?= $lang('email') ?></label>
                                <input type="email" name="email" class="form-control" value="<?= htmlentities($formValidator->getValue('email')); ?>" id="email" placeholder="<?= $lang('email') ?>">
                                <?php if ($formValidator->hasError('email')) : ?>
                                    <p class="error"><?= $formValidator->getError('email'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="phone"><?= $lang('phone') ?></label>
                                <input type="text" name="phone" class="form-control" value="<?= htmlentities($formValidator->getValue('phone')); ?>" id="phone" placeholder="<?= $lang('phone') ?>">
                                <?php if ($formValidator->hasError('phone')) : ?>
                                    <p class="error"><?= $formValidator->getError('phone'); ?></p>
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