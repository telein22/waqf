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
                        <div class="filter-right"><a href="<?= URL::full("{$prefix}/users-csv") ?>" target="_blank" class="btn btn-primary"><?= $lang('export_csv') ?></a></div>
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?= $lang('id') ?></th>
                                    <th><?= $lang('email_verification') ?></th>
                                    <th><?= $lang('account_verification') ?></th>
                                    <th><?= $lang('name') ?></th>
                                    <th><?= $lang('email') ?></th>
                                    <th><?= $lang('username') ?></th>
                                    <?php if ($userInfo['type'] == User::TYPE_ADMIN): ?>
                                        <th><?= $lang('type') ?></th>
                                    <?php endif; ?>
                                    <th><?= $lang('joined_at') ?></th>
                                    <th><?= $lang('last_active') ?></th>
                                    <th><?= $lang('action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allUsers as $user) : ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td>
                                            <input class="verification" value="<?= $user['id'] ?>" type="checkbox" <?php if ($user['email_verified']) echo 'checked' ?>>
                                        </td>
                                        <td>
                                            <input class="account-verification" value="<?= $user['id'] ?>" type="checkbox" <?php if ($user['account_verified']) echo 'checked' ?>>
                                        </td>
                                        <td><?= htmlentities($user['name']) ?></td>
                                        <td><?= htmlentities($user['email']) ?></td>
                                        <td><?= htmlentities($user['username']) ?></td>
                                        <?php if ($userInfo['type'] == User::TYPE_ADMIN): ?>
                                            <td><?= htmlentities($user['type']) ?></td>
                                        <?php endif; ?>
                                        <td><?= date('d-m-Y H:i', $user['joined_at']) ?></td>
                                        <td><?= date('d-m-Y H:i', $user['lastactive']) ?></td>
                                        <td>
                                            <a target="_blank" class="btn primary-btn" href="<?= URL::full('profile/' . $user['id']) ?>">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <?php if ($userInfo['type'] == User::TYPE_ENTITY & $userInfo['id'] != $user['id']) : ?>
                                                <a class="btn primary-btn cancel-membership" data-id="<?= $user['id'] ?>" href="#!">
                                                    <i class="fas fa-ban"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($prefix == User::TYPE_ADMIN): ?>
                                                <a target="_blank" class="btn primary-btn" href="<?= URL::full('admin/edit-user/' . $user['id']) ?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <?php if (!$user['suspended']) : ?>
                                                    <a class="btn primary-btn block" data-id="<?= $user['id'] ?>" href="#!">
                                                        <?= $lang('block') ?>
                                                    </a>
                                                <?php else : ?>
                                                    <a class="btn primary-btn unblock" data-id="<?= $user['id'] ?>" href="#!">
                                                        <?= $lang('unblock') ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th><?= $lang('id') ?></th>
                                    <th><?= $lang('email_verification') ?></th>
                                    <th><?= $lang('account_verification') ?></th>
                                    <th><?= $lang('name') ?></th>
                                    <th><?= $lang('email') ?></th>
                                    <th><?= $lang('username') ?></th>
                                    <?php if ($userInfo['type'] == User::TYPE_ADMIN): ?>
                                        <th><?= $lang('type') ?></th>
                                    <?php endif; ?>
                                    <th><?= $lang('joined_at') ?></th>
                                    <th><?= $lang('last_active') ?></th>
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
        $('.cancel-membership').on('click', function(e) {
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