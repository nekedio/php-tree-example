<?php

require_once __DIR__ . '/../vendor/autoload.php';

use function Php\Immutable\Fs\Trees\trees\mkdir;
use function Php\Immutable\Fs\Trees\trees\mkfile;
use function Php\Immutable\Fs\Trees\trees\getChildren;
use function Php\Immutable\Fs\Trees\trees\getName;
use function Php\Immutable\Fs\Trees\trees\getMeta;
use function Php\Immutable\Fs\Trees\trees\isDirectory;
use function Php\Immutable\Fs\Trees\trees\isFile;

$tree0 = mkdir('/', [
  mkdir('etc', [
    mkfile('bashrc'),
    mkfile('consul.cfg'),
  ]),
  mkfile('hexletrc'),
  mkdir('bin', [
    mkfile('ls'),
    mkfile('cat'),
  ]),
]);

// В реализации используем рекурсивный процесс,
// чтобы добраться до самого дна дерева.
function getNodesCount($tree)
{
  if (isFile($tree)) {
    // Возвращаем 1, для учета текущего файла
    return 1;
  }

  // Если узел — директория, получаем его детей
  $children = getChildren($tree);
  // Самая сложная часть
  // Считаем количество потомков, для каждого из детей,
  // вызывая рекурсивно нашу функцию getNodesCount
  $descendantsCount = array_map(fn($child) => getNodesCount($child), $children);
  // Возвращаем 1 (текущая директория) + общее количество потомков
  return 1 + array_sum($descendantsCount);
}

//print_r(getNodesCount($tree0)); // 8

$tree = mkdir('/', [
    mkdir('etc', [
    mkdir('apache', []),
    mkdir('nginx', [
        mkfile('.nginx.conf', ['size' => 800]),
    ]),
    mkdir('.consul', [
        mkfile('.config.json', ['size' => 1200]),
        mkfile('data', ['size' => 8200]),
        mkfile('raft', ['size' => 80]),
    ]),
    ]),
    mkfile('.hosts', ['size' => 3500]),
    mkfile('resolve', ['size' => 1000]),
]);

function getHiddenFilesCount($tree)
{
    if (isFile($tree)) {
        if (getName($tree)[0] == '.') {
            return 1;
        }
        return 0;
    }
    $counter = array_map(fn($child)=>getHiddenFilesCount($child), getChildren($tree));
    return array_sum($counter);
}

print_r(getHiddenFilesCount($tree));

//print_r(str_starts_with('1234', '1'));
