<?php

namespace Application\Controllers;

use Application\Helpers\FeedHelper;
use Application\Helpers\FollowerHelper;
use Application\Helpers\UserHelper;
use Application\Main\MainController;
use Application\Models\UserSettings;
use System\Core\Controller;
use System\Core\Exceptions\Error404;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Responses\View;

class OuterProfile extends MainController
{
    public function index( Request $request, Response $response )
    {
        $lang = $this->language;
        $feeds = array();

        $id = $request->param(0);
        if ( $this->user->isLoggedIn() ) throw new Redirect('profile/' . $id);
        $isFollowing = false;
        /**
         * @var \Application\Models\Follow
         */
        $followM = Model::get('\Application\Models\Follow');

        $entityUser = $this->user->getUser($id);
        if ( empty($entityUser) ) throw new Error404();

        $feedLimit = 4;

        $type = $request->param(1, 'timeline');

        switch ( $type ) {
            case 'timeline':
            case 'comment':
            case 'liked':
            case 'media':
            case 'about':
            case 'followers':
            case 'following':
            case 'associates':
                break;
            
            default:
                throw new Error404();
        }

        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');

        $feedM = Model::get('\Application\Models\Feed');

        switch ( $type ) {
            case 'timeline':
                $feeds = $feedM->getProfileFeeds($entityUser['id'], null ,$feedLimit);
                $feeds = FeedHelper::prepare($feeds, 0);
                break;
            case 'liked':
                $feeds = $feedM->getLikedFeeds($entityUser['id'], null, $feedLimit, true);
                $feeds = FeedHelper::prepare($feeds, 0);
                break;
            case 'media':
                $feeds = $feedM->getMediaFeeds($entityUser['id'], null, $feedLimit, true);
                $feeds = FeedHelper::prepare($feeds, 0);
                break;
            case 'comment':
                $feeds = $feedM->getCommentedFeeds($entityUser['id'], null, $feedLimit, true);
                $feeds = FeedHelper::prepare($feeds, 0);
                break;
        }
        
        $coverUrl = UserHelper::getCoverUrl('fit:970,300', $entityUser['id']);
        $avatarUrl = UserHelper::getAvatarUrl('fit:300,300', $entityUser['id']);

        $feedCount = $feedM->countFeeds($entityUser['id'], true);
        $followerCount = $followM->followerCount($entityUser['id'], true);
        $followCount = $followM->followCount($entityUser['id'], true);

        $associatesCount = $this->user->getAssociatesCount($entityUser['id'], true);

        $followers = $followM->getFollowers($entityUser['id'], 0, FollowerHelper::PAGE_LIMIT, true);
        $followers = FollowerHelper::prepare($followers);

        $followings = $followM->getFollowing($entityUser['id'], 0, FollowerHelper::PAGE_LIMIT, true);
        $followings = FollowerHelper::prepare($followings);

        $associates = $this->user->getAssociates($entityUser['id'], 0, FollowerHelper::PAGE_LIMIT, true);

        $reviewsM = Model::get('\Application\Models\Reviews');
        $reviews = $reviewsM->getReviewsUser( $entityUser['id'] );

        $reviews['percent'] = ($reviews['avg'] / 5) * 100;

        /**
         * @var \Application\Models\UserSpecialty
         */
        $userSplM = Model::get('\Application\Models\UserSpecialty');
        $specialties = $userSplM->getSpl($entityUser['id']);

        /**
         * @var \Application\Models\UserSpecialty
         */
        $userSubSplM = Model::get('\Application\Models\UserSubSpecialty');
        $subSpecialties = $userSubSplM->getSpl($entityUser['id']);

        /**
         * @var \Application\Models\UserSettings
         */
        $userSM = Model::get('\Application\Models\UserSettings');

        $about = array();
        $about['email'] = $entityUser['email'];
        $about['phone'] = $userSM->take($entityUser['id'], UserSettings::KEY_PHONE, $lang('none'));
        $about['gender'] = $userSM->take($entityUser['id'], UserSettings::KEY_GENDER, $lang('none'));
        $about['dob'] = $userSM->take($entityUser['id'], UserSettings::KEY_DOB, $lang('none'));
        $about['country'] = $userSM->take($entityUser['id'], UserSettings::KEY_COUNTRY);
        $about['city'] = $userSM->take($entityUser['id'], UserSettings::KEY_CITY);
        $about['bio'] = $userSM->take($entityUser['id'], UserSettings::KEY_BIO);
        $about['achievements'] = $userSM->take($entityUser['id'], UserSettings::KEY_ACHIEVEMENTS);
        $about['specialties'] = $specialties;
        $about['subSpecialties'] = $subSpecialties;
        
        $social = [
            'snapchat' => $userSM->take($entityUser['id'], UserSettings::KEY_SOCIAL_SNAPCHAT),
            'linkedIn' => $userSM->take($entityUser['id'], UserSettings::KEY_SOCIAL_LINKEDIN),
            'insta' => $userSM->take($entityUser['id'], UserSettings::KEY_SOCIAL_INSTAGRAM),
            'youtube' => $userSM->take($entityUser['id'], UserSettings::KEY_SOCIAL_YOUTUBE),
            'facebook' => $userSM->take($entityUser['id'], UserSettings::KEY_SOCIAL_FACEBOOK),
            'telegram' => $userSM->take($entityUser['id'], UserSettings::KEY_SOCIAL_TELEGRAM),
            'twitter' => $userSM->take($entityUser['id'], UserSettings::KEY_SOCIAL_TWITTER),
            'website' => $userSM->take($entityUser['id'], UserSettings::KEY_SOCIAL_WEBSITE)
        ];

        $enableMessaging = $userSM->take($entityUser['id'], UserSettings::KEY_MESSAGING_ENABLE, 0);
        $messagingPrice = $userSM->take($entityUser['id'], UserSettings::KEY_MESSAGING_PRICE);

        $country = null;
        $city = null;

        if ( $about['country'] )
        {
            /**
             * @var \Application\Models\Country
             */
            $countryM = Model::get('\Application\Models\Country');
            $country = $countryM->getById($about['country']);
        }
        
        if ( $about['city'] )
        {
            /**
             * @var \Application\Models\City
             */
            $cityM = Model::get('\Application\Models\City');
            $city = $cityM->getById($about['city']);
        }

        $about['country'] = $country;
        $about['city'] = $city;

        $view = new View();
        $view->set('Outer/Profile/index', [
            'type' => $type,
            'feedLimit' => $feedLimit,            
            'user' => $entityUser,
            'feeds' => $feeds,
            'isFollowing' => $isFollowing,
            'coverUrl' => $coverUrl,
            'avatarUrl' => $avatarUrl,
            'followerCount' => $followerCount,
            'followCount' => $followCount,
            'feedCount' => $feedCount,
            'followers' => $followers,
            'followings' => $followings,
            'about' => $about,
            'reviews' => $reviews,
            'enableMessaging' => $enableMessaging,            
            'messagingPrice' => $messagingPrice,
            'social' => $social,
            'isEntity' => $this->user->isEntity($entityUser),
            'entityInfo' => $this->user->getEntityById($entityUser['entity_id']),
            'associatesCount' => $associatesCount,
            'associates' => $associates,
        ]);

        $view->disableParse();

        $pview = new View();
        $pview->set('base', [
            'content' => $view->content(),
            'user' => $entityUser
        ]);

        $response->set($pview);
    }
}