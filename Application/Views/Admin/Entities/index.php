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
                                <th><?= $lang('associates') ?></th>
                                <th><?= $lang('created_at') ?></th>
                                <th><?= $lang('action') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($entities as $entity) : ?>
                                <tr>
                                    <td><?= htmlentities($entity['name']); ?></td>
                                    <td>
                                        <?= $entity['associates_count'] ?>
                                    </td>
                                    <td><?= date('d-m-Y H:i', $entity['joined_at']) ?></td>
                                    <td>
                                        <a class="btn" href="<?= URL::full("admin/entities/{$entity['id']}/associates" ) ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th><?= $lang('name') ?></th>
                                <th><?= $lang('associates') ?></th>
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
                "order": [[ 0, "desc" ]],
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            })
        });
    </script>
</define>