<?php

use Application\Models\User;
use System\Core\Model;
use System\Helpers\URL;
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
                        <div class="filter-right"><a href="<?= URL::full("{$prefix}/wallets/{$wallet_id}/csv") ?>" target="_blank" class="btn btn-primary"><?= $lang('export_csv') ?></a></div>
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th><?= $lang('id') ?></th>
                                <th><?= $lang('service_type') ?></th>
                                <th><?= $lang('service_id') ?></th>
                                <th><?= $lang('beneficiary') ?></th>
                                <th><?= $lang('transaction_amount') ?></th>
                                <th><?= $lang('created_at') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($transactions as $transaction) : ?>
                                <tr>
                                    <td><?= $transaction['id'] ?></td>
                                    <td><?= htmlentities($transaction['entity_type']) ?></td>
                                    <td><?= htmlentities($transaction['entity_id']) ?></td>
                                    <td><?= htmlentities($transaction['beneficiary']) ?></td>
                                    <td><?= htmlentities($transaction['amount']) ?></td>
                                    <td><?= htmlentities($transaction['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th><?= $lang('id') ?></th>
                                <th><?= $lang('service_type') ?></th>
                                <th><?= $lang('service_id') ?></th>
                                <th><?= $lang('beneficiary') ?></th>
                                <th><?= $lang('transaction_amount') ?></th>
                                <th><?= $lang('created_at') ?></th>
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
        $('#cancel-membership').on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            if (confirm('<?= $lang('confirm_membership_cancellation') ?>')) {
                window.location.href = `/entities/cancellation-membership/${id}`;
            }

        });

        $(".block").on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            if (confirm('<?= $lang('are_you_sure') ?>')) {
                $.ajax({
                    url: '<?= URL::full('/ajax/admin/block-user'); ?>',
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

        $(".unblock").on('click', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            if (confirm('<?= $lang('are_you_sure') ?>')) {
                $.ajax({
                    url: '<?= URL::full('/ajax/admin/unblock-user'); ?>',
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

        $(".account-verification").on('click', function(e) {
            var id = $(this).val();
            if (confirm('<?= $lang('are_you_sure') ?>')) {
                $.ajax({
                    url: '<?= URL::full('/ajax/admin/user-verification'); ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    accepts: 'JSON',
                    data: {
                        userId: id,
                        type: 'account'
                    },
                    success: function(data) {
                        window.location.reload();
                    },
                    complete: function() {

                    }
                });
            }
        })

        $(".verification").on('click', function(e) {
            var id = $(this).val();
            if (confirm('<?= $lang('are_you_sure') ?>')) {
                $.ajax({
                    url: '<?= URL::full('/ajax/admin/user-verification'); ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    accepts: 'JSON',
                    data: {
                        userId: id,
                        type: 'email'
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
                "order": [
                    [0, "desc"]
                ],
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            })
        });
    </script>
</define>