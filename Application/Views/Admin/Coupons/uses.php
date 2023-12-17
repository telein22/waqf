<?php

use Application\Helpers\DateHelper;
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
                    <div class="card-header">
                        <h3 class="card-title"><?= $lang('total_use_count', array('count' => count($orders))) ?></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?= $lang('order_id') ?></th>
                                    <th><?= $lang('user') ?></th>
                                    <th><?= $lang('type') ?></th>
                                    <th><?= $lang('date') ?></th>
                                    <th><?= $lang('status') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order) : ?>
                                    <tr>
                                        <td><?= htmlentities($order['id']) ?></td>
                                        <td>
                                            <a target="_blank" href="<?= URL::full('profile/' . $order['user']['id']) ?>">
                                                <?= htmlentities($order['user']['name'] . '/' . $order['user']['email']) ?>
                                            </a>
                                        </td>
                                        <td><?= $lang($order['entity_type']) ?></td>
                                        <td><?= DateHelper::butify($order['created_at']) ?></td>
                                        <td><?= $lang($order['status']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th><?= $lang('order_id') ?></th>
                                    <th><?= $lang('user') ?></th>
                                    <th><?= $lang('type') ?></th>
                                    <th><?= $lang('date') ?></th>
                                    <th><?= $lang('status') ?></th>
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
                "order": [[ 0, "desc" ]],
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            })
        });
    </script>
</define>