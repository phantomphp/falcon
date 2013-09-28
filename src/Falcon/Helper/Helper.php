<?php

namespace Falcon\Helper;

class Helper
{
    public static function generateUuid($token = null)
    {
        mt_srand();
        $token = sha1(mt_rand() . microtime() . $token);
        return substr($token, 0, 8) . '-'
            . substr($token, 8, 4) . '-'
            . substr($token, 12, 4) . '-'
            . substr($token, 16, 4) . '-'
            . substr($token, 20, 12);
    }
}