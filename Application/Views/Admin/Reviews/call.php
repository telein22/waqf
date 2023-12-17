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
                                    <th><?= $lang('user'); ?></th>
                                    <th><?= $lang('call') ?></th>
                                    <th><?= $lang('review') ?></th>
                                    <th><?= $lang('action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reviews as $review) : ?>
                                    <tr>
                                        <td>
                                            <a target="_blank" href="<?= URL::full('profile/' . $review['entity_owner_id']) ?>">
                                                <?= htmlentities($review['user']['name']) .' / ' . htmlentities($review['user']['email'])?>
                                            </a>
                                        </td>
                                        <td>
                                            <?= htmlentities($review['entity_details']['name']) ?>
                                        </td>
                                        <td>
                                            <?= number_format(htmlentities($review['avg_star'],0)) ?>
                                        </td>
                                        <td>
                                            <a class="btn" href="<?= URL::full('admin/view-workshop-reviews/' . (int) $review['entity_id']) ?>">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th><?= $lang('user'); ?></th>
                                    <th><?= $lang('call') ?></th>
                                    <th><?= $lang('review') ?></th>
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
        $(function() {
            $("#table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            })
        });
    </script>
</define>