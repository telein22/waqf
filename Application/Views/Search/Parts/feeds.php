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
            <?php if (!empty($feeds)) : ?>
                <?php foreach ($feeds as $feed) : ?>
                  <?php View::include('Search/Parts/feed', [  'feed' => $feed ]); ?>
                <?php endforeach; ?>

                <?php if ( count($feeds) == $limit ): ?>
                    <li class="d-block text-center m-0 p-0 load-more-search">
                        <?php if ( $staticLoadMore ): ?>
                            <a href="<?= URL::full('search/feeds/?' . http_build_query($_GET)) ?>" class="mr-3 btn"><?= $lang('view_more_request') ?></a>
                        <?php else: ?>
                            <a href="#" onclick="loadMoreFeeds();return false;" class="mr-3 btn"><?= $lang('view_more_request') ?></a>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>

            <?php else: ?>
                <li><p class="text-center">No tweets found</p></li>
            <?php endif; ?>

           
        </ul>
    </div>
</div>

<?php if ( !empty($feeds) ): ?>
<define footer_js>
    <script>
        var lastFeedId = <?= !empty($feeds) ? $feeds[count($feeds) - 1]['id'] : 0;  ?>;
        var isSearchBusy = false;

        function loadMoreFeeds( elm ) {
            if ( isSearchBusy ) return;

            <?php            
                $data =  $_GET;
                // $data['fromId'] = !empty($feeds) ? $feeds[count($feeds) - 1]['id'] : 0;
                $data['type'] = 'feeds';
            ?>
            var data = <?= json_encode($data); ?>;
            data['fromId'] = lastFeedId;

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

                    lastFeedId = payload.lastId;

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