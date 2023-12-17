<?php

namespace Application\Controllers\Ajax;

use Application\Helpers\CacheHelper;
use Application\Main\AuthController;
use Application\Main\ResponseJSON;
use Application\Models\Queue;
use Application\Models\UserSettings;
use Aws\S3\S3Client;
use System\Core\Config;
use System\Core\Model;
use System\Core\Request;
use System\Helpers\Strings;
use System\Helpers\URL;
use System\Libs\File;

class Profile extends AuthController
{
    public function uploadCover()
    {
        $lang = $this->language;

        if ( !isset($_FILES['image']) && $_FILES['image']['error'] == 0 )
            throw new ResponseJSON('error', $lang('error_while_uploading'));

        $file = new File();
        $file->set($_FILES['image']);
        
        // get supported file types
        $supported = Config::get('Website')->images_support;
        $supported = $supported ? $supported : ['image/jpeg'];

        if ( !in_array($file->getMime(), $supported) )            
            throw new ResponseJSON(
                'error',
                $lang('cover_not_supported', [
                    'supported' => '.jpg, .png'
                ])
            );

        $newName = Strings::random(20) . '_' . time() . '.' . $file->getExt();

        // upload the file
        $file->move('Application/Uploads/'. $newName );

        $userInfo = $this->user->getInfo();

        /**
         * @var \Application\Models\UserSettings
         */
        $userSM = Model::get('\Application\Models\UserSettings');
        $oldPhoto = $userSM->take($userInfo['id'], UserSettings::KEY_COVER, $newName);
        $_SESSION['old_file' . $userInfo['id']] = $oldPhoto; // store it in the session to be deleted in aws-s3 later
        $result = $userSM->put($userInfo['id'], UserSettings::KEY_COVER, $newName);
        if ( !$result )
        {
            $file->delete();

            throw new ResponseJSON('error', "Internal server error");
        }

        if ( $oldPhoto )
        {
            // now delete the old photo if found
            $file = new File();
            $file->set(ABS_PATH . 'Application/Uploads/' . $oldPhoto);
            $file->delete();
        }

        CacheHelper::forget(CacheHelper::USER_PROFILE_KEY, $this->user->getInfo()['id']);

        throw new ResponseJSON('success', URL::media('Application/Uploads/' . $newName, 'fit:970,300'));
    }

    public function uploadAvatar(Request $request )
    {
        $lang = $this->language;

//        if ( !isset($_FILES['image']) && $_FILES['image']['error'] == 0 )
//            throw new ResponseJSON('error', $lang('error_while_uploading'));

        $data = str_replace('data:image/png;base64,', '', $request->post('image'));
        $dir = dirname(dirname(__DIR__));
        $newName = Strings::random(20) . '_' . time() . '.' . 'png';
        $fullPath = "{$dir}/Uploads/{$newName}";

        file_put_contents($fullPath, base64_decode($data));


////        $file->set($_FILES['image']);
//        $file->set();
//
//        // get supported file types
//        $supported = Config::get('Website')->images_support;
//        $supported = $supported ? $supported : ['image/jpeg'];
//
//        if ( !in_array($file->getMime(), $supported) )
//            throw new ResponseJSON(
//                'error',
//                $lang('cover_not_supported', [
//                    'supported' => '.jpg, .png'
//                ])
//            );
//
//        $newName = Strings::random(20) . '_' . time() . '.' . $file->getExt();
//
//        // upload the file
//        $file->move('Application/Uploads/'. $newName );
//
        $userInfo = $this->user->getInfo();
//
//        /**
//         * @var \Application\Models\UserSettings
//         */
        $userSM = Model::get('\Application\Models\UserSettings');
        $oldPhoto = $userSM->take($userInfo['id'], UserSettings::KEY_AVATAR, $newName);
        $_SESSION['old_file' . $userInfo['id']] = $oldPhoto; // store it in the session to be deleted in aws-s3 later
        $result = $userSM->put($userInfo['id'], UserSettings::KEY_AVATAR, $newName);
        if ( !$result )
        {
            unlink($fullPath);

            throw new ResponseJSON('error', "Internal server error");
        }

        if ( $oldPhoto )
        {
            // now delete the old photo if found
            $file = new File();
            $file->set(ABS_PATH . 'Application/Uploads/' . $oldPhoto);
            $file->delete();
        }

        CacheHelper::forget(CacheHelper::USER_PROFILE_KEY, $this->user->getInfo()['id']);

        throw new ResponseJSON('success', URL::media('Application/Uploads/' . $newName, 'fit:300,300'));
    }
}
