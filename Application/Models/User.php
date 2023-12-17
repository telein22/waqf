<?php

namespace Application\Models;

use Application\Controllers\Review;
use System\Core\Model;
use System\Models\AbstractAuth;

class User extends AbstractAuth 
{
    const TYPE_ENTITY = 'entity';
    const TYPE_SUBSCRIBER = 'subscriber';
    const TYPE_ADMIN = 'admin';

    protected $_table = 'users';

    private $_canCreateWorkshop = [];

    public function usernameColumn()
    {
        return 'email';
    }
    
    public function passwordColumn()
    {
        return 'password';
    }

    public function uIdColumn()
    {
        return 'id';
    }

    public function verifyPassword($pass, $enc)
    {
        return password_verify($pass, $enc);
    }
    public function getTable()
    {
        return $this->_table;
    }

    public function isVerified()
    {
        $info = $this->getInfo();

        return isset($info['email_verified']) && $info['email_verified'] == 1;
    }

    public function isBlocked()
    {
        $info = $this->getInfo();

        return isset($info['suspended']) && $info['suspended'] == 1 && $info['type'] == self::TYPE_SUBSCRIBER;
    }
    
    public function create( $data )
    {
        return $this->_db->insert($this->_table, $data);
    }

    public function update( $data, $id = null )
    {
        if ( !$id = self::getId($id) ) return false;

        return $this->_db->update($this->_table, $id, $data);
    }

    public static function getId( $id = null )
    {
        $userM = Model::get('\Application\Models\User');
        if ( is_null($id) && $userM->isLoggedIn() )
        {
            $info = $userM->getInfo();
            $id = $info['id'];
        }

        return $id;
    }

    public function checkUserNameExists( $userName, $except = null )
    {
        $dbValues = array(
            $userName
        );
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `username` = ?";

        if( !empty($except) )
        {
            $dbValues[] = $except;
            $SQL .= ' AND `id` != ?';
        }

        return $this->_db->query($SQL, $dbValues)->get();
    }

    public function checkEmailExists( $email, $except = null )
    {
        $dbValues = array(
            $email
        );
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `email` = ?";

        if( !empty($except) )
        {
            $dbValues[] = $except;
            $SQL .= ' AND `id` != ?';
        }

        return $this->_db->query($SQL, $dbValues)->get();
    }

    public function checkPhoneExists( $phone, $except = null )
    {
        $dbValues = array(
            $phone
        );
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `phone` = ?";

        if( !empty($except) )
        {
            $dbValues[] = $except;
            $SQL .= ' AND `id` != ?';
        }

        return $this->_db->query($SQL, $dbValues)->get();
    }

