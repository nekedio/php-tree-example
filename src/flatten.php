<?php
$tree1 = [[5], 1, [3, 4]]; 
$tree2 = [1, 2, [3, 5], [[4, 3], 2]];

function removeFirstLevel($data)
{
    $nodes = array_filter($data, fn($node) => is_array($node));
    return array_merge(...$nodes);
}

//print_r(removeFirstLevel($tree1)); // [5, 3, 4]
//print_r(removeFirstLevel($tree2)); // [3, 5, [4, 3], 2]


function f(...$val) {
    return $val;
}

//print_r(f(1, 2, 3));
