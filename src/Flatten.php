<?php

function flatten($arr)
{
    $result = array_reduce($arr, function ($acc, $item) {
        if (is_array($item)) {
            return array_merge($acc, flatten($item));
        }
        $acc[] = $item;
        return $acc;
    }, []);
    return $result;
}


function test()
{
    flatten([]) === []
        ? print("OK\n")
        : print("KO\n");
    echo "--------------------------\n";
     
     flatten([1, 2, 3]) === [1, 2, 3]
        ? print("OK\n")
        : print("KO\n");
    echo "--------------------------\n";
    
    flatten([1, 2, [3, 4], 5]) === [1, 2, 3, 4, 5]
        ? print("OK\n")
        : print("KO\n");
    echo "--------------------------\n";
    
    flatten([1, 2, [3, 5], [[4, 3], 2]]) === [1, 2, 3, 5, 4, 3, 2]
        ? print("OK\n")
        : print("KO\n");
    echo "--------------------------\n";
}

test();
