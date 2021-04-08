<?php

require_once __DIR__ . '/../vendor/autoload.php';

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

function iter($tree, $value, $acc = [])
{
    [$name, $children] = $tree;
    
    if ($name == $value) {
            $acc[] = $name;
            return $acc;
    }

    if ($children == null) {
        if ($name == $value) {
            $acc[] = $name;
            return $acc;
        } else {
            return;
        }
    }

    $acc[] = $name;
    $arr = flatten(array_map(fn($child)=>iter($child, $value, $acc), $children));
    $filtered = array_values(array_filter($arr, fn($item)=>($item != null)));
    return $filtered;
}

function itinerary($tree, $value1, $value2)
{
    $route1 = array_reverse(iter($tree, $value1));
    print_r($route1);
    $route2 = iter($tree, $value2);
    print_r($route2);
    $result = array_values(array_unique(array_merge($route1, $route2)));
    print_r($result);
    print_r(array_merge($route1, $route2));
    return $result;
}

function test()
{
    $tree = ['Moscow', [
        ['Smolensk'],
        ['Yaroslavl'],
        ['Voronezh', [
            ['Liski'],
            ['Boguchar'],
            ['Kursk', [
                ['Belgorod', [
                    ['Borisovka'],
                ]],
                ['Kurchatov'],
            ]],
        ]],
        ['Ivanovo', [
            ['Kostroma'], ['Kineshma'],
        ]],
        ['Vladimir'],
        ['Tver', [
            ['Klin'], ['Dubna'], ['Rzhev'],
        ]],
    ]];

    $colors = new App\Colors();

    // print_r(iter($tree, 'Ivanovo'));
    // print_r(iter($tree, 'Kursk'));
    // print_r(iter($tree, 'Voronezh'));


    itinerary($tree, 'Dubna', 'Kostroma') === ['Dubna', 'Tver', 'Moscow', 'Ivanovo', 'Kostroma']
        ? print $colors->getColoredString(" OK ", "while", "green") . "\n"
        : print $colors->getColoredString(" KO ", "while", "red") . "\n";

    itinerary($tree, 'Borisovka', 'Kurchatov') === ['Borisovka', 'Belgorod', 'Kursk', 'Kurchatov']
        ? print $colors->getColoredString(" OK ", "while", "green") . "\n"
        : print $colors->getColoredString(" KO ", "while", "red") . "\n";

    itinerary($tree, 'Rzhev', 'Moscow') === ['Rzhev', 'Tver', 'Moscow']
        ? print $colors->getColoredString(" OK ", "while", "green") . "\n"
        : print $colors->getColoredString(" KO ", "while", "red") . "\n";

    itinerary($tree, 'Ivanovo', 'Kursk') === ['Ivanovo', 'Moscow', 'Voronezh', 'Kursk']
        ? print $colors->getColoredString(" OK ", "while", "green") . "\n"
        : print $colors->getColoredString(" KO ", "while", "red") . "\n";

    itinerary($tree, 'Rzhev', 'Borisovka') === ['Rzhev', 'Tver', 'Moscow', 'Voronezh', 'Kursk', 'Belgorod', 'Borisovka']
        ? print $colors->getColoredString(" OK ", "while", "green") . "\n"
        : print $colors->getColoredString(" KO ", "while", "red") . "\n";

    itinerary($tree, 'Tver', 'Dubna') === ['Tver', 'Dubna']
        ? print $colors->getColoredString(" OK ", "while", "green") . "\n"
        : print $colors->getColoredString(" KO ", "while", "red") . "\n";

}

test();
