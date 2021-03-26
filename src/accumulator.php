<?php

require_once __DIR__ . '/../vendor/autoload.php';

use function Php\Immutable\Fs\Trees\trees\mkdir;
use function Php\Immutable\Fs\Trees\trees\mkfile;
use function Php\Immutable\Fs\Trees\trees\getChildren;
use function Php\Immutable\Fs\Trees\trees\getName;
use function Php\Immutable\Fs\Trees\trees\getMeta;
use function Php\Immutable\Fs\Trees\trees\isDirectory;
use function Php\Immutable\Fs\Trees\trees\isFile;
use function Php\Immutable\Fs\Trees\trees\array_flatten;

$tree = mkdir('/', [
  mkdir('etc', [
    mkdir('apache'),
    mkdir('nginx', [
      mkfile('nginx.conf'),
    ]),
    mkdir('consul', [
      mkfile('config.json'),
      mkdir('data'),
    ]),
  ]),
  mkdir('logs'),
  mkfile('hosts'),
]);

//print_r($tree);


function findEmptyDirPaths($tree)
{
    $name = getName($tree);
    $children = getChildren($tree);

    // Если детей нет, то добавляем директорию
    if (count($children) === 0) {
        return [$name];
    }

    // Фильтруем файлы, они нас не интересуют 
    $dirNames = array_filter($children, fn($child) => !isFile($child));
    // Ищем пустые директории внутри текущей
    $emptyDirNames = array_map(fn($dir) => findEmptyDirPaths($dir), $dirNames);

    // array_flatten выправляет массив, так что он остается плоским
    return array_flatten($emptyDirNames);
}

// В выводе указана только конечная директория
// Подумайте, как надо изменить функцию, чтобы видеть полный путь

print_r(findEmptyDirPaths($tree));

// Функция iter используется внутри основной и может передавать аккумулятор
// В качестве аккумулятора выступает переменная $depth, содержащая текущую глубину
function iter($node, $depth)
{
    $name = getName($node);
    $children = getChildren($node);

    // Если детей нет, то добавляем директорию
    if (count($children) === 0) {
        return $name;
    }
    // Если это второй уровень вложенности, и директория не пустая,
    // то не имеет смысла смотреть дальше
    if ($depth === 2) {
        // Почему возвращается именно пустой массив?
        // Потому что снаружи выполняется array_flatten
        // Он раскрывает пустые массивы
        return [];
    }
    // Оставляем только директории
    $emptyDirPaths = array_filter($children, 'Php\Immutable\Fs\Trees\trees\isDirectory');
    // Не забываем увеличивать глубину
    $output = array_map(function ($child) use ($depth) {
        return iter($child, $depth + 1);
    }, $emptyDirPaths);

    // Перед возвратом "выпрямляем" массив
    return array_flatten($output);
}

function findEmptyPaths1($tree)
{
    // Начинаем с глубины 0
    return iter($tree, 0);
}

print_r(findEmptyPaths1($tree)); // ['apache', 'logs']

$tree = mkdir('/', [
    mkdir('etc', [
        mkdir('apache'),
        mkdir('nginx', [
            mkfile('nginx.conf', ['size' => 800]),
        ]),
        mkdir('consul', [
            mkfile('config.json', ['size' => 1200]),
            mkfile('data', ['size' => 8200]),
            mkfile('raft', ['size' => 80]),
        ]),
    ]),
    mkfile('hosts', ['size' => 3500]),
    mkfile('resolve', ['size' => 1000]),
]);

function findFilesByName($tree)
{
    
    
    
    return $tree;
}

print_r(findFilesByName($tree, 'co'));
// ['/etc/nginx/nginx.conf', '/etc/consul/config.json']


