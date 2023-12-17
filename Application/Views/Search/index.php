<?php

use Application\Models\User;
use System\Core\Model;
use System\Core\Request;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get('\Application\Models\Language');
$searchQ = Request::instance()->get('q');
$userM = Model::get(User::class);
?>

<div class="container">
    <div class="row mt-5">

        <!-- <div class="col-md-3">
            <div class="iq-card bg-primary search-query">
                <div class="iq-card-body">
                    <p class="m-0">
                        <?php if (empty($q)) : ?>
                            <?= $lang('no_search_term'); ?>
                        <?php else : ?>
                            <?= $lang('searching_for', ['term' => htmlentities($q)]); ?>
                        <?php endif; ?>                        
                    </p>
                </div>
            </div>
            <?php View::include('Search/Parts/user_filter', [
                'q' => $q,
                'users' => $searchUsers,
            ]); ?>
            <?php if (!$isHash) : ?>
                <?php $query = http_build_query($_GET); ?>
                <div class="order-request-menu list-group iq-menu">
                    <a href="<?= URL::full('/search?' . $query) ?>" class="list-group-item iq-waves-effect <?= $type == 'all' ? 'active' : '' ?>">
                        <i class="ri-star-line"></i> <?= $lang('all') ?>
                    </a>
                    <a href="<?= URL::full('/search/users?' . $query) ?>" class="list-group-item iq-waves-effect  <?= $type == 'users' ? 'active' : '' ?>">
                        <i class="ri-group-line"></i> <?= $lang('users') ?>
                    </a>
                    <a href="<?= URL::full('/search/feeds?' . $query) ?>" class="list-group-item iq-waves-effect <?= $type == 'feeds' ? 'active' : '' ?>">
                        <i class="ri-file-list-3-line"></i> <?= $lang('tweets') ?>
                    </a>
                </div>
            <?php endif; ?>
        </div> -->

        <div class="col-md-12">
            <div class="iq-card">
                <div class="iq-card-body p-3">
                    <div class="iq-search-bar sm-block">
                        <form action="<?= URL::full('search') ?>" method="GET" class="searchbox">
                            <input type="text" class="text search-input" placeholder="<?= $lang('search_placeholder') ?>" name="q" value="<?= $searchQ; ?>">
                            <a class="search-link" href="#" onclick="$('.searchbox').submit();"><i class="ri-search-line"></i></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <?php if (!$isHash) : ?>
                <?php $query = http_build_query($_GET); ?>
                <!-- <div class="order-request-menu list-group iq-menu">
                    <a href="<?= URL::full('/search?' . $query) ?>" class="list-group-item iq-waves-effect <?= $type == 'all' ? 'active' : '' ?>">
                        <i class="ri-star-line"></i> <?= $lang('all') ?>
                    </a>
                    <a href="<?= URL::full('/search/users?' . $query) ?>" class="list-group-item iq-waves-effect  <?= $type == 'users' ? 'active' : '' ?>">
                        <i class="ri-group-line"></i> <?= $lang('users') ?>
                    </a>
                    <a href="<?= URL::full('/search/feeds?' . $query) ?>" class="list-group-item iq-waves-effect <?= $type == 'feeds' ? 'active' : '' ?>">
                        <i class="ri-file-list-3-line"></i> <?= $lang('tweets') ?>
                    </a>
                </div> -->
                <div class="iq-card search-nav">
                    <div class="iq-card-body p-0">
                        <div class="user-tabing">
                            <ul class="nav nav-pills d-flex align-items-center justify-content-center profile-feed-items p-0 m-0">
                                <li class="col-sm-2 p-0">
                                    <a class="<?php if ($type == 'all') echo 'active' ?> nav-link timeline-tab-nav" href="<?= URL::full('/search?' . $query) ?>">
                                        <?= $lang('all') ?>
                                    </a>
                                </li>
                                <li class="col-sm-2 p-0">
                                    <a class="<?php if ($type == 'users') echo 'active' ?> nav-link about-tab-nav" href="<?= URL::full('/search/users?' . $query) ?>">
                                        <?= $lang('users') ?>
                                    </a>
                                </li>
                                <li class="col-sm-2 p-0">
                                    <a class="<?php if ($type == 'feeds') echo 'active' ?> nav-link comment-tab-nav" href="<?= URL::full('/search/feeds?' . $query) ?>">
                                        <?= $lang('posts') ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="">
                <div class="row">
                    <div class="col-md-12">
                        <?php View::include('Search/Parts/filter', [
                            'q' => $q,
                            'specs' => $specs,
                            'spec' => $spec,
                            'subSpecs' => $subSpecs,
                            'subSpec' => $subSpec,
                            // 'userInfo' => $userInfo
                            // 'subS' => $usubspl,
                        ]); ?>
                    </div>
                </div>
                <?php if (!$isHash) : ?>

                    <div class="row">
                        <?php if ($showUsers) : ?>
                            <div class="col-md-12">
                                <?php View::include('Search/Parts/users', ['users' => $users, 'limit' => $limit, 'staticLoadMore' => $staticLoadMore]) ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($showFeeds) : ?>
                            <div class="col-md-12">
                                <?php View::include('Search/Parts/feeds', [
                                    'feeds' => $feeds,
                                    'limit' => $limit,
                                    'staticLoadMore' => $staticLoadMore,
                                    // 'userInfo' => $userInfo
                                ]) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="row">
                        <div class="col-md-12">
                            <?php View::include('Search/Parts/feeds', ['feeds' => $feeds, 'limit' => $limit, 'staticLoadMore' => $staticLoadMore]) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<define header_css>
    <style>
        .order-request-menu {
            margin-bottom: 15px;
        }

        ul.request-list>li .d-flex {
            margin-left: 0;
            margin-top: 0;
        }
    </style>
