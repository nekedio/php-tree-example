<?php

require_once __DIR__ . '/../vendor/autoload.php';

use function Php\Immutable\Fs\Trees\trees\mkdir;
use function Php\Immutable\Fs\Trees\trees\mkfile;
use function Php\Immutable\Fs\Trees\trees\getChildren;
use function Php\Immutable\Fs\Trees\trees\getName;
use function Php\Immutable\Fs\Trees\trees\getMeta;
use function Php\Immutable\Fs\Trees\trees\isDirectory;
use function Php\Immutable\Fs\Trees\trees\isFile;

function dfs($tree)
{
  // Распечатываем содержимое узла
  echo getName($tree) . "\n";
  // Если это файл, то возвращаем управление
  if (isFile($tree)) {
      return;
  }

  // Получаем детей
  $children = getChildren($tree);

  // Применяем функцию dfs ко всем дочерним элементам
  // Множество рекурсивных вызовов в рамках одного вызова функции
  // называется древовидной рекурсией
  array_map(fn($child) => dfs($child), $children);
}

dfs($tree);
// => /
// => etc
// => bashrc
// => consul.cfg
// => hexletrc
// => bin
// => ls
// => cat




$tree = mkdir('/', [
    mkdir('eTc', [
        mkdir('NgiNx'),
        mkdir('CONSUL', [
            mkfile('config.json'),
        ]),
    ]),
    mkfile('hOsts'),
]);

//print_r($tree);

$tree1 = mkdir('/', [
    mkdir('eTc', [
        mkdir('NgiNx'),
        mkdir('CONSUL', [
            mkfile('config.JSON'),
        ]),
    ]),
    mkfile('hOsts'),
]);

function downcaseFileNames($tree)
{   
    if (isFile($tree)) {
        //return mkfile(getName($tree), getMeta($tree));
        return mkfile(strtolower(getName($tree)), getMeta($tree));
    }

    $children = getChildren($tree);
    $newChildren = array_map(fn($child) => downcaseFileNames($child), $children);
    return mkdir(getName($tree), $newChildren, getMeta($tree));
}

$newTree = downcaseFileNames($tree1);

//print_r($newTree);
var_dump($newTree == $tree1);
