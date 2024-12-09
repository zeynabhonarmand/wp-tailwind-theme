<?php
function toEnglishNumerals($input_str) {
    // Mapping Farsi numerals to English numerals
    $farsi_to_english = array(
        '۰' => '0',
        '۱' => '1',
        '۲' => '2',
        '۳' => '3',
        '۴' => '4',
        '۵' => '5',
        '۶' => '6',
        '۷' => '7',
        '۸' => '8',
        '۹' => '9'
    );

    // Replace Farsi numerals with English numerals in the input string
    return strtr($input_str, $farsi_to_english);
}
function toFarsiNumerals($str)
{
    $numMap = [
        '0' => '۰',
        '1' => '۱',
        '2' => '۲',
        '3' => '۳',
        '4' => '۴',
        '5' => '۵',
        '6' => '۶',
        '7' => '۷',
        '8' => '۸',
        '9' => '۹'
    ];

    // Convert numbers to string if they are not
    if (!is_string($str)) {
        $str = strval($str);
    }

    // Replace each Arabic numeral with its Persian equivalent
    return strtr($str, $numMap);
}