</define>
<?php if (!$userM->isLoggedIn()) : ?>
    <define header_css>
        <style>
            .btn-primary {
                background-color: #3f4aaa;
                border-color: #3f4aaa;
            }

            .search-nav {
                text-align: center;
                padding: 0 !important;
            }

            .search-input {
                width: 100%;
            }

            .search-query {
                margin-bottom: 10px;
                background-color: white !important;
                font-size: 18px;
            }

            .iq-card {
                margin-bottom: 15px;
                background-color: white;
                padding: 15px;
                border-radius: 5px;
                -webkit-box-shadow: 0px 0px 20px 0px rgba(44, 101, 144, 0.1);
                box-shadow: 0px 0px 20px 0px rgba(44, 101, 144, 0.1);
            }

            .order-request-menu a {
                border: none;
            }

            .profile-feed-items li a.nav-link {
                text-decoration: none;
                text-transform: capitalize;
                color: #777;
                text-align: center;
                padding: 20px 15px;
            }

            .profile-feed-items li a.nav-link.active {
                background-color: #3f4aaa;
            }

            .form-group {
                margin-bottom: 15px;
            }

            body {
                background: #fafafb;
            }

            .request-list li {
                padding-bottom: 15px;
                margin-top: 15px;
                border-bottom: 1px solid #d1d1d1;
            }

            .request-list li:last-child {
                border: none;
            }

            .request-list img {
                width: 70px;
                height: 70px;
            }

            .media-support-info {
                flex: 1;
                margin-left: 1rem !important;
            }

            .media-support-info h6 {
                font-size: 18px;
                margin: 0;
            }

            .lang-ar .media-support-info {
                margin-left: auto !important;
                margin-right: 1rem !important;
            }

            .iq-search-bar {
                padding: 0 15px;
            }

            .iq-search-bar .searchbox {
                width: 100%;
                position: relative;
            }

            .iq-search-bar .search-input {
                width: 100%;
                height: 40px;
                padding: 5px 40px 5px 15px;
                border-radius: 10px;
                border: none;
                background: #e8f1fb;
            }

            .iq-search-bar .search-input::placeholder {
                color: #bfbfbf;
            }

            .iq-search-bar .searchbox .search-link {
                position: absolute;
                right: 15px;
                top: 6px;
                font-size: 16px;
            }
        </style>
    </define>
<?php endif; ?>