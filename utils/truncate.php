<?php
declare(strict_types=1);
/**
 * truncateText
 *
 * @param  string $string
 * @param  int $maxLength
 * @return string
 */
function truncateText(string $string,int $maxLength) :string
{
    $wordsArr = explode(' ',$string);
    $truncatedText = '';
    foreach($wordsArr as $word){
        $truncatedTextLength = strlen($truncatedText);
        if($truncatedTextLength + strlen($word) <= $maxLength){
            $truncatedText .= ' '.$word;
            $truncatedTextLength += strlen($word)+1;
        }
        else break;
       
    }
    return $truncatedText.' [...]';
}