<?php

function convert($data)
{
    if ($data == []) {
        return [];
    }
    
    $result = array_reduce($data, function ($acc, $couple) {
        [$key, $value] = $couple;
        if (is_array($value)) {
            $acc[$key] = convert($value);
            return $acc;
        }
        $acc[$key] = $value;
        return $acc;
    }, []);
    return $result;
    
}

function test()
{
    convert([]) === []
    ? print("OK\n") : print("KO\n");
    echo "--------------------------\n";

    convert([['key', 'value']]) === [ 'key' => 'value' ]
    ? print("OK\n") : print("KO\n");
    echo "--------------------------\n";
    
    convert([['key', 'value'], ['key2', 'value2']]) === [ 'key' => 'value', 'key2' => 'value2']
    ? print("OK\n") : print("KO\n");
    echo "--------------------------\n";
    
    convert([
        ['key', [['key2', 'anotherValue']]],
        ['key2', 'value2']
    ]) === [ 'key' => ['key2' => 'anotherValue'], 'key2' => 'value2' ]
    ? print("OK\n") : print("KO\n");
}

test();


