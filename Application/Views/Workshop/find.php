<?php

use Application\Models\User;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get('\Application\Models\Language');

$userM = Model::get(User::class);

?>

<div class="container mt-5 workshop-page">
    <div class="row">
        <a href="<?= URL::full('workshops/b') ?>" class="text-bold text-secondary pull-right"><i class="ri-arrow-left-s-line"></i> <?= $lang('back_to_outgoing_sessions') ?></a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="iq-card">
                <div class="iq-card-header d-flex justify-content-between">
                    <div class="iq-header-title">
                        <?php if (empty($user)) : ?>
                            <h4 class="card-title"><?= $lang('find_workshops_search', array(
                                                        'filter' => $type == 'all' ? $lang('all') : $lang('my_following')
                                                    )); ?></h4>
                        <?php else : ?>
                            <h4 class="card-title"><?= $lang('find_workshops_search', array(
                                                        'filter' => $user['name']
                                                    )); ?></h4>
                        <?php endif; ?>
                    </div>
                    <div class="iq-card-header-toolbar d-flex align-items-center"></div>
                </div>

                <?php if (empty($user)) : ?>
                    <div class="iq-card-body">
                        <div class="row">
                            <a href="<?= URL::full('workshops/find/') ?>" class="mx-3">
                                <p class="alert alert-danger bg-filter-color text-nowrap mb-0 <?php if ($type == 'all') echo 'active-alert' ?>">
                                    <?= $lang('all'); ?>
                                </p>
                            </a>
                            <a href="<?= URL::full('workshops/find') ?>?type=follow">
                                <p class="alert bg-filter-color alert-danger text-nowrap mb-0 <?php if ($type == 'follow') echo 'active-alert' ?>"><?= $lang('my_following'); ?></p>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (empty($user)) : ?>
                <form method="POST" action="<?= $follow ? URL::current() . '?type=follow' : URL::current(); ?>">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="iq-card">
                                <div class="iq-card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name"><?= $lang('search_more_workshop') ?></label>
                                                <select class="form-control workshop-name-finder" id="name" multiple name="name[]">
                                                    <?php if (!empty($names)) : ?>
                                                        <?php foreach ($names as $name) : ?>
                                                            <option value="<?= htmlentities($name) ?>" selected><?= htmlentities($name); ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="request"><?= $lang('no_of_request'); ?></label>
                                            <input type="number" class="form-control" name="request" id="request" value="<?php // echo $limit 
                                                                                                                            ?>" min="10" step="4" max="80">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date"><?= $lang('date'); ?></label>
                                            <input type="date" class="form-control" name="date" id="date" value="<?php // echo !empty($date) ? $date : ''; 
                                                                                                                    ?>">
                                        </div>
                                    </div> -->
                                        <div class="col-md-12 <?php if ($lang->current() == 'ar') echo 'text-left'; else echo 'text-right';?>">
                                            <button class="btn btn-primary"><?= $lang('search'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
            <?php if (!empty($workshops)) : ?>
                <div class="row workshop-list">
                    <?php foreach ($workshops as $workshop) : ?>
                        <div class="col-md-12">
                            <?php View::include('Workshop/book_card', ['workshop' => $workshop, 'isAdvisor' => false, 'platform_fees' => $platform_fees]) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="row justify-content-center">
                    <div class="col-md-5 mb-5 text-center">
                        <img src="<?= URL::asset('Application/Assets/images/empty_workshop.png'); ?>" alt="No workshop" class="img-fluid" />
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($limit == count($workshops)) : ?>
                <a href="#" class="more-workshop-btn">More ></a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php View::include('Checkout/modal'); ?>
<define header_css>
    <style>
        .workshop-page .col-md-12:last-child {
            border-bottom: none;
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


            .iq-card {
                margin-bottom: 15px;
                background-color: white;
                padding: 15px;
                border-radius: 5px;
                -webkit-box-shadow: 0px 0px 20px 0px rgba(44, 101, 144, 0.1);
                box-shadow: 0px 0px 20px 0px rgba(44, 101, 144, 0.1);
            }


            .form-group {
                margin-bottom: 15px;
            }

            body {
                background: #fafafb;
            }

            .media-support {
                display: inline-block;
                width: 100%;
            }

            .media-support-user-img img {
                height: 60px;
            }

            .media-support-header {
                display: flex;
                align-items: flex-start;
            }

            .media-support-info {
                flex: 1;
            }

            .media-support-user-img {
                margin-right: 1rem;
            }

            .lang-ar .media-support-user-img {
                margin-right: 0;
                margin-left: 1rem;
            }

            .workshop-card .title,
            .workshop-book-card .title {
                margin-bottom: 15px;
            }

            .workshop-card ul,
            .workshop-book-card ul {
                list-style-type: none;
                padding-left: 0;
                direction: ltr;
            }

            .workshop-book-card li {
                float: left;
                margin-right: 10px;
            }

            .workshop-book-card li i {
                color: #ffba68;
            }

            .lang-ar .workshop-book-card li i {
                margin-left: 5px;
            }

            .workshop-book-card .alert img {
                width: 20px;
            }

            .workshop-book-card .btn-group {
                float: right;
            }

            .lang-ar .workshop-book-card .btn-group {
                float: left;
            }

            .lang-ar .book-card-container p {
                text-align: right;
                clear: both;
            }
            .lang-ar .workshop-card ul, .lang-ar .workshop-book-card ul {
                padding-right: 0;
                float: right;
            }
        </style>
    </define>
<?php endif; ?>
<define footer_js>
    <script>
        $('.workshop-name-finder').select2({
            ajax: {
                url: URLS.workshop_find_search,
                type: 'POST',
                data: function(param) {
                    param.follow = '<?= $follow ?>';
                    return param;
                },
                processResults: function(data) {
                    var final = {
                        results: []
                    };
                    for (var i = 0; i < data.payload.length; i++) {
                        final.results.push({
                            id: data.payload[i].name,
                            text: data.payload[i].name
                        });
                    }

                    return final;
                }
            }
        });

        var workshopSkip = <?= $limit; ?>;
        var workshopLimit = <?= $limit; ?>;

        var workshopIsBusy = false;

        $('.more-workshop-btn').on('click', function(e) {
            e.preventDefault();

            if (workshopIsBusy) return false;

            var $self = $(this);

            $.ajax({
                url: URLS.find_more_workshop,
                type: 'POST',
                beforeSend: function() {
                    workshopIsBusy = true;
                },
                data: {
                    skip: workshopSkip,
                    limit: workshopLimit,
                    names: '<?= json_encode($names) ?>',
                    user: <?= !empty($user) ? $user['id'] : 'null' ?>
                },
                complete: function() {
                    workshopIsBusy = false;
                },
                success: function(data) {
                    var d = data.payload;
                    workshopSkip = d.skip;
                    var w = d.workshops;

                    if (!d.dataAvl) $self.remove();

                    for (var i = 0; i < w.length; i++) {
                        $('.workshop-list').append('<div class="col-md-12">' + w[i] + '</div>');
                    }
                }
            });
        });
    </script>
</define>