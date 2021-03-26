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
    mkdir('apache'),
    mkdir('nginx', [
      mkfile('nginx.conf'),
    ]),
  ]),
  mkdir('consul', [
    mkfile('config.json'),
    mkfile('file.tmp'),
    mkdir('data'),
  ]),
  mkfile('hosts'),
  mkfile('resolve'),
]);

function getFilesCount($tree)
{
    if (isFile($tree)) {
        return 1;
    }
    $children = getChildren($tree);
    $count = array_map(fn($child)=>getFilesCount($child), $children);
    return array_sum($count);
}

//var_dump(getFilesCount($tree0));

function getSubdirectoriesInfo($tree)
{
    $children = getChildren($tree);
    $filtered = array_filter($children, fn($child)=>isDirectory($child));
    $result = array_map(fn($child)=>[getName($child), getFilesCount($child)], $filtered);
    return $result;
}

//print_r(getSubdirectoriesInfo($tree0));

//print_r($tree);

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

function getFilesSize($tree)
{
    if (!isDirectory($tree)) {
        return getMeta($tree)['size'];
    }
    $children = getChildren($tree);
    $count = array_map(fn($child)=>getFilesSize($child), $children);
    return array_sum($count);
}

function du($tree)
{
    $children = getChildren($tree);
    $result = array_map(fn($child)=>[getName($child), getFilesSize($child)], $children);
    usort($result, fn($arr1, $arr2) => $arr2[1] <=> $arr1[1]);
    // usort($result, function ($al, $bl) {
    //     return ($al[1] < $bl[1]) ? +1 : -1;
    // });
    return $result;
}

print_r(du(getChildren($tree)[0]));
print_r(du($tree));
