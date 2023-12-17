<?php

use Application\Models\Order;
use Application\Models\Payment;
use System\Core\Config;
use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');
?>
<div class="container">
    <div class="row mt-5 justify-content-center">
        <div class="col-md-8">
            <div class="iq-card">
                <div class="iq-card-header d-flex">
                    <div class="iq-header-title">                        
                        <h4 class="card-title">                        
                            <?= $lang('checkout_pay_title'); ?>                            
                        </h4>
                        <span class="text-primary font-size-16 text-bold"><?= $lang('c_price', ['p' => $order['payable']]); ?></span>
                    </div>
                </div>
                <div class="iq-card-body">
                    <?php

                        switch ( $order['payment_method'] )
                        {
                            case Payment::METHOD_STC:
                                $brands = "STC_PAY";
                                break;
                            case Payment::METHOD_APPLE_PAY:
                                $brands = "APPLEPAY";
                                break;
                            case Payment::METHOD_MADA:
                                $brands = "MADA";
                                break;
                            default:
                                $brands = "VISA MASTER";
                        }

                    ?>
                    <form action="<?= URL::full('order/complete') ?>" class="paymentWidgets" data-brands="<?= $brands; ?>"></form>                    
                </div>
            </div>
        </div>
    </div>
</div>
<define footer_js>
    <script>
        var wpwlOptions = {
            style: "plain",            
            forceCardHolderEqualsBillingName: true,            
            showCVVHint: true,
            requireCvv: true,
            allowEmptyCvv: false,
            brandDetection: false,
            billingAddress: <?= $billingAddress ?>,
            paymentTarget:"_top",
            applePay: {
                displayName: "Telein",
                total: { label: "Telein" },
                currencyCode: 'SAR',
                countryCode: 'SA',
                supportedNetworks:  ["mada","visa","masterCard"],
                supportedCountries: ["SA"],
                merchantCapabilities: ["supports3DS"]
                // requiredBillingContactFields: ["email", "name", "phone", "postalAddress"],
                // onPaymentAuthorized: function (payment) {
                //     document.body.innerHTML = JSON.stringify(payment);
                // }
            },
            mandatoryBillingFields:{
                country: true,
                state: true,
                city: true,
                postcode: true,
                street1: true,
                street2: false
            },
            locale: '<?= $lang->current() ?>',
            onReady: function(){ 
                <?php if ( $order['payment_method'] != Payment::METHOD_APPLE_PAY ): ?>
                    $(".wpwl-group-cardNumber").after($(".wpwl-group-brand").detach());
                    $(".wpwl-group-cvv").after( $(".wpwl-group-cardHolder").detach());
                    
                    var visa = $(".wpwl-brand:first").clone().removeAttr("class").attr("class", "wpwl-brand-card wpwl-brand-custom wpwl-brand-VISA")
                    <?php if ( $order['payment_method'] == Payment::METHOD_STC ): ?>
                        var master = $(visa).clone().removeClass("wpwl-brand-VISA").addClass("wpwl-brand-MASTER");                    
                    <?php else: ?>
                        var visa = $(".wpwl-brand:first").clone().removeAttr("class").attr("class", "wpwl-brand-card wpwl-brand-custom wpwl-brand-VISA")
                    <?php endif; ?>
                    // var mada = $(visa).clone().removeClass("wpwl-brand-VISA").addClass("wpwl-brand-MADA");
                    // var stc = $(visa).clone().removeClass("wpwl-brand-VISA").addClass("wpwl-brand-STC_PAY");
                    $(".wpwl-brand:first");  
                <?php endif; ?>              
            },
            onChangeBrand: function(e){
                $(".wpwl-brand-custom").css("opacity", "0.3");
                $(".wpwl-brand-" + e).css("opacity", "1"); 
            }
    }
    </script>
    <?php $config = Config::get('HyperPay'); ?>
    <?php $baseUrl = $config->baseURL;  ?>
    <script src="<?= $baseUrl ?>/v1/paymentWidgets.js?checkoutId=<?= $token; ?>"></script>
</define>
<define header_css>
    <style>
        .iq-header-title {
            display: flex !important;
            width: 100%;
            justify-content: space-between;
            align-items: center;
            flex-direction: row;
        }

        .wpwl-wrapper > .wpwl-icon {
            position: absolute;
            right: 0;
            top: 3px;
            border-radius: unset;
            border: 0;
            background: transparent;
        }

        .wpwl-apple-pay-button{-webkit-appearance: -apple-pay-button !important;}
    </style>
</define>