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
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th><?= $lang('name_ar') ?></th>
                                <th><?= $lang('name_en') ?></th>
                                <th><?= $lang('action') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($profitProceeds as $profitProceed) : ?>
                                <tr>
                                    <td><?= $profitProceed['name_ar'] ?></td>
                                    <td><?= $profitProceed['name_en'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th><?= $lang('name_ar') ?></th>
                                <th><?= $lang('name_en') ?></th>
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
        $(function () {
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