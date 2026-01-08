<?php

class Helper
{
    public static function slugify($string)
    {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^\pL\d]+/u', '-', $string);
        $string = trim($string, '-');
        return $string;
    }
}
