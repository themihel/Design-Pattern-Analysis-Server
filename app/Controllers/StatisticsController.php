<?php

namespace App\Controllers;

use App\Models\Action;
use App\Statistics\Helper\SQLGenerator;
use App\Statistics\Helper\Users;
use App\Statistics\StatisticsGenerator;
use Illuminate\Database\Capsule\Manager as DB;

class StatisticsController extends BaseController
{
    /**
     * Generates sessions out of data points.
     * A session is defined as the period between opening and closing the app.
     * Events like OPENED_APP and CLOSED_APP are relevant.
     * One session is one row in the table including time spent per action and how often they have been called.
     *
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getGenerate($request, $response)
    {
        // Drop if table exists
        if (DB::schema()->hasTable(SQLGenerator::STAT_TABLE)) {
            DB::statement(SQLGenerator::getDropTableSql());
        }

        // Create new (fresh) table
        DB::statement(SQLGenerator::getCreateTableSql());

        // generate session data based on users specified in file
        foreach (Users::get() as $userId => $userData) {
            $actions = Action::where(Action::COLUMN_USER_ID, '=', $userId)->get()->toArray();

            $statsGenerator = new StatisticsGenerator($actions);

            $data = $statsGenerator->calculateTime()
                ->determineSessions()
                ->flattenSessions();

            // prepare insert data
            $insertData = SQLGenerator::generateInsertData(
                $userId,
                $userData[Users::COLUMN_USER_GROUP],
                $userData[Users::COLUMN_USER_SMALL_ID],
                $data
            );

            // insert data into database
            foreach ($insertData as $entry) {
                DB::table(SQLGenerator::STAT_TABLE)->insert($entry);
            }
        }
    }
}