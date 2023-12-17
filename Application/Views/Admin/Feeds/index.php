<?php

use System\Core\Model;

/**
 * @var \Application\Models\Language
 */
$lang = Model::get('\Application\Models\Language');

use System\Helpers\URL;
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form action="<?= URL::current() ?>">
                        <div class="card-header filter-wrapper">
                            <div class="col-lg-3 col-sm-12 custom-flex-space">
                                <label class="mr-2" for="from"><?= $lang('from') ?></label>
                                <input type="date" required value="<?= !empty($from) && $from != '' ? date('Y-m-d', $from) : '' ?>" class="form-control" id="from" name="from">
                            </div>
                            <div class="col-lg-3 col-sm-12 custom-flex-space">
                                <label class="mr-2" for="to"><?= $lang('to') ?></label>
                                <input type="date" required value="<?= !empty($to) && $to != '' ? date('Y-m-d', $to) : '' ?>" class="form-control" id="to" name="to">
                            </div>
                            <div class="col-sm-12 align-items-center mobile-mt-3">
                                <button type="submit" class="btn-sm btn btn-primary mt-0"><?= $lang('filter') ?></button>
                                <!-- <a href="<?= URL::current() ?>" class="btn-sm btn btn-secondary">Reset Filter</a> -->
                            </div>
                        </div>
                    </form>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th><?= $lang('id') ?></th>
                                        <th><?= $lang('username') ?></th>
                                        <th><?= $lang('email') ?></th>
                                        <th><?= $lang('text') ?></th>
                                        <th><?= $lang('status') ?></th>
                                        <th><?= $lang('created_at') ?></th>
                                        <th><?= $lang('action') ?></th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?= $lang('feed') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
</section>

<define footer_js>
    <script>
        function viewFeed() {
            $(".view-feed").on('click', function(e) {
                e.preventDefault();

                var id = $(this).data('id');
                var url = "<?= URL::full('feed/') ?>" + id

                $("#modal").modal('show');

                $(".modal-body").html(
                    "<iframe class='feed-frame' src=" + url + "></iframe>"
                );
            })
        }

        function actions() {
            $(".feed-hide").on('click', function(e) {
                var id = $(this).data('id');
                if (confirm('<?= $lang('are_you_sure') ?>')) {
                    $.ajax({
                        url: '<?= URL::full('/ajax/admin/hide-blocked-feed-word'); ?>',
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

            $(".feed-show").on('click', function(e) {
                var id = $(this).data('id');
                if (confirm('<?= $lang('are_you_sure') ?>')) {
                    $.ajax({
                        url: '<?= URL::full('/ajax/admin/show-blocked-feed-word'); ?>',
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
        }


        $('#table').DataTable({
            "processing": true,
            "searching": true,
            "serverSide": true,
            "ordering": false,
            "ajax": {
                url: "<?= URL::full('/ajax/admin/data-table/feeds?from=' . $from . '&to=' . $to); ?>",
            },
            "initComplete": function(settings, json) {
                viewFeed();
                actions();
            },
            "drawCallback": function(settings) {
                viewFeed();
            },
            columns: [{
                    data: 'id'
                },
                {
                    data: 'username'
                },
                {
                    data: 'email'
                },
                {
                    data: 'text'
                },
                {
                    data: 'status'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'action'
                }
            ]
        });
    </script>
</define>