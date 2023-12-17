<?php

use System\Core\Model;
use System\Helpers\URL;

/**
 * @var \Application\Models\Language
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
                                    <th><?= $lang('name') ?></th>
                                    <th><?= $lang('image') ?></th>
                                    <th><?= $lang('created_at') ?></th>
                                    <th><?= $lang('action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($charities as $charity) : ?>
                                    <tr>
                                        <td><?= htmlentities($charity['en_name']) . '/' . htmlentities($charity['ar_name']); ?></td>
                                        <td>
                                            <a target="_blank" href="<?= URL::asset('Application/Uploads/' . $charity['img']) ?>"><?= $lang('view_image') ?></a>
                                        </td>
                                        <td><?= date('d-m-Y H:i', $charity['created_at']) ?></td>
                                        <td>
                                            <a class="btn" href="<?= URL::full('admin/edit-charity/' . (int) $charity['id']) ?>">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th><?= $lang('name') ?></th>
                                    <th><?= $lang('image') ?></th>
                                    <th><?= $lang('created_at') ?></th>
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
            if (confirm('Are you sure?')) {
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
                "order": [[ 2, "desc" ]],
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            })
        });
    </script>
</define>