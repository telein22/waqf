<?php

use System\Core\Model;
use System\Helpers\URL;

$lang = Model::get('\Application\Models\Language');
?>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <?php if ( !$review ): ?>
                <div class="iq-card">
                    <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                            <?php if ($imgUrl) : ?>
                                <img src="<?= $imgUrl; ?>" />
                            <?php endif; ?>
                            <h4 class="card-title"><?= $lang('review_title_' . $entityType, [ 'name' => htmlentities($name) ]) ?></h4>
                        </div>
                    </div>
                    <div class="iq-card-body">
                        <!-- <p><?= $lang('give_your_rating') ?></p> -->
                        <div class="review-body">
                            <a href="#" onclick="review(1);return false;">
                                <ul class="clearfix">
                                    <li>
                                        <i class="ri-star-line"></i>
                                        <i class="ri-star-fill"></i>
                                    </li>
                                </ul>
                            </a>

                            <a href="#" onclick="review(2);return false;">
                                <ul class="clearfix">
                                    <li>
                                        <i class="ri-star-line"></i>
                                        <i class="ri-star-fill"></i>
                                    </li>
                                    <li>
                                        <i class="ri-star-line"></i>
                                        <i class="ri-star-fill"></i>
                                    </li>
                                </ul>
                            </a>

                            <a href="#" onclick="review(3);return false;">
                                <ul class="clearfix">
                                    <li>
                                        <i class="ri-star-line"></i>
                                        <i class="ri-star-fill"></i>
                                    </li>
                                    <li>
                                        <i class="ri-star-line"></i>
                                        <i class="ri-star-fill"></i>
                                    </li>
                                    <li>
                                        <i class="ri-star-line"></i>
                                        <i class="ri-star-fill"></i>
                                    </li>
                                </ul>
                            </a>

                            <a href="#" onclick="review(4);return false;">
                                <ul class="clearfix">
                                    <li>
                                        <i class="ri-star-line"></i>
                                        <i class="ri-star-fill"></i>
                                    </li>
                                    <li>
                                        <i class="ri-star-line"></i>
                                        <i class="ri-star-fill"></i>
                                    </li>
                                    <li>
                                        <i class="ri-star-line"></i>
                                        <i class="ri-star-fill"></i>
                                    </li>
                                    <li>
                                        <i class="ri-star-line"></i>
                                        <i class="ri-star-fill"></i>
                                    </li>
                                </ul>
                            </a>

                            <a href="#" onclick="review(5);return false;">
                                <ul class="clearfix">
                                    <li>
                                        <i class="ri-star-line"></i>
                                        <i class="ri-star-fill"></i>
                                    </li>
                                    <li>
                                        <i class="ri-star-line"></i>
                                        <i class="ri-star-fill"></i>
                                    </li>
                                    <li>
                                        <i class="ri-star-line"></i>
                                        <i class="ri-star-fill"></i>
                                    </li>
                                    <li>
                                        <i class="ri-star-line"></i>
                                        <i class="ri-star-fill"></i>
                                    </li>
                                    <li>
                                        <i class="ri-star-line"></i>
                                        <i class="ri-star-fill"></i>
                                    </li>
                                </ul>
                            </a>

                        </div>
                        
                    </div>

                    <hr>
                    <p class="text-center pb-3">
                        <?= $lang('give_your_rating') ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="text-center">
                    <h3 class="text-center"><?= $lang('thank_you'); ?></h3>
                    <a href="<?= URL::full('dashboard') ?>" class="btn btn-primary mt-3"><?= $lang('back_to_home'); ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<define header_css>
    <style>
        .review-body {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            flex-wrap: wrap;
            flex-direction: column;
        }

        .review-body ul {
            list-style: none;
            margin: 0;
            margin-right: 20px;
            margin-top: 10px;
            margin-bottom: 10px;
            padding: 0;
            background: var(--iq-light-primary);
            padding: 5px;
            border-radius: 30px;
        }

        .review-body ul li{
            float: left;
            margin: 0;
            font-size: 22px;
            line-height: 22px;
            padding: 5px;
            color: var(--iq-dark);
        }

        .review-body ul li i:last-child {
            display: none;
        }

        .review-body ul:hover li i:first-child {
            display: none;
        }

        .review-body ul:hover li i:last-child {
            display: inline;
        }

        .iq-card-header img {
            width: 40px;
            height: 40px;
            margin-right: 20px;
            border-radius: 50%;
        }

        .iq-card-header .iq-header-title {
            display: flex !important;
            flex-direction: row;
            justify-content: center;
            align-items: center;

        }

        .message-container {
            padding: 15px;
            border-radius: 10px;
        }

        .message-container:not(:last-child) {
            margin-bottom: 20px;
        }
    </style>
</define>

<define footer_js>
    <script>
        function review( review )
        {
            cConfirm('<div class="custom-label-align"><?= $lang('is_final_review') ?></div>', function() {
                window.location.href = '<?= URL::full('review/' . $entityId . '/' . $entityType) ?>/' + review + '/<?= $ownerId ?>';
            });
        }
    </script>
</define>