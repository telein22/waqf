<?php

namespace Application\Helpers;

use Application\Models\User;
use Application\Models\UserSettings;
use Application\Models\UserSpecialty;
use Application\Models\UserSubSpecialty;
use Application\ThirdParties\AWS\AWS;
use System\Core\Application;
use System\Core\Model;

class UserHelper
{
    public static function prepare($data)
    {
        if (empty($data)) return array();

        $userIds = [];
        foreach ($data as $item) {
            $userIds[$item['id']] = $item['id'];
        }

        /**
         * @var UserSpecialty
         */
        $userSM = Model::get(UserSpecialty::class);
        $specsMain = $userSM->getByUserIds($userIds);

        /**
         * @var UserSubSpecialty
         */
        $userSM = Model::get(UserSubSpecialty::class);
        $specs = $userSM->getByUserIds($userIds);

        foreach ($data as &$item) {
            $item['sub_specialty'] = isset($specs[$item['id']]) ? $specs[$item['id']] : null;
            $item['specialty'] = isset($specsMain[$item['id']]) ? $specsMain[$item['id']] : null;
        }

        return $data;
    }

    public static function getAvatarUrl($params = null, $id = null)
    {
        $default = AWS::getFileURL('default-avatar.jpg');

        if (!$id = User::getId($id)) {
            return $default;
        }

        $userSM = Model::get('\Application\Models\UserSettings');
        $avatarFileName = $userSM->take($id, UserSettings::KEY_AVATAR);

        return $avatarFileName ? AWS::getFileURL($avatarFileName) : $default;
    }

    public static function getCoverUrl($params = null, $id = null)
    {
        $default = AWS::getFileURL('default-cover.jpg');

        if (!$id = User::getId($id)) {
            return $default;
        }

        $userSM = Model::get('\Application\Models\UserSettings');
        $coverFileName = $userSM->take($id, UserSettings::KEY_COVER);

        return $coverFileName ? AWS::getFileURL($coverFileName) : $default;
    }

    public static function genderText($gender)
    {
        /**
         * @var \Application\Models\Language
         */
        $lang = Model::get('\Application\Models\Language');

        return $gender == 1 ? $lang('male') : $lang('female');
    }

}