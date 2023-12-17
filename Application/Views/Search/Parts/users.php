<?php

use Application\Helpers\UserHelper;
use Application\Models\Language;
use System\Core\Model;
use System\Helpers\URL;
use System\Responses\View;

$lang = Model::get(Language::class);
?>
<div class="iq-card">
    <!-- <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">                                    
                                </div>
                            </div> -->
    <div class="iq-card-body">
        <ul class="request-list list-inline m-0 p-0">
            <?php if (!empty($users)) : ?>
                <?php foreach ($users as $user) : ?>
                    <?php View::include('Search/Parts/user', [ 'user' => $user ]); ?>
                <?php endforeach; ?>
                <?php if ( count($users) == $limit ): ?>
                    <li class="d-block text-center m-0 p-0 load-more-search">
                        <?php if ( $staticLoadMore ): ?>
                            <a href="<?= URL::full('search/users/?' . http_build_query($_GET)) ?>" class="mr-3 btn"><?= $lang('view_more_request') ?></a>
                        <?php else: ?>
                            <a href="javascript:void()" onclick="loadMoreUsers();" class="mr-3 btn"><?= $lang('view_more_request') ?></a>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
            <?php else: ?>
                <li><p class="text-center">No users found</p></li>
            <?php endif; ?>

           
        </ul>
    </div>
</div>

<?php if ( !empty($users) ): ?>
<define footer_js>
    <script>
        var lastUserId = <?= count($users);  ?>;
        var isSearchBusy = false;

        function loadMoreUsers( elm ) {
            if ( isSearchBusy ) return;

            <?php            
                $data =  $_GET;
                // $data['fromId'] = !empty($feeds) ? $feeds[count($feeds) - 1]['id'] : 0;
                $data['type'] = 'users';
            ?>
            var data = <?= json_encode($data); ?>;
            data['fromId'] = lastUserId;

            $.ajax({
                url: URLS.search,
                data: data,
                type: 'POST',
                dataType: 'JSON',
                accepts: 'JSON',
                beforeSend: function() {
                    isSearchBusy = true;
                },
                success: function(data) {
                    if ( data.info !== 'success' ) return;

                    var payload = data.payload;
                    payload.data.forEach(function(v){
                        $('.load-more-search').before(v);
                    });

                    lastUserId = payload.lastId;

                    if ( !payload.dataAvl ) {
                        $('.load-more-search').remove();
                    }
                },
                complete: function() {
                    isSearchBusy = false;
                }
            });
        }
    </script>
</define>
<?php endif; ?>