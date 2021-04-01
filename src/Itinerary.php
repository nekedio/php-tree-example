<?php

require_once __DIR__ . '/../vendor/autoload.php';

function itinerary($tree, $value1, $value2)
{
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
