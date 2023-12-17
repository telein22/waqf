<?php

use Application\Helpers\UserHelper;
use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get(Language::class);
?>


<?php if(!$userInfo['received_bc'] && $userInfo['phone']): ?>
<iframe src="<?= URL::full('business-card') . '?user_id=' . $userInfo['id'] ?>"  style="position: absolute;width:0;height:0;border:0;"></iframe>
<iframe src="<?= URL::full('social-media-card') . '?user_id=' . $userInfo['id'] ?>"  style="position: absolute;width:0;height:0;border:0;"></iframe>
<?php endif ?>


<div class="container">
    <div class="row mt-5 mb-5">
        <div class="col-md-12">
            <h2 class="font-size-32 mb-3"><?= $lang('follow_best_advisors', array(
                            'filter' => $selected == null ? '- All' : '- ' . $selected['specialty_' . $lang->current()]
                        )); ?></h2>
        </div>
        <div class="col-md-12">
            <div class="specialities-items owl-carousel">
                <div class="item">
                    <a href="<?= URL::full('dashboard')?>">
                        <p class="alert alert-danger bg-filter-color text-nowrap mb-0 <?php if( !$selected ) echo 'active-alert' ?>">
                            <?= $lang('all'); ?>
                        </p>
                    </a></div>
                <?php foreach ($specs as $spec) : ?>
                    <div class="item">
                    <a href="<?= URL::full('dashboard') . "/{$spec['specialty_en']}" ?>">
                            <p class="alert alert-danger bg-filter-color text-nowrap mb-0 <?php if( $selected && $selected['id'] == $spec['id'] ) echo 'active-alert' ?>">
                                <?= htmlentities($spec['specialty_' . $lang->current()]); ?>
                            </p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-md-12">
                <h2 class="font-size-32 mb-3"><?= $lang('follow_best_entities', array(
                        'filter' => $selected == null ? '- All' : '- ' . $selected['specialty_' . $lang->current()]
                    )); ?></h2>
            </div>

            <div class="col-md-12">
                <div class="specialities-items owl-carousel">
                    <div class="item">
                        <a href="<?= URL::full('dashboard')?>">
                            <p class="alert alert-danger bg-filter-color text-nowrap mb-0 <?php if( !$entitySelected ) echo 'active-alert' ?>">
                                <?= $lang('all'); ?>
                            </p>
                        </a></div>
                    <?php foreach ($entities as $entity) : ?>
                        <div class="item">
                            <a href="<?= URL::full('dashboard') . "/entities/{$entity['name']}" ?>">
                                <p class="alert alert-danger bg-filter-color text-nowrap mb-0 <?php if( $entitySelected && $entitySelected['id'] == $entity['id'] ) echo 'active-alert' ?>">
                                    <?= htmlentities($entity['name']); ?>
                                </p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>


            <div class="form-group mt-3 mb-0 pb-0">
                <img  id="loader" class="d-none" src="<?= URL::asset("Application/Assets/images/page-img/ajax-loader.gif"); ?>" alt="loader" style="width: 75px; height: 75px; margin: 0 auto; position: fixed; top:50%; left: 50%; z-index:99999999;" >
                <div class="row search-container">
                    <i class="search-icon ri-search-line"></i>
                    <input type="text" name="search-user" id="search-user" placeholder="<?= $lang('dashboard_search_placeholder') ?>" class="form-control search-user-input">
                </div>
            </div>
        </div>
    </div>
    <div class="row user-row">
        <?php if (!empty($users)) : ?>
            <?php foreach ($users as $user) : ?>
                <?php View::include('Dashboard/user', ['user' => $user, 'followingIds' => $followingIds]); ?>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-center"><?= $lang('no_results') ?></p>
        <?php endif; ?>
    </div>
    <?php if (count($users) == $limit) : ?>
        <div class="row more-row">
            <div class="col-md-12">
                <p><a href="#!" onclick="loadMoreUser()"><?= $lang('more') ?> ></a></p>
            </div>
        </div>
    <?php endif; ?>
</div>
<define header_css>
    <style>
        .specialities-items .owl-stage {
            display: flex;
            flex-wrap: nowrap;
        }

        .search-container {
            position: relative;
        }

        .search-user-input {
            font-size: 13px;
        }

        .lang-ar .search-user-input {
            padding-right: 32px; /* To create space for the icon */
        }

        .search-user-input {
            padding-left: 32px; /* To create space for the icon */
        }

        .lang-ar .search-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            color: #888;
            cursor: pointer;
        }

        .search-icon {
            width: 2%;
            font-size: 20px;
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #888;
            cursor: pointer;
        }

    </style>
</define>
<define footer_js>
    <script>
        var lang = '<?= $lang->current() ?>';

        var rtl = false;
        if( lang === 'ar' ) {
            rtl = true;
        }

        $('.search-user-input').on('keydown', (e) => {
            var query = $('#search-user').val();

            if(e.key === 'Enter' && query.length > 0) {
                search(query);
            }
        });

        $('.search-icon').click(() => {

            var query = $('#search-user').val();

            if (query.length > 0) {
                search(query)
            }
        });

        function search(query) {
            $('#loader').removeClass('d-none');

            $.ajax({
                url: "<?= URL::full('/ajax/dashboard/search') ?>",
                data: {
                    q: query
                },
                dataType: 'JSON',
                accepts: 'JSON',
                type: 'POST',
                success: function(data) {
                    if (data.info !== 'success') {
                        $('#loader').addClass('d-none');
                        return;
                    }

                    $('#loader').addClass('d-none');

                    if (!data.payload.dataAvl) {
                        $('.more-row').hide();
                    } else {
                        $('.more-row').show();
                    }

                    var wrapper = $(".user-row");
                    wrapper.empty();
                    wrapper.html(data.payload.data);
                    dashboardSkip = data.payload.data.length
                },
                error: function(data) {
                    $('#loader').addClass('d-none');
                }
            });
        }

        $('.specialities-items').owlCarousel({
            loop: false,
            margin: 10,
            autoWidth: true,
            rtl: rtl,
            dots: false,
            nav: false,
        });


        var dashboardSkip = <?= count($users); ?>;
        function loadMoreUser() {
            $('#loader').removeClass('d-none');
            var query = $('#search-user').val();
            $.ajax({
                url: URLS.more_user_suggestions,
                data: {
                    skip: dashboardSkip,
                    q: query
                },
                dataType: 'JSON',
                accepts: 'JSON',
                type: 'POST',
                beforeSend: function() {

                },
                success: function(data) {
                    if (data.info !== 'success') {
                        return;
                    }
                    $('#loader').addClass('d-none');
                    dashboardSkip = data.payload.skip;

                    // toggle the follow icon
                    data.payload.list.forEach(function(v) {
                        $('.user-row').append(v);
                    });

                    if (!data.payload.dataAvl) {
                        $('.more-row').hide();
                    }
                },
                complete: function(data) {

                }

            });

        }

        function onclickFollow(elm, entityId) {
            $.ajax({
                url: URLS.toggle_follow,
                data: {
                    id: entityId
                },
                dataType: 'JSON',
                accepts: 'JSON',
                type: 'POST',
                beforeSend: function() {

                },
                success: function(data) {
                    if (data.info !== 'success') {
                        return;
                    }
                },
                complete: function() {
                    // Follow
                    // toggle the follow icon
                    $(elm).addClass('d-none');
                    $(elm).prev().removeClass('d-none');
                    toast('primary', '<?= $lang('success'); ?>', '<?= $lang('follow_success'); ?>');

                    dashboardSkip -= 1;
                }

            });
        }
    </script>
</define>