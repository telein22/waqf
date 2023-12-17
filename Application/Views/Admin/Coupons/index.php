<?php

use System\Core\Model;
use System\Helpers\URL;
use Application\Models\User;
use Application\Models\Language;

$prefix = $userInfo['type'] == User::TYPE_ADMIN ? 'admin' : 'entities';
$lang = Model::get(Language::class);
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
                    <div class="card-body">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?= $lang('coupon_code') ?></th>
                                    <th><?= $lang('user') ?></th>
                                    <th><?= $lang('workshop') ?></th>
                                    <th><?= $lang('type') ?></th>
                                    <th><?= $lang('maximum_use') ?></th>
                                    <th><?= $lang('expiry_date') ?></th>
                                    <th><?= $lang('amount') ?></th>
                                    <th><?= $lang('created_by') ?></th>
                                    <th><?= $lang('action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($coupons as $coupon) : ?>
                                    <?php
                                    $type = $coupon['type'] == 0 ? $lang('fixed') : $lang('percentage');
                                    ?>
                                    <tr>
                                        <td><?= htmlentities($coupon['code']) ?></td>
                                        <td><?= $coupon['username'] ?? '' ?></td>
                                        <td><?= $coupon['entity_name'] ?? '' ?></td>
                                        <td><?= $type ?></td>
                                        <td><?= htmlentities($coupon['max_use']) ?></td>
                                        <td><?= htmlentities($coupon['expiry']) ?></td>
                                        <td><?= htmlentities($coupon['amount']) ?></td>
                                        <td><?= isset($coupon['created_by'][1]) ? $coupon['created_by'][1] : '' ?></td>
                                        <td>
                                            <button class="btn delete" data-id="<?= $coupon['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <a class="btn" href="<?= URL::full("{$prefix}/coupon-edit/{$coupon['id']}") ?>">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a class="btn" href="<?= URL::full("{$prefix}/coupon-uses/{$coupon['code']}" ) ?>">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th><?= $lang('coupon_code') ?></th>
                                    <th><?= $lang('user') ?></th>
                                    <th><?= $lang('workshop') ?></th>
                                    <th><?= $lang('type') ?></th>
                                    <th><?= $lang('maximum_use') ?></th>
                                    <th><?= $lang('expiry_date') ?></th>
                                    <th><?= $lang('amount') ?></th>
                                    <th><?= $lang('created_by') ?></th>
                                    <th><?= $lang('action') ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>

<define footer_js>
    <script>
        $(".delete").on('click', function(e) {
            var id = $(this).data('id');
            if (confirm('<?= $lang('are_you_sure') ?>')) {
                $.ajax({
                    url: '<?= URL::full('/ajax/admin/delete-coupon'); ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    accepts: 'JSON',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        window.location.reload();
                    },
                    complete: function() {

                    }
                });
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