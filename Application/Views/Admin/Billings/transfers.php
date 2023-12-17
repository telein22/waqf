<?php

use Application\Helpers\DateHelper;
use Application\Models\Call;
use Application\Models\Conversation;
use Application\Models\Order;
use Application\Models\Workshop;
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
                    <div class="card-header ">
                        <form action="<?= URL::current() ?>">
                            <div class="filter-wrapper">
                                <div class="col-lg-3  custom-flex-space">
                                    <label class="mr-2" for="from"><?= $lang('from') ?></label>
                                    <input type="date" required value="<?= !empty($from) && $from != '' ? date('Y-m-d', $from) : '' ?>" class="form-control" id="from" name="from">
                                </div>
                                <div class="col-lg-3  custom-flex-space">
                                    <label class="mr-2" for="to"><?= $lang('to') ?></label>
                                    <input type="date" required value="<?= !empty($to) && $to != '' ? date('Y-m-d', $to) : '' ?>" class="form-control" id="to" name="to">
                                </div>
                                <div class="align-items-center  mobile-mt-3">
                                    <button type="submit" class="btn-sm btn btn-primary mt-0"><?= $lang('filter') ?></button>
                                    <!-- <a href="<?= URL::current() ?>" class="btn-sm btn btn-secondary">Reset Filter</a> -->
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- <div class="card-header">
                        <h3 class="card-title">DataTable with default features</h3>
                    </div> -->
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?= $lang('id') ?></th>
                                    <th><?= $lang('status') ?></th>
                                    <th><?= $lang('amount') ?></th>
                                    <th><?= $lang('transferred_to') ?></th>
                                    <td><?= $lang('created_at') ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transfers as $transfer) : ?>
                                    <tr>
                                        <td>
                                            <?= $transfer['id'] ?>
                                        </td>
                                        <td>
                                            <?= $lang(htmlentities($transfer['status'])) ?>
                                        </td>
                                        <td>
                                            <?= $lang('c_price', ['p' => $transfer['transfer_amount']]) ?>
                                        </td>
                                        <td>
                                            <?= $transfer['receiver']['name'] ?>
                                        </td>
                                        <td>
                                            <?= DateHelper::butify($transfer['created_at']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th><?= $lang('id') ?></th>
                                    <th><?= $lang('status') ?></th>
                                    <th><?= $lang('amount') ?></th>
                                    <th><?= $lang('transfered_to') ?></th>
                                    <td><?= $lang('created_at') ?></td>
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