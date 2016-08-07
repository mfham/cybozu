<?php

namespace App;

class Common
{

    /**
     * Convert date from stdClass to Array
     */
    public static function convertToArray($value) {
        return json_decode(json_encode($value), true);
    }
}