    public function getUser( $id )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `id` = ?";
        return $this->_db->query($SQL, [$id])->get();
    }

    public function getActiveUsers( $limit = null )
    {
        $dbValues = [];

        /**
         * @var \Application\Models\UserSettings
         */
        $userSM = Model::get('\Application\Models\UserSettings');
        $userST = $userSM->getTable();

        $userSplM = Model::get('\Application\Models\UserSpecialty');
        $userSplT = $userSplM->getTable();

        $userSubSplM = Model::get('\Application\Models\UserSubSpecialty');
        $userSubSplT = $userSubSplM->getTable();

        $keyGender = UserSettings::KEY_GENDER;
        $keyPhone = UserSettings::KEY_PHONE;
        $keyDob = UserSettings::KEY_DOB;
        $keyCountry = UserSettings::KEY_COUNTRY;
        $keyCity = UserSettings::KEY_CITY;
        $keyBank1 = UserSettings::KEY_BANK1;
        $keyBank2 = UserSettings::KEY_BANK2;

        $SQL = "SELECT * FROM `{$this->_table}` 
                WHERE `suspended` = 0
                AND `id` IN (
                    SELECT `user_id` FROM `{$userST}`
                    WHERE `key` = '$keyGender'
                )
                AND `id` IN (
                    SELECT `user_id` FROM `{$userST}`
                    WHERE `key` = '$keyPhone'
                )
                AND `id` IN (
                    SELECT `user_id` FROM `{$userST}`
                    WHERE `key` = '$keyDob'
                )
                AND `id` IN (
                    SELECT `user_id` FROM `{$userST}`
                    WHERE `key` = '$keyCountry'
                )
                AND `id` IN (
                    SELECT `user_id` FROM `{$userST}`
                    WHERE `key` = '$keyCity'
                )
                AND `id` IN (
                    SELECT `user_id` FROM `{$userST}`
                    WHERE `key` = '$keyBank1'
                )
                AND `id` IN (
                    SELECT `user_id` FROM `{$userST}`
                    WHERE `key` = '$keyBank2'
                )
                AND `id` IN (
                    SELECT `user_id` FROM `{$userSplT}`
                )
                AND `id` IN (
                    SELECT `user_id` FROM `{$userSubSplT}`
                )
                ";

        if( !empty($limit) && is_numeric($limit) )
        {
            $SQL .= ' ORDER BY `account_verified` DESC, `id` DESC LIMIT ?';
            $dbValues[] = $limit;
        }
        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function getUserByEmail( $email )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `email` = ? AND `suspended` = ?";
        return $this->_db->query($SQL, [$email, 0])->get();
    }

    public function getUserByPhone( $phone )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `phone` = ?";
        return $this->_db->query($SQL, [$phone])->get();
    }

    public function all( $limit = null ) {
        $dbValues = [];

        $SQL = "SELECT * FROM `users`";

        if( !empty($limit) )
        {
            $SQL .= ' ORDER BY `id` DESC LIMIT ?';
            $dbValues[] = $limit;
        }

        $result = $this->_db->query($SQL, $dbValues)->getAll();

        return $result;
    }

    public function getInfoByIds( $ids )
    {
        if ( empty($ids) ) return [];

        $ids = (array) $ids;
        $SQL = "SELECT * FROM `{$this->_table}` 
            WHERE `id` IN ";
        
        $placeholder = array_fill(0, count($ids), '?');
        $values = array_values($ids);

        $SQL .= " (" . implode(', ', $placeholder) . ")";

        $result = $this->_db->query($SQL, $values)->getAll();
        
        if ( !$result ) return [];

        $output = [];
        foreach ( $result as $row )
        {
            $output[$row['id']] = $row;
        }

        return $output;
    }

    public function getInfoByUsernames( $usernames )
    {
        if ( empty($usernames) ) return [];

        $usernames = (array) $usernames;
        $SQL = "SELECT * FROM `{$this->_table}` 
            WHERE `username` IN ";
        
        $placeholder = array_fill(0, count($usernames), '?');
        $values = array_values($usernames);

        $SQL .= " (" . implode(', ', $placeholder) . ")";

        $result = $this->_db->query($SQL, $values)->getAll();
        
        if ( !$result ) return [];

        $output = [];
        foreach ( $result as $row )
        {
            $output[$row['username']] = $row;
        }

        return $output;
    }

    public function canCreateWorkshop( $id = null )
    {
        $id = self::getId($id);
        $userInfo = self::getInfo();
        if ( isset($this->_canCreateWorkshop[$id]) ) return $this->_canCreateWorkshop[$id];

         /**
         * @var \Application\Models\UserSettings
         */
        $userSM = Model::get('\Application\Models\UserSettings');

        $userSplM = Model::get('\Application\Models\UserSpecialty');
        $userSubSplM = Model::get('\Application\Models\UserSubSpecialty');
        
        $gender = $userSM->take($id, UserSettings::KEY_GENDER);
        $dob = $userSM->take($id, UserSettings::KEY_DOB);
        $country = $userSM->take($id, UserSettings::KEY_COUNTRY);
        $city = $userSM->take($id, UserSettings::KEY_CITY);

        $bank1 = $userSM->take($id, UserSettings::KEY_BANK1);
        $bank2 = $userSM->take($id, UserSettings::KEY_BANK2);

        $uspl = $userSplM->getSpl($id);
        $usubspl = $userSubSplM->getUserSpl($id);
        
        // $bank3 = $userSM->take($id, UserSettings::KEY_BANK3);
        if( $this->isEntity($userInfo) ) {
            $gender = true;
            $dob = true;
            $usubspl = true;
        }

        $canCreate = $gender && $dob && $country && $city && $uspl && $usubspl;

        return $this->_canCreateWorkshop[$id] = $canCreate;
    }

    public function searchBySpec( $specId, $onlyActiveUsers = false )
    {
        /**
         * @var UserSpecialty
         */
        $specM = Model::get(UserSpecialty::class);
        $specTable = $specM->getTable();

        $SQL = "SELECT * FROM `{$this->_table}`
                WHERE `suspended` = 0
                AND `id` IN (
                    SELECT `user_id` FROM `{$specTable}`
                    WHERE `specialty` = ?
                )";
        
        if( !$onlyActiveUsers )
        {
            /**
             * @var \Application\Models\UserSettings
             */
            $userSM = Model::get('\Application\Models\UserSettings');
            $userST = $userSM->getTable();
            
            $userSubSplM = Model::get('\Application\Models\UserSubSpecialty');
            $userSubSplT = $userSubSplM->getTable();

            $keyGender = UserSettings::KEY_GENDER;
            $keyPhone = UserSettings::KEY_PHONE;
            $keyDob = UserSettings::KEY_DOB;
            $keyCountry = UserSettings::KEY_COUNTRY;
            $keyCity = UserSettings::KEY_CITY;
            $keyBank1 = UserSettings::KEY_BANK1;
            $keyBank2 = UserSettings::KEY_BANK2;

            $SQL .= " AND `id` IN (
                        SELECT `user_id` FROM `{$userST}`
                        WHERE `key` = '$keyGender'
                    )
                    AND `id` IN (
                        SELECT `user_id` FROM `{$userST}`
                        WHERE `key` = '$keyPhone'
                    )
                    AND `id` IN (
                        SELECT `user_id` FROM `{$userST}`
                        WHERE `key` = '$keyDob'
                    )
                    AND `id` IN (
                        SELECT `user_id` FROM `{$userST}`
                        WHERE `key` = '$keyCountry'
                    )
                    AND `id` IN (
                        SELECT `user_id` FROM `{$userST}`
                        WHERE `key` = '$keyCity'
                    )
                    AND `id` IN (
                        SELECT `user_id` FROM `{$userST}`
                        WHERE `key` = '$keyBank1'
                    )
                    AND `id` IN (
                        SELECT `user_id` FROM `{$userST}`
                        WHERE `key` = '$keyBank2'
                    )
                    AND `id` IN (
                        SELECT `user_id` FROM `{$specTable}`
                    )
                    AND `id` IN (
                        SELECT `user_id` FROM `{$userSubSplT}`
                    ) ";
        }

        return $this->_db->query($SQL, [$specId])->getAll();
    }

    public function search(
        $term,
        $subSpecs = null,
        $userIds = null,
        $skip = null,
        $limit = null,
        $searchedBy = null,
        $ignoreSuspended = false,
        $specs = null,
        $onlyVerified = false,
        $entityId = null
    )
    {


        /**
         * @var User
         */
        $userM = Model::get(User::class);
        $userId = 0;
        if ($userM->isLoggedIn()) {
            $userId = $userM->getInfo();
            $userId = $userId['id'];
        }

        /**
         * @var UserSpecialty
         */
        $specM = Model::get(UserSpecialty::class);
        $specTable = $specM->getTable();

        /**
         * @var UserSubSpecialty
         */
        $subM = Model::get(UserSubSpecialty::class);
        $subTable = $subM->getTable();

        /**
         * @var Reviews
         */
        $reviewM = Model::get(Reviews::class);
        $reviewTable = $reviewM->getTable();

        $RSQL = "SELECT
                    `entity_owner_id`,
                    AVG(`star`) as `rate`,
                    COUNT(`id`) AS `totalRate`
                FROM `{$reviewTable}` GROUP BY `entity_owner_id`";

        $SQL = "SELECT DISTINCT
                `u`.*,
                `r`.`rate`,
                `r`.`totalRate`
            FROM `{$this->_table}` AS `u`            
            LEFT JOIN `{$specTable}` AS `st`
            ON ( `u`.`id` = `st`.`user_id` )
            LEFT JOIN `{$subTable}` AS `s`
            ON ( `u`.`id` = `s`.`user_id` )
            LEFT JOIN ({$RSQL}) AS `r`
            ON ( `u`.`id` = `r`.`entity_owner_id` )
            WHERE `u`.`name` LIKE ? ";

        if ($onlyVerified) {
            $SQL .= " AND `u`.`account_verified` = 1"; // I want only the verified account
        }

        if ($entityId) {
            $SQL .= " AND (`u`.`entity_id` = {$entityId} OR `u`.`id` = {$entityId})";
        } else {
            $SQL .= " AND `u`.`id` <> {$userId}";
        }



        if ($ignoreSuspended) {
            $SQL .= " AND `u`.`suspended` = 0 ";
        }

        $dbValues = ['%' . $term . '%'];
//        $dbValues[] = $userId;

        $specs = (array)$specs;
        if (!empty($specs)) {

            $placeHolders = array_fill(0, count($specs), '?');
            $values = array_values($specs);

            $placeHolders = implode(', ', $placeHolders);

            $SQL .= " AND `st`.`specialty` iN ({$placeHolders}) ";
            $dbValues = array_merge($dbValues, $values);
        }

        $subSpecs = (array)$subSpecs;
        if (!empty($subSpecs)) {

            $placeHolders = array_fill(0, count($subSpecs), '?');
            $values = array_values($subSpecs);

            $placeHolders = implode(', ', $placeHolders);

            $SQL .= " AND `s`.`specialty` iN ({$placeHolders}) ";
            $dbValues = array_merge($dbValues, $values);
        }

        if (!empty($userIds)) {
            $userIds = (array)$userIds;

            $placeHolders = array_fill(0, count($userIds), '?');
            $values = array_values($userIds);

            $placeHolders = implode(', ', $placeHolders);

            $SQL .= " AND `u`.`id` iN ({$placeHolders}) ";
            $dbValues = array_merge($dbValues, $values);
        }

        if ($searchedBy) {
            /**
             * @var Follow
             */
            $followM = Model::get(Follow::class);
            $followTable = $followM->getTable();

            $SQL .= " AND `u`.`id` NOT IN (
                    SELECT `follow` FROM `{$followTable}`
                    WHERE `follower` = ?
                ) AND `u`.`id` <> ?";

            $dbValues[] = $searchedBy;
            $dbValues[] = $searchedBy;
        }

        $SQL .= " ORDER BY `u`.`account_verified` DESC, `r`.`rate` DESC, `r`.`totalRate` DESC, `u`.`id` DESC ";
