<?php

use Application\Helpers\DateHelper;
use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get(Language::class);
?>

<div class="container">
    <div class="row mt-5 justify-content-center">
        <div class="col-md-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title font-size-16"><?= $lang('my_orders_title', ['count' => $orderCount['count']]); ?></h4>
                    </div>
                    <!-- <div class="iq-card-header-toolbar d-flex align-items-center">
                        <div class="iq-card-header-toolbar d-flex align-items-center">
                            <form action="<?= URL::current(); ?>" method="POST">
                                <div class="form-group mb-0">
                                    <select name="year" class="form-control">
                                        <?php // foreach ($years as $year) : ?>
                                            <option value="<?php // echo $year ?>"><?php // echo $year ?></option>
                                        <?php // endforeach; ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div> -->
                </div>
                <div class="iq-card-body">
                    <div class="container orders-wrapper">
                        <?php if( !empty( $orders ) ) : ?>
                            <?php foreach ($orders as $order) : ?>
                                <?php View::include('Order/my_item', array(
                                    'order' => $order
                                )) ?>

                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="mb-0 pb-0"><?= $lang('notification_no_orders') ?></p>
                        <?php endif; ?>


                    </div>
                    <?php if ( count($orders) == $limit ): ?>
                        <div class="container mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="#" class="more-workshop-btn"><?= $lang('more') ?> ></a>
                                </div>
                                <div class="col-sm-12 text-center order-loader d-none">
                                <img src="<?= URL::asset("Application/Assets/images/page-img/page-load-loader.gif"); ?>" alt="loader" style="height: 100px;">
                            </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<define footer_js>
    <script>
        var lastOrderId = <?= !empty($orders) ? $orders[count($orders) - 1]['id'] : 0; ?>;
        var isBusyLoadMore = false;

        $(".more-workshop-btn").on('click', function(e) {
            e.preventDefault();
            loadeMoreOrders(lastOrderId - 1, true);
        })


        // load more orders.
        function loadeMoreOrders(lastId, append, callback) {
            if (isBusyLoadMore) return;
            $(".order-loader").removeClass('d-none');

            $.ajax({
                url: URLS.more_order,
                data: {
                    fromId: lastId,
                    status: 'none',
                    limit: '<?= $limit ?>'
                },
                beforeSend: function() {
                    isBusyLoadMore = true;
                },
                success: function(data) {
                    if (data.info !== 'success') {
                        return;
                    }

                    var orders = data.payload.orders;

                    orders.forEach(function(v) {
                        $('.orders-wrapper').append(v.order);
                        lastOrderId = v.orderId;
                    });

                    if (!data.payload.dataAvl) {
                        $('.more-workshop-btn').remove();
                    }

                    callback && callback(orders);

                },
                complete: function() {
                    $(".order-loader").addClass('d-none');
                    isBusyLoadMore = false;
                }
            })
        }
    </script>
</define>