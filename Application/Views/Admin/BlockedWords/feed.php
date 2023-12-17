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
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th><?= $lang('username') ?></th>
                                        <th><?= $lang('email') ?></th>
                                        <th><?= $lang('text') ?></th>
                                        <th><?= $lang('status') ?></th>
                                        <th><?= $lang('blocked_words') ?></th>
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
        
       function actions () {
            $(".feed-hide").on('click', function(e) {
                var id = $(this).data('id');
                if (confirm('<?php $lang('are_you_sure') ?>')) {
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
                if (confirm('<?php $lang('are_you_sure') ?>')) {
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
                url: "<?= URL::full('/ajax/admin/data-table/feeds-with-blocked?from=' . $from . '&to=' . $to); ?>",
            },
            "initComplete": function(settings, json) {
                viewFeed();
                actions();
            },
            "drawCallback": function(settings) {
                viewFeed();
                // actions();
            },
            columns: [{
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
                    data: 'word'
                },
                {
                    data: 'action'
                }
            ]
        });
    </script>
</define>