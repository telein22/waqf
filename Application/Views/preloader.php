<?php

use System\Helpers\URL;

?>
<div class="preloader">
    <img src="<?= URL::asset('Application/Assets/images/logo.svg'); ?>" alt="Logo" />
</div>
<define footer_js>
    <script>
        $(window).on('load', function() {
            $('.preloader').addClass('hide');
        })
    </script>
</define>
<define header_css>
    <style>
        @keyframes shake {
            0% {
                -webkit-transform: translate(2px, 1px) rotate(0deg);
                transform: translate(2px, 1px) rotate(0deg);
            }

            10% {
                -webkit-transform: translate(-1px, -2px) rotate(-2deg);
                transform: translate(-1px, -2px) rotate(-2deg);
            }

            20% {
                -webkit-transform: translate(-3px, 0) rotate(3deg);
                transform: translate(-3px, 0) rotate(3deg);
            }

            30% {
                -webkit-transform: translate(0, 2px) rotate(0deg);
                transform: translate(0, 2px) rotate(0deg);
            }

            40% {
                -webkit-transform: translate(1px, -1px) rotate(1deg);
                transform: translate(1px, -1px) rotate(1deg);
            }

            50% {
                -webkit-transform: translate(-1px, 2px) rotate(-1deg);
                transform: translate(-1px, 2px) rotate(-1deg);
            }

            60% {
                -webkit-transform: translate(-3px, 1px) rotate(0deg);
                transform: translate(-3px, 1px) rotate(0deg);
            }

            70% {
                -webkit-transform: translate(2px, 1px) rotate(-2deg);
                transform: translate(2px, 1px) rotate(-2deg);
            }

            80% {
                -webkit-transform: translate(-1px, -1px) rotate(4deg);
                transform: translate(-1px, -1px) rotate(4deg);
            }

            90% {
                -webkit-transform: translate(2px, 2px) rotate(0deg);
                transform: translate(2px, 2px) rotate(0deg);
            }

            100% {
                -webkit-transform: translate(1px, -2px) rotate(-1deg);
                transform: translate(1px, -2px) rotate(-1deg);
            }
        }

        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
            z-index: 100000000;
            opacity: 1;
            visibility: visible;
            transition: all .3s ease-in-out;
        }

        .preloader.hide {
            opacity: 0;
            visibility: hidden;
        }

        .preloader img {
            width: 200px;
            animation: shake 1.5s infinite;
        }
    </style>
</define>