<?php

$stack = new SplStack();

$stack->push('1');
$stack->push('2');
$stack->push('3');
$stack->push('4');
$stack->push('5');

echo 'Количество элементов стека: ' . $stack->count() . '<br/>';
//echo '2 ' . $stack->pop() . '<br/>';
//echo '2 ' . $stack->pop() . '<br/>';
//echo '2 ' . $stack->pop() . '<br/>';
//echo 'Current: ' . $stack->current() . '<br/>';
$stack->offsetUnset($stack->count() - 1);
//echo '1 элемент стека: ' . $stack->bottom() . '<br/>';

foreach ($stack as $key => $item) {
    echo "{$key} элемент стека: {$item}<br/>";
}