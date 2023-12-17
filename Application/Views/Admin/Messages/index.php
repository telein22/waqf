<?php

use Application\Helpers\ConversationHelper;
use Application\Models\Conversation;
use System\Core\Model;
use System\Helpers\URL;

/**
 * @var \System\Models\Language
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
                    <div class="card-body">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?= $lang('id') ?></th>
                                    <th><?= $lang('owner') ?></th>
                                    <th><?= $lang('creator') ?></th>
                                    <th><?= $lang('first_message') ?></th>
                                    <th><?= $lang('is_expired') ?></th>
                                    <th><?= $lang('status') ?></th>
                                    <th><?= $lang('created_at') ?></th>
                                    <th><?= $lang('action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($convos as $conv) : ?>
                                    <tr>
                                        <td><?= $conv['id'] ?></td>
                                        <td>
                                            <a target="_blank" href="<?= URL::full('profile/' . $conv['owner']['id']) ?>">
                                                <?= htmlentities($conv['owner']['name']) . ' / ' . htmlentities($conv['owner']['email']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a target="_blank" href="<?= URL::full('profile/' . $conv['creator']['id']) ?>">
                                                <?= htmlentities($conv['creator']['name']) . ' / ' . htmlentities($conv['creator']['email']) ?>
                                            </a>
                                        </td>
                                        <td><?= $conv['first_message'] ?></td>
                                        <td>
                                            <?php
                                            if ($conv['status'] == Conversation::STATUS_CURRENT && ConversationHelper::isExpired($conv['created_at']) ) {
                                                echo $lang('yes');
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <td><?= $lang($conv['status']) ?></td>
                                        <td><span style="display: none;"><?= $conv['created_at'] ?></span><?= date('d-m-Y H:i', $conv['created_at']) ?></td>
                                        <td>
                                            <a class="btn primary-btn show-conversation" href="#!" data-id="<?= $conv['id'] ?>">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>                            
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?= $lang('conversation') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<define footer_js>
    <script>
        $(".show-conversation").on('click', function(e) {
            e.preventDefault();

            var id = $(this).data('id');

            $.ajax({
                url: '<?= URL::full('/ajax/admin/show-conversation'); ?>',
                type: 'POST',
                dataType: 'JSON',
                accepts: 'JSON',
                data: {
                    id: id
                },
                success: function(data) {
                    $("#modal").modal('show');

                    $(".modal-body").html(data.payload);
                },
                complete: function() {

                }
            });
        })

        $(".block").on('click', function(e) {
            var id = $(this).data('id');
            if (confirm('Are you sure?')) {
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
            var id = $(this).data('id');
            if (confirm('Are you sure?')) {
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