<?php


namespace App\Statistics;


class StatisticsGenerator
{
    /**
     * @var array
     */
    protected $actions = [];

    /**
     * @var array
     */
    protected $sessions = [];

    /**
     * StatisticsGenerator constructor.
     * @param array $actions
     */
    public function __construct(array $actions)
    {
        $this->actions = $actions;
    }

    /**
     * Calculates time spent on each event
     *
     * @return $this
     */
    public function calculateTime()
    {
        $time = null;

        // calculate time spend
        for ($i = 0; $i < count($this->actions); $i++) {
            if ($time != null) {
                $actionBefore = &$this->actions[$i - 1];
                $actionBefore['timeSpend'] = (int)$this->actions[$i]['unixTime'] - $time;
            }

            if ($this->actions[$i]['action'] === 'OPENED_APP') {
                $time = null;
            } else {
                $time = $this->actions[$i]['unixTime'];
            }
        }

        return $this;
    }

    /**
     * Bundles session by grouping time spent data
     *
     * @return $this
     */
    public function determineSessions()
    {
        $session = [];
        $sessionStarted = false;

        // grouping session data
        foreach ($this->actions as $actionData) {
            $action = $actionData['action'];

            // handle session start
            if ($action === 'OPENED_APP') {
                // handle already started session by adding "CLOSED_APP" directly after last action
                // prevents to long sessions with wrong data
                if ($sessionStarted) {
                    $actionBefore = end($session);

                    $session[] = [
                        'id' => '-1',
                        'action' => 'CLOSED_APP',
                        'timestamp' => $actionBefore['timestamp'],
                        'unixTime' => $actionBefore['unixTime'],
                    ];
                }

                // mark as started
                $sessionStarted = true;
            }

            // handle session end
            if ($action === 'CLOSED_APP') {
                // ignore time to next OPENED_APP
                $actionData['timeSpend'] = '-1';
                $session[] = $actionData;

                // add to session list and clear current session
                $this->sessions[] = $session;
                $session = [];

                // mark end
                $sessionStarted = false;
            }

            if (!$sessionStarted) {
                // ignore everything before session start
                continue;
            }

            // add action to current session
            $session[] = $actionData;
        }

        return $this;
    }

    /**
     * Flattens session by cumulative sum up time spent on each an event-type
     * (Looses info about each time spent on each event but gains more insight in whole sessions)
     *
     * @return array
     */
    public function flattenSessions()
    {
        $flattenedSession = [];
        $lastSessionAction = 'none';

        foreach ($this->sessions as $session) {
            $sums = [];

            $lastAction = 'none';

            foreach ($session as $action) {
                $actioName = $action['action'];
                $time = isset($action['timeSpend']) && $action['timeSpend'] > 0 ? $action['timeSpend'] : 0;

                if (empty($sums)) {
                    $sums['TIME'] = [
                        'START' => $action['timestamp'],
                        'END' => 0,
                    ];
                }
                $sums['TIME']['END'] = $action['timestamp'];

                $sums[$actioName]['amount'] += 1;
                $sums[$actioName]['time'] += $time;


                if ($actioName !== 'CLOSED_APP' && $actioName !== 'OPENED_APP') {
                    $lastAction = $actioName;
                }

            }

            /**
             * Use time from last action in last session
             * Is need if Session only consists of OPEN and CLOSE (current view is last NAV_* action from before)
             *
             * Example:
             * User Opens app navigates to NAV_EXPLORE
             * User Closes app and reopens
             * --> No new NAV_EXPLORE event is triggered as no navigation took action
             */
            if ($lastSessionAction != 'none' && $sums['OPENED_APP']['time'] >= 0) {
                $openTime = $sums['OPENED_APP']['time'];

                // clear opened_app time
                $sums['OPENED_APP']['time'] = 0;

                // set data based on last session
                $sums[$lastSessionAction]['amount'] += 1;
                $sums[$lastSessionAction]['time'] += $openTime;

            }

            if ($lastAction !== 'none') {
                $lastSessionAction = $lastAction;
            }

            $flattenedSession[] = $sums;
        }

        return $flattenedSession;
    }
}