<?php

use Application\Controllers\Ajax\Order;
use Application\Helpers\DateHelper;
use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;
use Application\Helpers\AppHelper;
use Application\Models\WithdrawalRequest;

$lang = Model::get(Language::class);
$minimumWithdrawalAmount = AppHelper::getMinimumWithdrawalAmount();
?>

<div class="container">
    <div class="row mt-5">
        <div class="col-md-6">
            <div class="iq-card">
                <div class="iq-card-body bg-primary money-card">
                    <h3 class="font-size-16 font-weight-bold"><?= $lang('wallet_balance') ?></h3>
                    <span class="small font-size-12"><?php echo $lang('after_deducting_service_fee'); ?></span>
                    <p class="font-size-32 mb-0"><?= $lang('c_price_earning', ['p' => number_format($wallet['balance'], 2)]) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="iq-card">
                <div class="iq-card-body bg-info money-card">
                    <h3 class="font-size-16 font-weight-bold"><?= $lang('total_pending') ?></h3>
                    <span class="small font-size-12"><?php echo $lang('after_deducting_service_fee'); ?></span>
                    <p class="font-size-32 mb-0"><?= $lang('c_price_earning', ['p' => number_format($currentPending, 2)]) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="iq-card">
                <div class="iq-card-body money-card" style="background-color: #8991d4">
                    <h3 class="font-size-16 font-weight-bold"><?= $lang('total') ?></h3>
                    <span class="small font-size-12"><?php echo $lang('after_deducting_service_fee'); ?></span>
                    <p class="font-size-32 mb-0"><?= $lang('c_price_earning', ['p' => number_format($totalAmount, 2)]) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="iq-card">
                <div class="iq-card-body money-card" style="background-color: #585858">
                    <h3 class="font-size-16 font-weight-bold"><?= $lang('total_withdrawn') ?></h3>
                    <span class="small font-size-12"><?php echo $lang('after_deducting_service_fee'); ?></span>
                    <p class="font-size-32 mb-0"><?= $lang('c_price_earning', ['p' => number_format($totalWithdrawn, 2)]) ?></p>
                </div>
            </div>
        </div>

        <!--        <div class="col-md-6">-->
        <!--            <div class="iq-card">-->
        <!--                <div class="iq-card-body bg-warning money-card">-->
        <!--                    <h3 class="font-size-16 font-weight-bold">-->
        <? //= $lang('profitswithdrawal') ?><!--</h3>-->
        <!--                    <span class="small font-size-12">-->
        <?php // echo $lang('after_deducting_service_fee'); ?><!--</span>-->
        <!--                    <p class="font-size-32 mb-0">></p>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->


        <!--        <div class="col-md-6">-->
        <!--            <div class="iq-card">-->
        <!--                <div class="iq-card-body bg-warning money-card">-->
        <!--                    <h3 class="font-size-16 font-weight-bold">--><? //= $lang('ongoing') ?><!--</h3>-->
        <!--                    <span class="small font-size-12">-->
        <?php // echo $lang('after_deducting_service_fee'); ?><!--</span>-->
        <!--                    <p class="font-size-32 mb-0">></p>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->

        <!-- <div class="col-md-4">
            <div class="iq-card">
                <div class="iq-card-body bg-info money-card">
                    <h3 class="font-size-16 font-weight-bold"><?= $lang('confirmed_amount') ?></h3>
                    <p class="font-size-32 mb-0"><?= $lang('c_price_earning', ['p' => $readyAmount]) ?></p>
                </div>
            </div>
        </div> -->
    </div>

    <div class="col-md-12 text-ar-right">
        <a href="#!" class="btn btn-primary" id="profits-withdraw"><?= $lang('profits_withdrawal') ?></a>
        <span><?= $lang('profits_withdrawal_note1', ['min' => $minimumWithdrawalAmount]) ?></span>
    </div>

    <!--    <form action="--><?php //URL::current() ?><!--" method="GEt">-->
    <!--        <form action="--><? //= URL::current() ?><!--" method="get">-->
    <!--            <div class="row">-->
    <!--                <div class="col-md-6">-->
    <!--                    <div class="form-group">-->
    <!--                        <label for="from">--><? //= $lang('from'); ?><!--</label>-->
    <!--                        <input name="from" required value="-->
    <? //= !empty($from) && $from != '' ? date('Y-m-d', $from) : '' ?><!--" type="date" class="form-control" />-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <div class="col-md-6">-->
    <!--                    <div class="form-group">-->
    <!--                        <label for="from">--><? //= $lang('to'); ?><!--</label>-->
    <!--                        <input name="to" required value="-->
    <? //= !empty($to) && $to != '' ? date('Y-m-d', $to) : '' ?><!--" type="date" class="form-control" />-->
    <!--                    </div>-->
    <!--                </div>-->
    <!---->
    <!--                <div class="col-md-12 text-ar-right">-->
    <!--                    <button type="submit" class="btn btn-primary mb-3">-->
    <? //= $lang('submit'); ?><!--</button>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </form>-->
    <!--    </form>-->

    <div class="container-fluid mt-4">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- <div class="card-header">
                        <h3 class="card-title">DataTable with default features</h3>
                    </div> -->
                    <!-- /.card-header -->
                    <div class="card-body">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-toggle="tab"
                                        data-target="#home-tab-pane" type="button" role="tab"
                                        aria-controls="home-tab-pane"
                                        aria-selected="true"><?= $lang('wallet_transactions') ?></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-toggle="tab"
                                        data-target="#profile-tab-pane" type="button" role="tab"
                                        aria-controls="profile-tab-pane"
                                        aria-selected="false"><?= $lang('withdrawal_requests') ?></button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel"
                                 aria-labelledby="home-tab" tabindex="0">

                                <!--                    <div class="filter-right"><a href="-->
                                <? //= URL::full("{$prefix}/wallets/{$wallet_id}/csv") ?><!--" target="_blank" class="btn btn-primary">-->
                                <? //= $lang('export_csv') ?><!--</a></div>-->
                                <table id="transactions-table" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <div class="filter-right"><a href="<?= URL::full("/earnings/wallet-transactions-csv") ?>" target="_blank" class="btn btn-primary"><?= $lang('export_csv') ?></a></div>
                                    </tr>
                                    <tr>
                                        <th><?= $lang('id') ?></th>
                                        <th><?= $lang('service_type') ?></th>
                                        <th><?= $lang('service_id') ?></th>
                                        <th><?= $lang('beneficiary') ?></th>
                                        <th><?= $lang('transaction_amount') ?></th>
                                        <th><?= $lang('created_at') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($transactions as $transaction) : ?>
                                        <tr>
                                            <td><?= $transaction['id'] ?></td>
                                            <td><?= htmlentities($transaction['entity_type']) ?></td>
                                            <td><?= htmlentities($transaction['entity_id']) ?></td>
                                            <td><?= htmlentities($transaction['beneficiary']) ?></td>
                                            <td><?= htmlentities($transaction['amount']) ?></td>
                                            <td><?= htmlentities($transaction['created_at']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th><?= $lang('id') ?></th>
                                        <th><?= $lang('service_type') ?></th>
                                        <th><?= $lang('service_id') ?></th>
                                        <th><?= $lang('beneficiary') ?></th>
                                        <th><?= $lang('transaction_amount') ?></th>
                                        <th><?= $lang('created_at') ?></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel"
                                 aria-labelledby="profile-tab" tabindex="0">


                                <table id="withdrawal-requests-table" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <div class="filter-right"><a href="<?= URL::full("/earnings/withdrawal-requests-csv") ?>" target="_blank" class="btn btn-primary"><?= $lang('export_csv') ?></a></div>
                                    </tr>
                                    <tr>
                                        <th><?= $lang('id') ?></th>
                                        <th><?= $lang('withdrawal_amount') ?></th>
                                        <th><?= $lang('status') ?></th>
                                        <th><?= $lang('created_at') ?></th>
                                        <th><?= $lang('updated_at') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th><?= $lang('id') ?></th>
                                        <th><?= $lang('withdrawal_amount') ?></th>
                                        <th><?= $lang('status') ?></th>
                                        <th><?= $lang('created_at') ?></th>
                                        <th><?= $lang('updated_at') ?></th>
                                    </tr>
                                    </tfoot>
                                </table>


                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


</div>


<?php View::include ('Earning/modal', [
    'wallet' => $wallet,
    'minimumWithdrawalAmount' => $minimumWithdrawalAmount,
    'freelanceDocumentUploaded' => $freelanceDocumentUploaded,
    'beneficiaryName' => $beneficiaryName,
    'iban' => $iban,
    'bankName' => $bankName,
    'userIsEntity' => $userIsEntity
]) ?>


<define header_css>
    <style>
        .money-card {
            border-radius: 10px;
        }

        .money-card h3 {
            color: var(--iq-white);
        }

        @media screen and (max-width: 900px) {
            .earning-logs span {
                float: none;
                display: block;
            }
        }
    </style>
</define>


<define footer_js>

    <script>
        $('#profits-withdraw').on('click', () => {
            var currentBalance = Math.floor("<?= $wallet['balance'] ?>"),
            minimumWithdrawalAmount = "<?= $minimumWithdrawalAmount ?>";

        if (currentBalance < minimumWithdrawalAmount) {
            toast('danger', "<?= $lang('profits_below_threshold')?>")
        } else {
            $('#earnings-modal').modal('show');
        }

        })
        ;


        var lastLogId = <?= !empty($logs) ? $logs[count($logs) - 1]['id'] : 0; ?>;
        var isBusyLoadMore = false;

        $(".more-log-btn").on('click', function (e) {
            e.preventDefault();
            loadMoreLogs(lastLogId - 1, true);
        })


        // load more logs.
        function loadMoreLogs(lastId, append, callback) {
            if (isBusyLoadMore) return;
            $(".log-loader").removeClass('d-none');

            $.ajax({
                url: URLS.more_logs,
                data: {
                    fromId: lastId,
                    from: '<?= $from ?>',
                    to: '<?= $to ?>',
                    status: 'none',
                    limit: '<?= $limit ?>'
                },
                beforeSend: function () {
                    isBusyLoadMore = true;
                },
                success: function (data) {
                    if (data.info !== 'success') {
                        return;
                    }

                    var logs = data.payload.logs;

                    logs.forEach(function (v) {
                        $('.log-wrapper').append(v.log);
                        lastLogId = v.logId;
                    });

                    if (!data.payload.dataAvl) {
                        $('.more-log-btn').remove();
                    }

                    callback && callback(logs);

                },
                complete: function () {
                    $(".log-loader").addClass('d-none');
                    isBusyLoadMore = false;
                }
            })
        }

        $(function() {
            $("#transactions-table").DataTable({
                "searching": false,
                "order": [
                    [0, "desc"]
                ],

                "lengthChange": false,
                "autoWidth": false,
            })
        });





        $("#withdrawal-requests-table").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "searching": false,
            "order": [
                [0, "desc"]
            ],
            ajax: {
                "url": URLS.get_withdrawal_request,
                "dataSrc": "payload.data"
            },
            "columns": [
                {data: 'id', name: 'id', orderable:false, searchable:false},
                {data: 'amount', name: 'amount', orderable:false, searchable:false},
                {data: 'status', name: 'status', orderable:false, searchable:false},
                {data: 'created_at', name: 'created_at', orderable:false, searchable:false},
                {data: 'updated_at', name: 'updated_at', orderable:false, searchable:false},
            ]
            });







    </script>
</define>
<define header_css>
    <style>
        .log-card {
            /*background-color: #51af51;*/
            background-color: #7380a8;
            color: white;
        }

        .log-card span {
            color: white !important;
        }

        .log-card a {
            color: black;
        }

        .log-card.order_cancel {
            background-color: #bf5151;
        }
    </style>
</define>