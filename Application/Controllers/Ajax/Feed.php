<?php

namespace Application\Controllers\Ajax;

use Application\Helpers\FeedHelper;
use Application\Helpers\HashTagHelper;
use Application\Helpers\LiveSessionHelper;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use Application\Models\Feed as FeedM;
use Application\Models\Meeting;
use Application\Models\MeetingApi;
use Application\Models\Workshop;
use Error;
use System\Core\Config;
use System\Core\Controller;
use System\Core\Model;
use System\Core\Request;
use System\core\Response;
use System\Helpers\Strings;
use System\Libs\File;
use System\Libs\FormValidator;
use System\Responses\JSON;
use System\Responses\View;

class Feed extends AuthController
{
    public function post( Request $request, Response $response )
    {
        $lang = $this->language;
        $text = $request->post('text');
        $hasWorkshop = $request->post('workshop_active') == "1";

        $hasError = false;
        $tags = [];

        $userInfo = $this->user->getInfo();

        $dbData = [
            'user_id' => $userInfo['id'],
            'type' =>  FeedM::TYPE_USER_STATUS,
            'data' => []
        ];

        $needTest = true;
        if ( isset($_FILES['image']) && $_FILES['image']['error'] == 0 )
        {
            $needTest = false;
        }

        if( $needTest )
        {
            if ( empty($text) ) throw new ResponseJSON('error', $lang('feed_textarea_empty'));
        }

        if ( mb_strlen($text) > 580 )  throw new ResponseJSON('error', $lang('feed_char_limit_exceeded', [ 'limit' => 580 ]));

        $tags = HashTagHelper::find($text);
        $dbData['data'] = [];

        // check if the work shop is posted.
        if ( $hasWorkshop )
        {
            /**
             * @var \Application\Models\Charity
             */
            $charityM = Model::get('\Application\Models\Charity');
            $charities = $charityM->all();            
            $charityIds = [];
            foreach( $charities as $charity )
            {
                $charityIds[] = $charity['id'];
            }

            $formValidator = FormValidator::instance("workshop");

            // Quick hack for multiselect
            if ( isset($_POST['charity']) && $_POST['charity'][0] == "" )
            {
                unset($_POST['charity']);
            }

            $formValidator->setRules([
                'name' => [
                    'required' => true,
                    'type' => 'string',
                    'maxchar' => 100
                ],
                'desc' => [
                    'required' => true,
                    'type' => 'string',
                ],
                'date' => [
                    'required' => true,
                    'type' => 'string'
                ],
                'time' => [
                    'required' => true,
                    'type' => 'string'
                ],
                'duration' => [
                    'required' => true,
                    'type' => 'number',
                    'min' => 10
                ],
                'price' => [
                    'required' => true,
                    'type' => 'number',
                    'min' => 1
                ],
                'capacity' => [
                    'required' => true,
                    'type' => 'number',
                    'min' => 2,
                    'max' => 500
                ],
                'charity' => [
                    'type' => 'multiselect',
                    'values' => $charityIds
                ],
                'invite' => [
                    'type' => 'string'
                ]
            ])->setErrors([
                'name.required' => $lang('workshop_name_required'),
                'name.maxchar' => $lang('workshop_name_max', [
                    'max' => 100
                ]),
                'desc.required' => $lang('workshop_desc_required'),
                'date.required' => $lang('workshop_date_required'),
                'time.required' => $lang('workshop_time_required'),
                'duration.required' => $lang('workshop_duration_required'),
                'duration.type' => $lang('workshop_duration_should_number'),
                'duration.min' => $lang('workshop_duration_invalid', [
                    'min' => 10
                ]),
                'price.required' => $lang('workshop_price_required'),
                'price.type' => $lang('workshop_price_should_number'),
                'price.min' => $lang('workshop_price_invalid'),
                'capacity.required' => $lang('workshop_capacity_required'),
                'capacity.type' => $lang('workshop_capacity_should_number'),
                'capacity.min' => $lang('workshop_capacity_invalid', [
                    'min' => 2,
                    'max' => 500
                ]),
                'capacity.max' => $lang('workshop_capacity_invalid', [
                    'min' => 2,
                    'max' => 500
                ])
            ]);
            // do the workshop code later.

            if ( ! $formValidator->validate() )
            {
                // we need to output the errors
                $errors = [];
                if ( $formValidator->hasError('name') )
                {
                    $error[] = '<li>' . $formValidator->getError('name') . '</li>';
                }

                if ( $formValidator->hasError('desc') )
                {
                    $error[] = '<li>' . $formValidator->getError('desc') . '</li>';
                }

                if ( $formValidator->hasError('date') )
                {
                    $error[] = '<li>' . $formValidator->getError('date') . '</li>';
                }

                if ( $formValidator->hasError('time') )
                {
                    $error[] = '<li>' . $formValidator->getError('time') . '</li>';
                }

                if ($formValidator->hasError('duration'))
                {
                    $error[] = '<li>' . $formValidator->getError('duration') . '</li>';
                }

                if ( $formValidator->hasError('price') )
                {
                    $error[] = '<li>' . $formValidator->getError('price') . '</li>';
                }

                if ( $formValidator->hasError('charity') )
                {
                    $error[] = '<li>' . $formValidator->getError('charity') . '</li>';
                }

                if ($formValidator->hasError('capacity'))
                {
                    $error[] = '<li>' . $formValidator->getError('capacity') . '</li>';
                }

                throw new ResponseJSON('error', "
                    <ul>
                    " . implode('', $error) . "
                    </ul>
                ");

            }

            if ( !$formValidator->hasError('date') && !$formValidator->hasError('time')  )
            {
                $date = $formValidator->getValue('date');
    
                $currentDate = date('Y-m-d');
    
                if( $date <= $currentDate )
                {
                    $selectedTime = strtotime($formValidator->getValue('time'));
                    $minTime = strtotime('+5 minutes', strtotime(date("H:i", time())));
    
                    if( $selectedTime < $minTime )
                    {
                        throw new ResponseJSON(
                            'error',
                            '<ul><li>'. $lang('workshop_min_time_error', [
                                'min' => date('h:i a', $minTime)
                            ]) .'</li></ul>'
                        );
                    }
                    
                }
            }

            // now check invite
            $username = $formValidator->getValue('invite');
            if ( !empty($username))
            {
                $username = str_replace('@', '', $username);
                if ( $username == $userInfo['username'] )
                {
                    $formValidator->setError('invite', $lang('workshop_invite_error_self'));
                }

                if ( !$formValidator->hasError('invite') )
                {
                    // check if user exist with username
                    $result = $this->user->find(array('username' => $username));
                    if ( !$result )
                    {
                        $formValidator->setError('invite', $lang('workshop_invite_invalid'));
                    } else {
                        // check if the user is valid for inviting workshop
                        $result = $this->user->canCreateWorkshop($result['id']);
                        if (!$result) $formValidator->setError('invite', $lang('work_invite_cant_create'));
                    }
                }
            }

            if ( $formValidator->hasError('invite') )
            {
                throw new ResponseJSON(
                    'error',
                    '<ul><li>' . $formValidator->getError('invite') . '</li></ul>'
                );
            }

            // else valid invite

            $config = Config::get("Website");
            $startTime = $formValidator->getValue('date') . ' ' . $formValidator->getValue('time');
            $endTime = date('Y-m-d H:i:s', strtotime($startTime) + $formValidator->getValue('duration') * 60);
    
            // Now validate allowed.
            $count = LiveSessionHelper::getCountAt($startTime, $endTime);
            $avl = $config->max_allowed_concurrent_session - $count;
            
            if ( $avl < $formValidator->getValue('capacity') + 1 )
            {
                $avl = $avl < 0 ? 0 : $avl;
                throw new ResponseJSON(
                    'error',
                    '<ul><li>' . $lang('you_cant_use_this_time_for_workshop', [ 'count' => $avl ]) . '</li></ul>'
                );
            }

            /**
             * @var \Application\Models\Workshop
             */
            $workshopM = Model::get('\Application\Models\Workshop');
            $charity = $formValidator->getValue('charity');
            $charity = !empty($charity) ? json_encode($charity) : '[]';
            $result = $workshopM->create(array(
                'user_id' => $userInfo['id'],
                'name' => $formValidator->getValue('name'),
                'desc' => $formValidator->getValue('desc'),
                'price' => $formValidator->getValue('price'),
                'capacity' => $formValidator->getValue('capacity'),
                'date' => $formValidator->getValue('date') .' ' . $formValidator->getValue('time'),
                'duration' => $formValidator->getValue('duration'),
                'charity' => $charity,
                'invite' => empty($formValidator->getValue('invite')) ? null : $formValidator->getValue('invite'),
                'status' => Workshop::STATUS_NOT_STARTED,
                'created_at' => time()
            ));

            if ( !$result ) throw new ResponseJSON('error', "Cant create workshop, server error.");

            $dbData['data']['workshop'] = $result;

        }

        // check if the image is posted
        // if posted the process it.
        if ( isset($_FILES['image']) && $_FILES['image']['error'] == 0 )
        {
            $file = new File();
            $file->set($_FILES['image']);
            
            // get supported file types
            $supported = Config::get('Website')->images_support;
            $supported = $supported ? $supported : ['image/jpeg'];

            if ( !in_array($file->getMime(), $supported) ) 
                throw new ResponseJSON(
                    'error',
                    $lang('feed_textarea_file_not_supported', [
                        'supported' => '.jpg, .png'
                    ])
                );

            $newName = Strings::random(20) . '_' . time() . '.' . $file->getExt();
                // upload the file
            $file->move('Application/Uploads/'. $newName );

            $dbData['data']['image'] = $newName;
        }

        $dbData['ref'] = $hasWorkshop ? 'workshop_' . $dbData['data']['workshop'] : null;
        $dbData['created_at'] = time();
        $feedM = Model::get('\Application\Models\Feed');
        $dbData['data'] = json_encode($dbData['data']);
        $dbData['deleted'] = 0;
        $dbData['text'] = $text;

        if ( ! $insertId = $feedM->create($dbData) )
            throw new ResponseJSON(
                'error',
                "Internal server error"
            );

        $this->hooks->dispatch('feed.on_create', [
            'data' => $dbData,
            'text' => $text,
            'feed_id' => $insertId
        ])->later();

        if ( !empty($tags) )
        {
            // insert the tags
            $hashM = Model::get('\Application\Models\HashTags');
            $hashM->createBulk($insertId, FeedM::TYPE_USER_STATUS, $tags);
        }

        throw new ResponseJSON('success', array(
            'insertId' => $insertId,
            'workshop' => $hasWorkshop
        ));
    }

    public function take( Request $request, Response $response )
    {
        $id = $request->post('postId');
        if ( empty($id) ) throw new ResponseJSON('error', 'Invalid params');

        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $feed = $feedM->getFeed($id);

        if ( ! $feed ) throw new ResponseJSON('error', 'Invalid Id');

        $userInfo = $this->user->getInfo();

        $feeds = FeedHelper::prepare([$feed], $userInfo['id']);

        // Now load the view
        $view = new View();
        $view->set('Feed/feed', [
            'userInfo' => $this->user->getInfo(),
            'feed' => $feeds[0]
        ]);
        $content = $view->content();

        throw new ResponseJSON('success', $content);
    }

    public function delete( Request $request, Response $response )
    {
        $id = $request->post('id');

        $feedM = Model::get('\Application\Models\Feed');
        $feedM->delete($id);
    }

    public function moreMedia( Request $request, Response $response )
    {
        $profileId = $request->post('profileId');        
        $fromId = $request->post('fromId');        
        $fromId = !empty($fromId) ? $fromId : null;          

        $userInfo = $this->user->getInfo();

        $limit = 5;

        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $feeds = $feedM->getMediaFeeds($profileId, $fromId, $limit, true);
        $feeds = FeedHelper::prepare($feeds, $userInfo['id']);
        $output = [];
        foreach ( $feeds as $feed )
        {
            $feedViewerM = Model::get('\Application\Models\FeedViewers');

            if( $feed['user_id'] != $userInfo['id'] )
            {
                $feedViewerM->create(array(
                    'feed_id' => $feed['id'],
                    'viewer_id' => $userInfo['id'],
                    'viewed_at' => time()
                ));
            }

            $view = new View();
            $view->set('Feed/feed', [
                'userInfo' => $userInfo,
                'feed' => $feed
            ]);            

            $commentIds = [];
            foreach ( $feed['comments']  as $comment )
            {
                $commentIds[] = $comment['id'];
            }

            $output[] = array(
                'feedId' => $feed['id'],
                'commentIds' => $commentIds,
                'feed' => $view->content()
            );
        }

        throw new ResponseJSON('success', array(
            'feeds' => $output,
            'dataAvl' => count($feeds) == $limit
        ));
    }

    public function moreComment( Request $request, Response $response )
    {
        $profileId = $request->post('profileId');        
        $fromId = $request->post('fromId');        
        $fromId = !empty($fromId) ? $fromId : null;          

        $userInfo = $this->user->getInfo();

        $limit = 5;

        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $feeds = $feedM->getCommentedFeeds($profileId, $fromId, $limit, true);
        $feeds = FeedHelper::prepare($feeds, $userInfo['id']);
        $output = [];
        foreach ( $feeds as $feed )
        {
            $feedViewerM = Model::get('\Application\Models\FeedViewers');

            if( $feed['user_id'] != $userInfo['id'] )
            {
                $feedViewerM->create(array(
                    'feed_id' => $feed['id'],
                    'viewer_id' => $userInfo['id'],
                    'viewed_at' => time()
                ));
            }

            $view = new View();
            $view->set('Feed/feed', [
                'userInfo' => $userInfo,
                'feed' => $feed
            ]);            

            $commentIds = [];
            foreach ( $feed['comments']  as $comment )
            {
                $commentIds[] = $comment['id'];
            }

            $output[] = array(
                'feedId' => $feed['id'],
                'commentIds' => $commentIds,
                'feed' => $view->content()
            );
        }

        throw new ResponseJSON('success', array(
            'feeds' => $output,
            'dataAvl' => count($feeds) == $limit
        ));
    }

    public function moreLiked( Request $request, Response $response )
    {
        $profileId = $request->post('profileId');        
        $fromId = $request->post('fromId');        
        $fromId = !empty($fromId) ? $fromId : null;          

        $userInfo = $this->user->getInfo();

        $limit = 5;

        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $feeds = $feedM->getLikedFeeds($profileId, $fromId, $limit, true);
        $feeds = FeedHelper::prepare($feeds, $userInfo['id']);
        $output = [];
        foreach ( $feeds as $feed )
        {
            $feedViewerM = Model::get('\Application\Models\FeedViewers');

            if( $feed['user_id'] != $userInfo['id'] )
            {
                $feedViewerM->create(array(
                    'feed_id' => $feed['id'],
                    'viewer_id' => $userInfo['id'],
                    'viewed_at' => time()
                ));
            }

            $view = new View();
            $view->set('Feed/feed', [
                'userInfo' => $userInfo,
                'feed' => $feed
            ]);            

            $commentIds = [];
            foreach ( $feed['comments']  as $comment )
            {
                $commentIds[] = $comment['id'];
            }

            $output[] = array(
                'feedId' => $feed['id'],
                'commentIds' => $commentIds,
                'feed' => $view->content()
            );
        }

        throw new ResponseJSON('success', array(
            'feeds' => $output,
            'dataAvl' => count($feeds) == $limit
        ));
    }
    public function more( Request $request, Response $response )
    {
        $fromId = $request->post('fromId');        
        $fromId = !empty($fromId) ? $fromId : null;          

        $userInfo = $this->user->getInfo();

        $limit = 5;

        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $feeds = $feedM->getFeeds($userInfo['id'], $fromId, $limit, true);
        $feeds = FeedHelper::prepare($feeds, $userInfo['id']);

        $output = [];
        foreach ( $feeds as $feed )
        {
            $feedViewerM = Model::get('\Application\Models\FeedViewers');

            if( $feed['user_id'] != $userInfo['id'] )
            {
                $feedViewerM->create(array(
                    'feed_id' => $feed['id'],
                    'viewer_id' => $userInfo['id'],
                    'viewed_at' => time()
                ));
            }

            $view = new View();
            $view->set('Feed/feed', [
                'userInfo' => $userInfo,
                'feed' => $feed
            ]);            

            $commentIds = [];
            foreach ( $feed['comments']  as $comment )
            {
                $commentIds[] = $comment['id'];
            }

            $output[] = array(
                'feedId' => $feed['id'],
                'commentIds' => $commentIds,
                'feed' => $view->content()
            );
        }

        throw new ResponseJSON('success', array(
            'feeds' => $output,
            'dataAvl' => count($feeds) == $limit
        ));
    }

    public function moreProfile( Request $request, Response $response )
    {
        $fromId = $request->post('fromId');        
        $fromId = !empty($fromId) ? $fromId : null;       

        $profileId = $request->post('profileId');

        $userInfo = $this->user->getInfo();

        $limit = 5;

        /**
         * @var \Application\Models\Feed
         */
        $feedM = Model::get('\Application\Models\Feed');
        $feeds = $feedM->getProfileFeeds($profileId, $fromId ,$limit);
        $feeds = FeedHelper::prepare($feeds, $userInfo['id']);

        $output = [];
        foreach ( $feeds as $feed )
        {
            $feedViewerM = Model::get('\Application\Models\FeedViewers');

            if( $feed['user_id'] != $userInfo['id'] )
            {
                $feedViewerM->create(array(
                    'feed_id' => $feed['id'],
                    'viewer_id' => $userInfo['id'],
                    'viewed_at' => time()
                ));
            }

            $view = new View();
            $view->set('Feed/feed', [
                'userInfo' => $userInfo,
                'feed' => $feed
            ]);            

            $commentIds = [];
            foreach ( $feed['comments']  as $comment )
            {
                $commentIds[] = $comment['id'];
            }

            $output[] = array(
                'feedId' => $feed['id'],
                'commentIds' => $commentIds,
                'feed' => $view->content()
            );
        }

        throw new ResponseJSON('success', array(
            'feeds' => $output,
            'dataAvl' => count($feeds) == $limit
        ));
    }
}