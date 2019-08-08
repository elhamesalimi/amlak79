<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 07/03/2019
 * Time: 11:28 AM
 */

namespace App\Systems;


class Random
{

    static function generate($len, $id=null)
    {
        $num_len = strlen($id);
        $length = $len-$num_len;
        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length) . $id;
    }
}