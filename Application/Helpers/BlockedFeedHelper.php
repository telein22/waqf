<?php

namespace Application\Helpers;

use Application\Models\Expression;
use System\Core\Model;

class BlockedFeedHelper
{
    public static function prepare( $feeds )
    {
        $hiddenM = Model::get('\Application\Models\HiddenEntities');

        $entities = $hiddenM->listByType( 'feed' );

        $hiddenFeeds = array();

        foreach( $entities as $item )
        {
            $hiddenFeeds[] = $item['entity_id'];
        }
        
        foreach ( $feeds as &$feed )
        {
            $blockedFeedM = Model::get('\Application\Models\BlockedFeedWords');
            
            $rows = $blockedFeedM->getByEntityId( $feed['id'] );
            
            $words = [];
            foreach( $rows as $item )
            {
                $words[] = $item['word'];
            }

            $words = implode(', ', $words);
            $feed['word'] = $words; 
            
            $feed['hidden'] = in_array( $feed['id'], $hiddenFeeds );
        }

        return $feeds;
    }
}