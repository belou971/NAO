<?php
/**
 * Created by PhpStorm.
 * User: belou
 * Date: 12/05/18
 * Time: 19:19
 */
namespace TS\NaoBundle\Component;

class ActionType {
    /*  Action References in route    */
    const LOAD_SPECIMENS                 = "load_specimens";
    const SEARCH_SPECIMEN_BY_NAME        = "search_specimen_by_name";
    const SEARCH_SPECIMEN_BY_CITY        = "search_specimen_by_city";
    const SEARCH_SPECIMEN_BY_COORD       = "search_specimen_by_coord";
    const ZOOM_MAX                       = "zoom_max";
    const LAST_OBSERVATIONS              = "last_observations";
    const READ_OBSERVATION               = "read_observation";

    /**
     * List of all references of messages
     *
     * @var array
     */
    private static $table = [
        self::LOAD_SPECIMENS,
        self::SEARCH_SPECIMEN_BY_NAME,
        self::SEARCH_SPECIMEN_BY_CITY,
        self::SEARCH_SPECIMEN_BY_COORD,
        self::ZOOM_MAX,
        self::LAST_OBSERVATIONS,
        self::READ_OBSERVATION
    ];

    /**
     * @param  string $reference Represents a message reference
     * @return bool returns true if that reference exists in the reference table, false otherwise
     */
    public static function exists($reference)
    {
        return in_array($reference, self::$table);
    }
}