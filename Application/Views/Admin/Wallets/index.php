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
                        <div class="filter-right"><a href="<?= URL::full("{$prefix}/wallets-csv") ?>" target="_blank" class="btn btn-primary"><?= $lang('export_csv') ?></a></div>
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th><?= $lang('id') ?></th>
                                <th><?= $lang('user') ?></th>
                                <th><?= $lang('balance') ?></th>
                                <th><?= $lang('bank_info') ?></th>
                                <th><?= $lang('action') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($wallets as $wallet) : ?>
                                <tr>
                                    <td><?= $wallet['id'] ?></td>
                                    <td><a href="<?= URL::full('profile/' . $wallet['user_id']) ?>"><?= $wallet['name'] . ' (' . $wallet['email'] . ')' ?></a></td>
                                    <td><?= htmlentities($wallet['balance']) ?></td>
                                    <td><?= htmlentities($wallet['bank_info']) ?></td>
                                    <td>
                                        <a class="btn primary-btn" href="<?= URL::full('admin/wallets/' . $wallet['id']) ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th><?= $lang('id') ?></th>
                                <th><?= $lang('user') ?></th>
                                <th><?= $lang('balance') ?></th>
                                <th><?= $lang('bank_info') ?></th>
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