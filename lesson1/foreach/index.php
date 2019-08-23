<?php

$array = [];

for ($i = 0; $i < 1000000; $i++)
	$array[$i] = $i;

$iter = new ArrayIterator($array);

$start = microtime(true);

foreach ($array as $item) {}

echo 'Чистый foreach: ' . (microtime(true) - $start);

$start = microtime(true);

foreach ($iter as $item) {}
//while( $iter->valid() ) {}

echo '<br/>Итератор foreach: ' . (microtime(true) - $start);