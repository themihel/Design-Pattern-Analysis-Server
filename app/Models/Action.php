<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    /**
     * @var string
     */
    public const COLUMN_USER_ID = 'userId';

    /**
     * @var string
     */
    public const COLUMN_ACTION = 'action';

    /**
     * @var string
     */
    public const COLUMN_VERSION = 'version';

    /**
     * @var string
     */
    public const COLUMN_TIMESTAMP = 'timestamp';

    /**
     * Eloquent configuration
     *
     * @var string
     */
    protected $table = 'actions';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        self::COLUMN_USER_ID,
        self::COLUMN_ACTION,
        self::COLUMN_VERSION,
        self::COLUMN_TIMESTAMP,
    ];

    public static function getActionKeys()
    {
        $actionRows =  self::select(self::COLUMN_ACTION)->groupBy(self::COLUMN_ACTION)->get();

        $actions = [];
        foreach ($actionRows as $row) {
            $actions[] = $row->action;
        }

        return $actions;
    }
}
