<?php

namespace App\Statistics\Helper;

use App\Models\Action;

class SQLGenerator
{
    /**
     * @var string
     */
    public const STAT_TABLE = 'stats';

    /**
     * Removes stats table if exists
     *
     * @return string
     */
    public static function getDropTableSql()
    {
        return 'DROP TABLE ' . self::STAT_TABLE;
    }

    /**
     * Generate stats table with all currently used columns
     *
     * @return string
     */
    public static function getCreateTableSql()
    {
        $sql = 'CREATE TABLE `' . self::STAT_TABLE . '` (';

        // special columns
        $sql .= '`id` bigint(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY, ';
        $sql .= '`userId` varchar(255) NOT NULL, ';
        $sql .= '`usergroup` varchar(255) NOT NULL, ';
        $sql .= '`usersmallid` smallint(1) NOT NULL, ';
        $sql .= '`TIME_START` TIMESTAMP NULL, ';
        $sql .= '`TIME_END` TIMESTAMP NULL, ';

        // action columns
        foreach (Action::getActionKeys() as $actionName) {
            $sql .= '`' . $actionName . '_amount` int(32) NOT NULL DEFAULT 0, ';
            $sql .= '`' . $actionName . '_time` int(32) NOT NULL DEFAULT 0,';
        }

        $sql = trim($sql, ', ') . ');' . "\n";

        return $sql;
    }

    /**
     * Prepare data for insert in table stated above
     *
     * @param $userId
     * @param $userGroup
     * @param $userSmallId
     * @param array $flattenSessions
     * @return array
     */
    public static function generateInsertData($userId, $userGroup, $userSmallId, array $flattenSessions)
    {
        $insertDataArray = [];

        foreach ($flattenSessions as $sessiondata) {
            $insertData = [];
            $insertData['userId'] = $userId;
            $insertData['usergroup'] = $userGroup;
            $insertData['usersmallid'] = $userSmallId;

            foreach ($sessiondata as $action => $data) {
                foreach ($data as $dataType => $value) {
                    $insertData[$action . '_' . $dataType] = $value;
                }
            }

            $insertDataArray[] = $insertData;
        }

        return $insertDataArray;
    }
}