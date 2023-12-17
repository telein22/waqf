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
                                    <th><?= $lang('id'); ?></th>
                                    <th><?= $lang('word') ?></th>
                                    <th><?= $lang('action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allWords as $word) : ?>
                                    <tr>
                                        <td><?= $word['id'] ?></td>
                                        <td><?= htmlentities($word['word']) ?></td>
                                        <td>
                                            <a class="btn" href="<?= URL::full('admin/edit-blocked-words/' . (int) $word['id']) ?>">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a class="btn deleteBlockedWords" href="#!" data-id="<?= $word['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                <th><?= $lang('id'); ?></th>
                                    <th><?= $lang('word') ?></th>
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
        $(".deleteBlockedWords").on('click', function(e) {
            var id = $(this).data('id');
            if (confirm('<?= $lang('are_you_sure') ?>')) {
                $.ajax({
                    url: '<?= URL::full('/ajax/admin/delete-blocked-words'); ?>',
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