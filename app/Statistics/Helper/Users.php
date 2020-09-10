<?php

namespace App\Statistics\Helper;

class Users
{
    const GROUP_TRIAL = 'TrialGroup';
    const GROUP_CONTROL = 'ControlGroup';
    
    const COLUMN_USER_GROUP = 'userGroup';
    const COLUMN_USER_SMALL_ID = 'usersmallid'; // easier handling for a user in visualization
    
    /**
     * List of participants of the app with their respective user hash
     * Fixed data, to have same usersmallid and additional data
     * @TODO: As this was only a proof-of-concept study this may be automated in the future (query with group by user id)
     *
     * @return array
     */
    public static function get()
    {
        return ([
            '<hashed-user-id-1>' => [
                self::COLUMN_USER_GROUP => self::GROUP_TRIAL,
                self::COLUMN_USER_SMALL_ID => '1',
            ],
            '<hashed-user-id-2>' => [
                self::COLUMN_USER_GROUP => self::GROUP_CONTROL,
                self::COLUMN_USER_SMALL_ID => '2',
            ],
            // ...
        ]);
    }
}