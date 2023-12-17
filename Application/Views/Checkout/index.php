<?php

use Application\Models\Coupons;
use Application\Models\Invite;
use Application\Models\Payment;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get('\Application\Models\Language');

?>
<div class="container">
    <div class="row mt-5 justify-content-center">
        <div class="col-md-8">
            <div class="iq-card position-relative inner-page-bg bg-primary" style="height: 150px;">
                <div class="inner-page-title">
                    <h3 class="text-white"><?= $lang('checkout'); ?></h3>
                    <p class="text-white"><?= $lang('check_out_for', ['name' => htmlentities($name)]); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="iq-card checkout-card-<?= $type ?>">
                <!-- <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="card-title"><?= $lang('checkout'); ?></h4>
                    </div>
                </div> -->
                <div class="iq-card-body">
                    <div class="checkout-item-container">
                        <?php
                        View::include('Checkout/Items/item_' . $type, [
                            'item' => $item
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="iq-card">
                <div class="iq-card-body">
                    <div class="checkout-apply-coupon">
                        <?php if ( empty( $coupon )  ): ?>
                            <?php if ( $inviteType !== Invite::JOIN_TYPE_FREE ): ?>
                                <a href="javascript:void(0)" data-target="#apply-coupon-modal" data-toggle="modal"><?= $lang('apply_coupon_btn') ?></a>
                                <hr>
                            <?php endif; ?>
                        <?php else: ?>
                                <h3 class="font-size-16 clearfix">
                                    <span class="remove_coupon pull-right">x</span>
                                    <?= $lang('coupon_applied', [ 'coupon' => $coupon['code'] ]); ?>
                                </h3>                                
                                <hr>
                        <?php endif; ?>
                    </div>
                    <div class="checkout-bill">
                        <?php
                            $payable = $price;
                        ?>
                        <table>
                            <tr>
                               <!-- remove total line -->

                            </tr>

                            <?php $platformFeesA = $payable * $platform_fees / 100 ?>
                            <?php $platformFeesA = number_format(round($platformFeesA, 2), 2); ?>
                            <?php $priceWithPlatformFees = $payable + $platformFeesA ?>

                            <?php if ( !empty($coupon) ): ?>
                                <?php
                                    $cPrice = Coupons::TYPE_FIXED == $coupon['type'] ? (float) $coupon['amount'] : 0;                                    
                                    $cPrice = Coupons::TYPE_PERCENT == $coupon['type'] ? $priceWithPlatformFees * ((float) $coupon['amount'] / 100) : $cPrice;
                                    $cPrice = number_format(round($cPrice, 2), 2);
                                    $priceWithPlatformFees -= $cPrice;
                                    $priceWithPlatformFees = $priceWithPlatformFees < 0 ? 0 : $priceWithPlatformFees;
                                ?>
                            <tr>
                                <td><?= $lang('coupon_discount'); ?></td>
                                <td class="text-danger"><?= $lang('c_price', ['p' => '-' . $cPrice]); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php $vatA = $payable * $vat / 100 ?>
                            <?php $vatA = number_format(round($vatA, 2), 2); ?>
                            <?php $priceWithPlatformFees += $vatA ?>

                            <tr>
                                <!-- remove vat total line -->
                            </tr>                            
                            <?php if ( $inviteType === Invite::JOIN_TYPE_FREE ): ?>
                                <tr>
                                    <td colspan="2" class="text-primary" style="text-align: center;"><?= $lang('invite_free'); ?></td>
                                </tr>
                                <?php $priceWithPlatformFees = 0; ?>
                            <?php endif; ?>
                            <tr>
                                <td><?= $lang('total_plus_vat'); ?></td>
                                <td><?= $lang('c_price', ['p' => number_format($priceWithPlatformFees, 2)]); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <h4 class="font-size-16 card-title"><?= $lang('select_a_payment_option'); ?></h4>
                    </div>
                </div>
                <div class="iq-card-body">
                    <div class="checkout-main">
                        <!-- <p class="text-center font-size-18">You need to pay the following about</p>
                        <p class="h3 text-center text-danger"><?php // echo $price ?> SR</p> -->
                        <div class="main-form text-center mt-3 ">
                            <p class="text-center mt-4 loading-text"><?= $lang('loading'); ?></p>
                            <form method="POST" accept="<?= URL::full('checkout/create-order'); ?>" class="payment-form" style="visibility: hidden;">
                                <div class="payment-switcher mb-5">
                                    <div class="payment-wrapper mb-3">
                                        <input type="radio" name="payM" id="option-1" class="apple-pay" value="<?= Payment::METHOD_APPLE_PAY; ?>">
                                        <input type="radio" name="payM" id="option-2" class="visa" value="<?= Payment::METHOD_VISA; ?>">
                                        <input type="radio" name="payM" id="option-3" class="stc_pay" value="<?= Payment::METHOD_STC; ?>">
                                        <input type="radio" name="payM" id="option-4" class="mada" value="<?= Payment::METHOD_MADA; ?>">
                                        <label for="option-1" class="option option-1">
                                            <div class="dot"></div>
                                            <span><?= $lang('pay_with_apple_pay');  ?></span>
                                        </label>
                                        <label for="option-2" class="option option-2">
                                            <div class="dot"></div>
                                            <span><?= $lang('pay_with_visa');  ?></span>
                                        </label>                                        
                                        <label for="option-3" class="option option-3">
                                            <div class="dot"></div>
                                            <span><?= $lang('pay_with_stc');  ?></span>
                                        </label>
                                        <label for="option-4" class="option option-4">
                                            <div class="dot"></div>
                                            <span><?= $lang('pay_with_mada');  ?></span>
                                        </label>
                                    </div>                                  
                                </div>
                                <input type="hidden" value="<?= $id ?>" name="id"/>
                                <input type="hidden" value="<?= $type ?>" name="type"/>
                                <input type="hidden" value="<?= $price ?>" name="amount"/>
                                <input type="hidden" value="<?= $vat ?>" name="vat" />
                                <input type="hidden" value="<?= $vatA ?>" name="vat_amount" />
                                <?php if ( $inviteType === Invite::JOIN_TYPE_FREE ): ?>
                                    <?php $priceWithPlatformFees = 0; ?>
                                    <p class="mb-3"><?= $lang('checkout_invited_free_' . $type); ?></p>
                                    <button type="submit" class="btn btn-danger"><?= $lang('free_checkout'); ?></button>
                                <?php else: ?>
                                    <button type="submit" id="submit-btn" class="btn btn-danger checkout-pay-button"><?= $lang('pay_now', ['price' => $priceWithPlatformFees]); ?></button>
                                <?php endif; ?>
                                <input type="hidden" value="<?= number_format($priceWithPlatformFees, 2) ?>" name="payable" />
                                <?php if ( !empty( $coupon )  ): ?>
                                    <input type="hidden" value="<?= htmlentities($coupon['code']); ?>" name="coupon" />
                                    <input type="hidden" value="<?= htmlentities($cPrice); ?>" name="coupon_amount" />
                                <?php endif; ?>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php View::include('Checkout/coupon_modal'); ?>
<define header_css>
    <style>
        .btn-group {
            display: none;
        }

        .payment-wrapper {
            display: block;
            /* height: 40px; */
            /* max-width: 500px; */
            align-items: center;
            justify-content: space-evenly;
            margin: 0 auto;
            flex-wrap: wrap;
        }
        

        .payment-wrapper .option {
            background: var(--iq-white);
            height: 100%;
            width: 100%;
            /* display: flex; */
            /* align-items: center; */
            /* justify-content: space-evenly; */
            margin: 0 ;
            border-radius: 5px;
            cursor: pointer;
            padding: 0 7px;
            border: 1px solid var(--iq-border-light);
            transition: all 0.3s ease;
            font-size: 14px;
            line-height: 38px;
            position: relative;
            margin-bottom: 15px;
        }

        .payment-wrapper .option:last-child {
            margin-bottom: 0;
        }

        .payment-wrapper .option .dot {
            height: 15px;
            width: 15px;
            background: var(--iq-border-light);
            border-radius: 50%;
            position: relative;
            float: right;
            display: inline-block;
            position: absolute;
            right: 10px;
            top: 50%;
            margin-top: -7px;
        }

        .payment-wrapper .option .dot::before {
            position: absolute;
            content: "";
            top: 4px;
            left: 4px;
            width: 7px;
            height: 7px;
            background: #000000;
            border-radius: 50%;
            opacity: 0;
            transform: scale(1.5);
            transition: all 0.3s ease;
        }

        .payment-wrapper input[type="radio"] {
            display: none;
        }

        .payment-wrapper #option-1:checked:checked~.option-1,
        .payment-wrapper #option-2:checked:checked~.option-2,
        .payment-wrapper #option-3:checked:checked~.option-3,
        .payment-wrapper #option-4:checked:checked~.option-4 {
            border-color: #000000;
            background: #000000;
        }

        .payment-wrapper #option-1:checked:checked~.option-1 .dot,
        .payment-wrapper #option-2:checked:checked~.option-2 .dot,
        .payment-wrapper #option-3:checked:checked~.option-3 .dot,
        .payment-wrapper #option-4:checked:checked~.option-4 .dot {
            background: #fff;
        }

        .payment-wrapper #option-1:checked:checked~.option-1 .dot::before,
        .payment-wrapper #option-2:checked:checked~.option-2 .dot::before,
        .payment-wrapper #option-3:checked:checked~.option-3 .dot::before,
        .payment-wrapper #option-4:checked:checked~.option-4 .dot::before {
            opacity: 1;
            transform: scale(1);
        }

        .payment-wrapper .option span {
            /* font-size: 13px; */
            color: #808080;
            display: block;
        }

        .payment-wrapper #option-1:checked:checked~.option-1 span,
        .payment-wrapper #option-2:checked:checked~.option-2 span,
        .payment-wrapper #option-3:checked:checked~.option-3 span,
        .payment-wrapper #option-4:checked:checked~.option-4 span {
            color: #fff;
        }

        
        @media screen and ( max-width: 900px ) {
            .payment-wrapper  {
                display: block;
                height: auto;
            }

            .payment-wrapper .option {
                margin-bottom: 15px;
                margin-left: 0;
                margin-right: 0;
            }

        }

        .checkout-bill table {
            width: 100%;
            text-align: right;
            font-weight: bold;
        }

        .checkout-bill table td:first-child {
            /* padding: 10px; */
            text-align: left;
        }

        .checkout-bill table tr:last-child td {
            padding-top: 20px;
        }

        /* .checkout-bill table td:last-child {
            width: 100px;
        } */

        .checkout-bill table td:not(:last-child) {
            /* border-right: 1px solid var(--iq-border-light); */
        }

        .remove_coupon {
            cursor: pointer;
        }
    </style>
