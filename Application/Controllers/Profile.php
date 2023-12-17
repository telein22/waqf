<?php

namespace Application\Controllers;

use Application\Helpers\AppHelper;
use Application\Helpers\FeedHelper;
use Application\Helpers\FollowerHelper;
use Application\Helpers\UserHelper;
use Application\Main\AuthController;
use Application\Models\User;
use Application\Models\UserSettings;
use System\Core\Exceptions\Error404;
use System\Core\Exceptions\Redirect;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Libs\FormValidator;
use System\Models\Cookie;
use System\Responses\View;

class Profile extends AuthController
{
    public function index(Request $request, Response $response)
    {
        $lang = $this->language;
        $userInfo = $this->user->getInfo();
        $feeds = array();

        $id = $request->param(0);
        $id = empty($id) ? $userInfo['id'] : $id;

        $type = $request->param(1, 'timeline');

        switch ($type) {
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

        $isOwner = $id == $userInfo['id'];
        $isFollowing = false;

        /**
         * @var \Application\Models\Follow
         */
        $followM = Model::get('\Application\Models\Follow');

        $entityUser = $userInfo;
        if (!$isOwner) {
            $entityUser = $this->user->getUser($id);
            if (empty($entityUser)) throw new Error404;

            // check if the current user is following this entity user.            
            $isFollowing = $followM->isFollowing($userInfo['id'], $entityUser['id']);
        }

        if ($entityUser['suspended'] == 1 && !$isOwner) throw new Error404();

        $feedLimit = 4;

        $feedM = Model::get('\Application\Models\Feed');
        switch ($type) {
            case 'timeline':
                $feeds = $feedM->getProfileFeeds($entityUser['id'], null, $feedLimit);
                $feeds = FeedHelper::prepare($feeds, $userInfo['id']);
                break;
            case 'liked':
                $feeds = $feedM->getLikedFeeds($entityUser['id'], null, $feedLimit, true);
                $feeds = FeedHelper::prepare($feeds, $userInfo['id']);
                break;
            case 'media':
                $feeds = $feedM->getMediaFeeds($entityUser['id'], null, $feedLimit, true);
                $feeds = FeedHelper::prepare($feeds, $userInfo['id']);
                break;
            case 'comment':
                $feeds = $feedM->getCommentedFeeds($entityUser['id'], null, $feedLimit, true);
                $feeds = FeedHelper::prepare($feeds, $userInfo['id']);
                break;
        }

        foreach ($feeds as $feed) {
            $feedViewerM = Model::get('\Application\Models\FeedViewers');

            if ($feed['user_id'] != $userInfo['id']) {
                $feedViewerM->create(array(
                    'feed_id' => $feed['id'],
                    'viewer_id' => $userInfo['id'],
                    'viewed_at' => time()
                ));
            }
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
        $reviews = $reviewsM->getReviewsUser($entityUser['id']);

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

        if ($about['country']) {
            /**
             * @var \Application\Models\Country
             */
            $countryM = Model::get('\Application\Models\Country');
            $country = $countryM->getById($about['country']);
        }

        if ($about['city']) {
            /**
             * @var \Application\Models\City
             */
            $cityM = Model::get('\Application\Models\City');
            $city = $cityM->getById($about['city']);
        }

        $about['country'] = $country;
        $about['city'] = $city;

        $view = new View();
        $view->set('Profile/index', [
            'type' => $type,
            'feedLimit' => $feedLimit,
            'feeds' => $feeds,
            'user' => $entityUser,
            'isOwner' => $isOwner,
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
            'cUser' => $userInfo,
            // This variable stores if the viewer can workshop or not
            'viewerCanWorkshop' => $this->user->canCreateWorkshop(),
            'isEntity' => $this->user->isEntity($entityUser),
            'entityInfo' => $this->user->getEntityById($entityUser['entity_id']),
            'associatesCount' => $associatesCount,
            'associates' => $associates,
        ]);

        $view->append('footer');
        $view->prepend('header', [
            'title' => $entityUser['name'],
            'user' => $userInfo
        ]);

        $response->set($view);
    }

    public function edit(Request $request, Response $response)
    {
        $lang = $this->language;

        $section = 'general';
        $userInfo = $this->user->getInfo();
        $isEntity = $this->user->isEntity($userInfo);

        /**
         * @var \Application\Models\Country
         */
        $countryM = Model::get('\Application\Models\Country');
        $countries = $countryM->getAll();


        /**
         * @var \Application\Models\Specialty
         */
        $specialtiesModel = Model::get("\Application\Models\Specialty");
        $specialties = $specialtiesModel->all();

        /**
         * @var \Application\Models\SubSpecialty
         */
        $subSpecialtiesM = Model::get("\Application\Models\SubSpecialty");
        $subSpecialties = $subSpecialtiesM->all();

        $subSIds = array();
        foreach ($subSpecialties as $specialty) {
            $subSIds[] = $specialty['id'];
        }


        $sIds = array();
        foreach ($specialties as $specialty) {
            $sIds[] = $specialty['id'];
        }

        $formRules = [
            'name' => [
                'required' => true,
                'type' => 'string'
            ],
            'email' => [
                'required' => true,
                'type' => 'string',
                'unique' => 'users,email,' . $userInfo['email'],
                'pattern' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'
            ],
            'gender' => [
                'required' => true,
                'type' => 'select',
                'values' => [1, 2]
            ],
            'dob' => [
                'required' => true,
                'type' => 'string'
            ],
            'country' => [
                'required' => true,
                'type' => 'string'
            ],
            'city' => [
                'required' => true,
                'type' => 'string'
            ],
            'spl' => [
                'required' => true,
                'type' => 'multiselect',
                'values' => $sIds
            ],
            'subSpl' => [
                'required' => true,
                'type' => 'multiselect',
                'values' => $subSIds
            ],
            'bio' => [
                'required' => true,
                'type' => 'string',
            ],
            'achievements' => [
                'type' => 'string',
            ],
            'b1' => [
                'type' => 'string'
            ],
            'b2' => [
                'type' => 'string'
            ],
            'b3' => [
                'type' => 'string'
            ],
            'entity' => [
                'required' => false,
                'type' => 'string'
            ],
        ];

        $formValidation = [
            'name.required' => $lang('field_required'),
            'email.required' => $lang('field_required'),
            'email.unique' => $lang('email_unique'),
            'email.pattern' => $lang('email_pattern'),
            'gender.required' => $lang('field_required'),
            'dob.required' => $lang('field_required'),
            'country' => $lang('field_required'),
            'city.required' => $lang('field_required'),
            'spl.required' => $lang('field_required'),
            'subSpl.required' => $lang('field_required'),
            'subSpl.values' => $lang('sub_specialties_values'),
            'bio.required' => $lang('field_required')
        ];

        if ($request->post('phone')) {
            $formRules['phone'] = [
                'required' => true,
                'type' => 'number'
            ];

            $formValidation = array_merge($formValidation, [
                'phone.required' => $lang('field_required'),
                'phone.type' => $lang('phone_invalid'),
            ]);
        }

        if ($isEntity) {
            unset($formRules['gender']);
            unset($formRules['dob']);
            unset($formRules['subSpl']);
        }


        $formValidator = FormValidator::instance("edit_general");
        $formValidator->setRules($formRules)->setErrors($formValidation);


        /**
         * @var \Application\Models\UserSettings
         */
        $userSM = Model::get('\Application\Models\UserSettings');
        /**
         * @var \Application\Models\UserSpecialty
         */
        $userSplM = Model::get('\Application\Models\UserSpecialty');

        $userSubSplM = Model::get('\Application\Models\UserSubSpecialty');
        $isSaved = false;

        if ($request->getHTTPMethod() == 'POST' && $formValidator->validate()) {
            $userSM->put($userInfo['id'], UserSettings::KEY_BANK1, $formValidator->getValue('b1'));

            $userSM->put($userInfo['id'], UserSettings::KEY_BANK2, $formValidator->getValue('b2'));

            if ($b3 = $formValidator->getValue('b3')) {
                $userSM->put($userInfo['id'], UserSettings::KEY_BANK3, $b3);
            }

            $email = $formValidator->getValue('email');
            $name = $formValidator->getValue('name');
            $phone = $formValidator->getValue('phone');
            $gender = $formValidator->getValue('gender');
            $dob = $formValidator->getValue('dob');
            $country = $formValidator->getValue('country');
            $city = $formValidator->getValue('city');
            $uspl = $formValidator->getValue('spl');
            $usubspl = $formValidator->getValue('subSpl');
            $bio = $formValidator->getValue('bio');
            $achievements = $formValidator->getValue('achievements');
            $entity = (int)$formValidator->getValue('entity');
            $entity = $entity == 0 ? null : $entity;

            $userData = ['name' => $name, 'email' => $email, 'phone' => $phone, 'entity_id' => $entity];

            if ($email != $userInfo['email']) {
                $userData['email_verified'] = 0;
            }

            $this->user->update($userData);

            if (!$isEntity) {
                $userSM->put($userInfo['id'], UserSettings::KEY_GENDER, $gender);
                $userSM->put($userInfo['id'], UserSettings::KEY_DOB, $dob);
            }

            $userSM->put($userInfo['id'], UserSettings::KEY_COUNTRY, $country);
            $userSM->put($userInfo['id'], UserSettings::KEY_CITY, $city);
            $userSM->put($userInfo['id'], UserSettings::KEY_BIO, $bio);
            $userSM->put($userInfo['id'], UserSettings::KEY_ACHIEVEMENTS, $achievements);

            // update the sub specialties
            $userSubSplM->delete($userInfo['id']);
            $userSubSplM->create($userInfo['id'], $usubspl);

            // update the specialties
            $userSplM->delete($userInfo['id']);
            $userSplM->create($userInfo['id'], $uspl);


            // all saves are done
            $isSaved = true;
        }

        if (!$isEntity) {
            $gender = $userSM->take($userInfo['id'], UserSettings::KEY_GENDER);
            $dob = $userSM->take($userInfo['id'], UserSettings::KEY_DOB);
        }

        $phone = $userInfo['phone'];
        $country = $userSM->take($userInfo['id'], UserSettings::KEY_COUNTRY);
        $city = $userSM->take($userInfo['id'], UserSettings::KEY_CITY);
        $uspl = $userSplM->getSpl($userInfo['id']);
        $usubspl = $userSubSplM->getUserSpl($userInfo['id']);
        $bio = $userSM->take($userInfo['id'], UserSettings::KEY_BIO);
        $achievements = $userSM->take($userInfo['id'], UserSettings::KEY_ACHIEVEMENTS);

        $subSpecialties = array();
        if ($request->post('spl')) {
            $subSpecialties = $request->post('spl');

            $subSpecialties = $subSpecialtiesM->getBySpecialty($subSpecialties);
        } else {
            $splList = array();
            foreach ($uspl as $item) {
                $splList[] = $item['id'];
            }

            $subSpecialties = $subSpecialtiesM->getBySpecialty($splList);
        }

        $cities = array();
        if ($country = $formValidator->getValue('country', $country)) {
            /**
             * @var \Application\Models\City
             */
            $cityM = Model::get('\Application\Models\City');
            $cities = $cityM->getByCountry($country);
        }

        $bank1 = $userSM->take($userInfo['id'], UserSettings::KEY_BANK1);
        $bank2 = $userSM->take($userInfo['id'], UserSettings::KEY_BANK2);
        $bank3 = $userSM->take($userInfo['id'], UserSettings::KEY_BANK3);

        // get the entities
        $entities = $this->user->getEntities();

        $socailSection = 'social';
        $view = new View();
        $view->set('Profile/edit', [
            'section' => $section,
            'data' => [
                'bank1' => $bank1,
                'bank2' => $bank2,
                'bank3' => $bank3,
                'countries' => $countries,
                'user' => $userInfo,
                'gender' => $gender,
                'phone' => $phone,
                'dob' => $dob,
                'country' => $country,
                'cities' => $cities,
                'city' => $city,
                'specialties' => $specialties,
                'subSpecialties' => $subSpecialties,
                'uspl' => $uspl,
                'usubspl' => $usubspl,
                'isSaved' => $isSaved,
                'bio' => $bio,
                'achievements' => $achievements,
                'isEntity' => $isEntity,
                'entities' => $entities,
                'entity' => $entity
            ]
        ]);
        $view->prepend('header', [
            'title' => "Edit Profile"
        ]);
        $view->append('footer');

        $response->set($view);
    }

    public function editPwd(Request $request, Response $response)
    {
        $section = 'change_pwd';
        $userInfo = $this->user->getInfo();

        $lang = $this->language;

        /**
         * @var \Application\Models\UserSettings
         */
        $userSM = Model::get('\Application\Models\UserSettings');

        $isSaved = false;

        $formValidator = FormValidator::instance('edit_pwd');
        $formValidator->setRules([
            'cpass' => [
                'required' => true,
                'type' => 'string'
            ],
            'npass' => [
                'required' => true,
                'type' => 'string'
            ],
            'vpass' => [
                'required' => true,
                'type' => 'string'
            ]
        ])->setErrors([
            'cpass.required' => $lang('field_required'),
            'npass.required' => $lang('field_required'),
            'vpass.required' => $lang('field_required')
        ]);

        if ($request->getHTTPMethod() == "POST" && $formValidator->validate()) {
            $cpass = $formValidator->getValue('cpass');
            $npass = $formValidator->getValue('npass');
            $vpass = $formValidator->getValue('vpass');

            $isOk = $this->user->verifyPassword($cpass, $userInfo['password']);
            if (!$isOk) {
                $formValidator->setError('cpass', $lang('current_password_not_match'));
            } else if ($npass !== $vpass) {
                $formValidator->setError('npass', $lang('new_password_not_match'));
                $formValidator->setError('vpass', $lang('new_password_not_match'));
            } else {
                // we can save
                /**
                 * @var \Application\Models\User
                 */
                $this->user->update(array(
                    'password' => password_hash($npass, PASSWORD_DEFAULT)
                ));

                $isSaved = true;

                $userM = Model::get('\Application\Models\User');
                $userM->logout();

                /**
                 * @var Cookie
                 */
                $cookieM = Model::get(Cookie::class);

                $token = $cookieM->getCookie('loginToken');

                if (!empty($token)) {
                    /**
                     * @var RememberToken
                     */
                    $rememberM = Model::get(RememberToken::class);
                    $rememberM->removeByToken($token);

                    $cookieM->removeCookie('loginToken');
                }

                throw new Redirect("login");
            }
        }

        $view = new View();
        $view->set('Profile/edit', [
            'section' => $section,
            'data' => [
                'isSaved' => $isSaved
            ]
        ]);
        $view->prepend('header', [
            'title' => "Edit Profile"
        ]);
        $view->append('footer');

        $response->set($view);
    }

    public function editBank(Request $request, Response $response)
    {
        $lang = $this->language;

        $section = 'bank';
        $userInfo = $this->user->getInfo();

        $formValidator = FormValidator::instance("edit_bank");
        $formValidator->setRules([
            'b1' => [
                'required' => true,
                'type' => 'string'
            ],
            'b2' => [
                'type' => 'string'
            ],
            'b3' => [
                'type' => 'string'
            ],
        ])->setErrors([
            'b1.required' => $lang('field_required')
        ]);

        $isSaved = false;

        /**
         * @var \Application\Models\UserSettings
         */
        $userSM = Model::get('\Application\Models\UserSettings');

        if ($request->getHTTPMethod() == 'POST' && $formValidator->validate()) {

            $userSM->put($userInfo['id'], UserSettings::KEY_BANK1, $formValidator->getValue('b1'));

            if ($b2 = $formValidator->getValue('b2')) {
                $userSM->put($userInfo['id'], UserSettings::KEY_BANK2, $b2);
            }

            if ($b3 = $formValidator->getValue('b3')) {
                $userSM->put($userInfo['id'], UserSettings::KEY_BANK3, $b3);
            }

            $isSaved = true;
        }

        $bank1 = $userSM->take($userInfo['id'], UserSettings::KEY_BANK1);
        $bank2 = $userSM->take($userInfo['id'], UserSettings::KEY_BANK2);
        $bank3 = $userSM->take($userInfo['id'], UserSettings::KEY_BANK3);

        $view = new View();
        $view->set('Profile/edit', [
            'section' => $section,
            'data' => [
                'bank1' => $bank1,
                'bank2' => $bank2,
                'bank3' => $bank3,
                'isSaved' => $isSaved
            ]
        ]);
        $view->prepend('header', [
            'title' => "Edit Profile"
        ]);
        $view->append('footer');

        $response->set($view);
    }

    public function editSocial(Request $request, Response $response)
    {
        $lang = $this->language;

        $section = 'social';
        $userInfo = $this->user->getInfo();

        $formValidator = FormValidator::instance("edit_social");
        $formValidator->setRules([
            'snapchat' => [
                'type' => 'string',
                'pattern' => '/^https:\/\/www\.snapchat\.com/'
            ],
            'linkedin' => [
                'type' => 'string',
                'pattern' => '/^https:\/\/www\.linkedin\.com/'
            ],
            'insta' => [
                'type' => 'string',
                'pattern' => '/^https:\/\/www\.instagram\.com/'
            ],
            'youtube' => [
                'type' => 'string',
                'pattern' => '/^https:\/\/www\.youtube\.com/'
            ],
            'facebook' => [
                'type' => 'string',
                'pattern' => '/^https:\/\/www\.facebook\.com/'
            ],
            'telegram' => [
                'type' => 'string',
//                'pattern' => '/^https:\/\/www\.t\.me/'
            ],
            'twitter' => [
                'type' => 'string',
                'pattern' => '/^(?:https?:\/\/)?(?:www\.)?twitter\.com\/[a-zA-Z0-9_]+\/?$/'
            ],
            'website' => [
                'type' => 'string',
                'pattern' => '/^https:\/\//'
            ]
        ])->setErrors([
            'snapchat.pattern' => $lang('snapchat_invalid'),
            'linkedin.pattern' => $lang('linkedin_invalid'),
            'insta.pattern' => $lang('insta_invalid'),
            'youtube.pattern' => $lang('youtube_invalid'),
            'facebook.pattern' => $lang('facebook_invalid'),
            'telegram.pattern' => $lang('telegram_invalid'),
            'twitter.pattern' => $lang('twitter_invalid'),
            'website.pattern' => $lang('website_invalid'),
        ]);

        $isSaved = false;

        /**
         * @var \Application\Models\UserSettings
         */
        $userSM = Model::get('\Application\Models\UserSettings');


        if ($request->getHTTPMethod() === 'POST' && $formValidator->validate()) {
            $snapchat = $formValidator->getValue('snapchat');
            $linkedIn = $formValidator->getValue('linkedin');
            $insta = $formValidator->getValue('insta');
            $youtube = $formValidator->getValue('youtube');
            $facebook = $formValidator->getValue('facebook');
            $telegram = $formValidator->getValue('telegram');
            $twitter = $formValidator->getValue('twitter');
            $website = $formValidator->getValue('website');

            $userSM->put($userInfo['id'], UserSettings::KEY_SOCIAL_SNAPCHAT, $snapchat);
            $userSM->put($userInfo['id'], UserSettings::KEY_SOCIAL_LINKEDIN, $linkedIn);
            $userSM->put($userInfo['id'], UserSettings::KEY_SOCIAL_INSTAGRAM, $insta);
            $userSM->put($userInfo['id'], UserSettings::KEY_SOCIAL_YOUTUBE, $youtube);
            $userSM->put($userInfo['id'], UserSettings::KEY_SOCIAL_FACEBOOK, $facebook);
            $userSM->put($userInfo['id'], UserSettings::KEY_SOCIAL_TELEGRAM, $telegram);
            $userSM->put($userInfo['id'], UserSettings::KEY_SOCIAL_TWITTER, $twitter);
            $userSM->put($userInfo['id'], UserSettings::KEY_SOCIAL_WEBSITE, $website);

            $isSaved = true;
        }

        $snapchat = $userSM->take($userInfo['id'], UserSettings::KEY_SOCIAL_SNAPCHAT);
        $linkedIn = $userSM->take($userInfo['id'], UserSettings::KEY_SOCIAL_LINKEDIN);
        $insta = $userSM->take($userInfo['id'], UserSettings::KEY_SOCIAL_INSTAGRAM);
        $youtube = $userSM->take($userInfo['id'], UserSettings::KEY_SOCIAL_YOUTUBE);
        $facebook = $userSM->take($userInfo['id'], UserSettings::KEY_SOCIAL_FACEBOOK);
        $telegram = $userSM->take($userInfo['id'], UserSettings::KEY_SOCIAL_TELEGRAM);
        $twitter = $userSM->take($userInfo['id'], UserSettings::KEY_SOCIAL_TWITTER);
        $website = $userSM->take($userInfo['id'], UserSettings::KEY_SOCIAL_WEBSITE);

        $view = new View();
        $view->set('Profile/edit', [
            'section' => $section,
            'data' => [
                'snapchat' => $snapchat,
                'linkedIn' => $linkedIn,
                'insta' => $insta,
                'youtube' => $youtube,
                'facebook' => $facebook,
                'telegram' => $telegram,
                'twitter' => $twitter,
                'website' => $website,
                'isSaved' => $isSaved
            ]
        ]);
        $view->prepend('header', [
            'title' => "Edit Profile"
        ]);
        $view->append('footer');

        $response->set($view);
    }
}