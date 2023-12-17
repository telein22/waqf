<?php

use System\Core\Model;
use System\Helpers\URL;

use System\Libs\FormValidator;
/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

$formValidator = FormValidator::instance("settings");
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
                                <label for="vat"><?= $lang('vat_full') ?></label>
                                <input type="number" value="<?= $vat ?>" name="vat" class="form-control" id="vat" placeholder="<?= $lang('vat_full') ?>">
                                <?php if ( $formValidator->hasError('vat') ): ?>
                                    <p class="error"><?= $formValidator->getError('vat'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="platform_fees"><?= $lang('platform_fees') ?></label>
                                <input type="number" value="<?= $platform_fees ?>" name="platform_fees" class="form-control" id="platform_fees" placeholder="<?= $lang('platform_fees') ?>">
                                <?php if ( $formValidator->hasError('platform_fees') ): ?>
                                    <p class="error"><?= $formValidator->getError('platform_fees'); ?></p>
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
        $(".verification").on('click', function(e) {
            var id = $(this).val();
            $.ajax({
                url: '<?= URL::full('/ajax/admin/user-verification'); ?>',
                type: 'POST',
                dataType: 'JSON',
                accepts: 'JSON',
                data: {
                    userId: id
                },
                success: function(data) {

                },
                complete: function() {

                }
            });
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