</define>
<define footer_js>
    <script>

        function hideAll() {
            $('.apple-pay-info').addClass('d-none');
            $('.visa-info').addClass('d-none');
            $('.visa-inputs').addClass('d-none');
        }

        // var cleave = new Cleave('#card', {
        //     creditCard: true,
        //     onCreditCardTypeChanged: function(type) {
        //         // Update UI ...
        //     }
        // });

        // var cleave = new Cleave('#expr', {
        //     date: true,
        //     datePattern: ['m', 'y']
        // });

        // var cleave = new Cleave('#cvv', {
        //     blocks: [3],
        //     uppercase: true
        // });

        <?php if ( $inviteType !== Invite::JOIN_TYPE_FREE ): ?>

        setTimeout(function() {
            $('.loading-text').addClass('d-none');
            if (!window.ApplePaySession) {            
                // The Apple Pay JS API is available.
                $('.apple-pay').addClass('d-none');
                $('.option-1').addClass('d-none');
                $('.visa').trigger('click');
                $('.payment-form').css({
                    visibility: 'visible'
                });
            } else {
                $('.apple-pay').trigger('click');
                $('.payment-form').css({
                    visibility: 'visible'
                });
            }
        }, 2000);

        function applyCoupon() {
            
        }

        <?php else: ?>
            setTimeout(function() {
                $('.loading-text').addClass('d-none');
                $('.payment-switcher').addClass('d-none');
                $('.payment-form').css({
                    visibility: 'visible'
                });
            }, 2000);
        <?php endif; ?>
    </script>
    <script>
        var isSubmitting = false;
        $('.payment-form').on('submit', function(e){
            if ( isSubmitting ) return;

            e.preventDefault();

            $.ajax({
                url: URLS.create_order,
                data: $(this).serialize(),
                beforeSend: function() {
                    isSubmitting = true;
                    $('#submit-btn').html('<?= $lang('loading') ?>');
                },
                success: function( data ) {
                    if ( data.info !== 'success' ) {
                        toast('danger', '<?= $lang('error') ?>', data.payload);
                        return;
                    }

                    window.location.href = data.payload;
                },
                complete: function() {
                    isSubmitting = false;
                    $('#submit-btn').html('<?= $lang('pay_now', ['price' => number_format($priceWithPlatformFees, 2)]); ?>');
                }
            });


        });

        $('.remove_coupon').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: URLS.checkout_remove_coupon,
                data: $(this).serialize(),
                beforeSend: function() {
                },
                success: function( data ) {

                    window.location.reload();

                },
                complete: function() {

                }
            });
        });
    </script>
</define>