<?php

namespace App\Service;

class Censurator
{

    public function purify($string): string
    {
        $fuckingWord = ["fuck", "fion", "merde", "enculé", "pussy", "chatGpt", "danser"];

        return str_replace($fuckingWord, '*****', $string);
        // return in_array($word, self::fuckingWord ? str_repeat('*', strlen($word)) : $word;
    }
}