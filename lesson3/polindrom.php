<?php

$polindrom = "radar";
echo isPolindrom($polindrom);

/**
 * Проверка слова на полиндром
 * @param string $polindrom
 * @param int $i
 * @return mixed
 */
function isPolindrom(string $polindrom, int $i = 0)
{
    $length = strlen($polindrom);
    $middle = floor($length/2);

    if ($i == $middle) {
        return "Слово \"$polindrom\" является полиндромом";
    }

    if ($polindrom[$i] === $polindrom[$length-1-$i]) {
        $i++;
        return isPolindrom($polindrom, $i);
    }

    return "Слово \"$polindrom\" не является полиндромом";
}