//            $SQL .= " ORDER BY `u`.`account_verified` DESC";

        if (is_numeric($skip) && is_numeric($limit)) {
            $SQL .= " LIMIT ?, ? ";
            $dbValues[] = (int)$skip;
            $dbValues[] = (int)$limit;
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function isEntity($user)
    {
        return $user['type'] == self::TYPE_ENTITY;
    }

    public function getEntityById($entityId)
    {
        if (is_null($entityId)) {
            return null;
        }

        $SQL = "SELECT * FROM `{$this->_table}` WHERE `type` = ? AND id = ? AND `suspended` = ?";
        return $this->_db->query($SQL, [
            self::TYPE_ENTITY,
            $entityId,
            0
        ])->get();
    }

    public function getEntities()
    {
        $SQL = "SELECT id, name FROM `{$this->_table}` WHERE `type` = ? AND `suspended` = ?";
        return $this->_db->query($SQL, [
            self::TYPE_ENTITY,
            0
        ])->getAll();
    }

    public function getEntitiesWithAssociatesCount()
    {
        $SQL = "SELECT entities.id,entities.joined_at, entities.name, COUNT(CASE WHEN users.id IS NULL THEN null ELSE 1 END) AS associates_count 
                FROM `{$this->_table}` entities LEFT OUTER JOIN `{$this->_table}` ON (entities.id = users.entity_id) 
                WHERE entities.type = ? GROUP BY entities.name, entities.id ORDER BY entities.id DESC;";

        return $this->_db->query($SQL, [
            self::TYPE_ENTITY
        ])->getAll();
    }

    public function getAssociatesCount($userId, $ignoreSuspended = false)
    {
        $SQL = "SELECT count(*) as associates_count FROM `{$this->_table}` WHERE `entity_id` = ?";

        if ($ignoreSuspended) {
            $SQL .= ' AND `suspended` = 0';
        }

        $result = $this->_db->query($SQL, [
            $userId
        ])->get();

        return $result ? $result['associates_count'] : 0;
    }

    public function getAssociates( $entityId, $skip = null, $limit = null, $ignoreSuspended = false )
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `entity_id` = ?";

        if ($ignoreSuspended) {
            $SQL .= ' AND `suspended` = 0';
        }

        $dbValues = [$entityId];

        if ( is_numeric($skip) && is_numeric($limit) )
        {
            $SQL .= " LIMIT ?, ? ";
            $dbValues[] = (int) $skip;
            $dbValues[] = (int) $limit;
        }

        return $this->_db->query($SQL, $dbValues)->getAll();
    }

    public function getVerifiedUsers()
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `email_verified` = ? ORDER BY `id` DESC";
        return $this->_db->query($SQL, [
            1
        ])->getAll();
    }

    public function getUsersCount()
    {
        $SQL = "SELECT count(*) as usersCount FROM `{$this->_table}`";
        $res = $this->_db->query($SQL)->get();

        return $res['usersCount'];
    }

    public function getHighRatedUsers()
    {
        $SQL = "SELECT * FROM `{$this->_table}` INNER JOIN 
                    (  SELECT entity_owner_id AS user_id, AVG(star) AS rating FROM `reviews` GROUP by entity_owner_id ) AS reviews 
                          ON (users.id = reviews.user_id) ORDER BY reviews.rating DESC;";
        return $this->_db->query($SQL)->getAll();
    }

    public function markBusinessCardASRecieved($id)
    {
        $SQL = "UPDATE `{$this->_table}` SET `received_bc` = 1 WHERE `id` = ?";

        return $this->_db->query($SQL, [$id]);
    }

    public function checkUsername($username)
    {
        $SQL = "SELECT count(*) as usersCount FROM `{$this->_table}` WHERE `username` = ?";
        $res = $this->_db->query($SQL, [
            $username
        ])->get();

        return ($res['usersCount'] && $res['usersCount'] > 0) ? false : true ;
    }

    public function getEntitiesForDashboard()
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `type` = ? AND `email_verified` = 1 AND `account_verified` = 1  AND `suspended` = 0 ORDER BY `account_verified` DESC";

        return $this->_db->query($SQL, [
            self::TYPE_ENTITY
        ])->getAll();
    }

    public function getEntityByName(string $name)
    {
        $SQL = "SELECT * FROM `{$this->_table}` WHERE `name` = ? AND `type` = ? AND `email_verified` = 1  AND `suspended` = 0 ";

        return $this->_db->query($SQL, [
            $name,
            self::TYPE_ENTITY
        ])->get();
    }

    public function cancelMembership(int $entityId, int $memberId)
    {
        $SQL = "UPDATE `{$this->_table}` SET `entity_id` = null WHERE `id` = ? and entity_id = ?";

        return $this->_db->query($SQL, [$memberId, $entityId ]);
    }

    public static function isCharity(): bool
    {
        $session = Model::get("\System\Models\Session");
        $user = $session->take('userInfo');

        return $user['is_charity'] == 1;
    }
}