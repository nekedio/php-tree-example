<?php

require_once __DIR__ . '/../vendor/autoload.php';

use function Php\Immutable\Fs\Trees\trees\mkdir;
use function Php\Immutable\Fs\Trees\trees\mkfile;
use function Php\Immutable\Fs\Trees\trees\getChildren;
use function Php\Immutable\Fs\Trees\trees\getName;
use function Php\Immutable\Fs\Trees\trees\getMeta;
use function Php\Immutable\Fs\Trees\trees\isDirectory;
use function Php\Immutable\Fs\Trees\trees\isFile;

function getEx()
{
    $tree = mkdir('/', [mkfile('hexlet.log')], ['hidden' => true]);

    echo "\n------tree-------\n";
    print_r($tree);

    echo "\n------getName(tree)-------\n";
    var_dump(getName($tree)); // '/'

    echo "\n------getMeta(tree)-------\n";
    var_dump(getMeta($tree)['hidden']); // true

    echo "\n------isDirectory(tree)-------\n";
    var_dump(isDirectory($tree)); // true

    echo "\n------isFile(tree)-------\n";
    var_dump(isFile($tree)); // false

    [$file] = getChildren($tree);

    echo "\n------getName(file)-------\n";
    var_dump(getName($file)); // 'hexlet.log'

    echo "\n------isset(getMeta(file)['unhnown'])-------\n";
    var_dump(isset(getMeta($file)['unknown'])); // false

    echo "\n------isFile(file)-------\n";
    var_dump(isFile($file)); // true

    echo "\n------isDirectory(file)-------\n";
    var_dump(isDirectory($file)); // false

    // New file
    $file = mkfile('one', ['size' => 35]);
    $newFile = mkfile('new name', getMeta($file));
}

function getEx1()
{
    // sorting
    echo "\n------sorting-------";
    $tree = mkdir('/', [
        mkfile('one'),
        mkfile('two'),
        mkdir('three'),
    ]);

    $children = getChildren($tree);
    $newChildren = array_reverse($children);
    $sortTree = mkdir(getName($tree), $newChildren, getMeta($tree));

    echo "\n------tree-------\n";
    print_r($tree);
    echo "\n------sortTree-------\n";
    print_r($sortTree);

    //changed directory
    echo "\n------changedDir-------";
    $tree = mkdir('/', [
        mkfile('oNe'),
        mkfile('Two'),
        mkdir('THREE'),
    ]);

    $children = getChildren($tree);
    $newChildren = array_map(function ($child) {
        $name = getName($child);
        if (isDirectory($child)) {
            return mkdir(strtolower($name), getChildren($child), getMeta($child));
        }
        return mkfile(strtolower($name), getMeta($child));
    }, $children);

    $tree2 = mkdir(getName($tree), $newChildren, getMeta($tree));

    echo "\n------tree-------\n";
    print_r($tree);
    echo "\n------changedDir-------\n";
    print_r($tree2);

    //delete
    echo "\n------delete-------";
    $tree = mkdir('/', [
        mkfile('one'),
        mkfile('two'),
        mkdir('three'),
    ]);

    $children = getChildren($tree);
    $newChildren = array_filter($children, fn($child) => isDirectory($child));
    $tree2 = mkdir(getName($tree), $newChildren, getMeta($tree));

    echo "\n------tree-------\n";
    print_r($tree);
    echo "\n------delete-------\n";
    print_r($tree2);
}

//getEx();
//getEx1();

//---------------------------------------ex------------------------------------------

$tree = mkdir(
    'my documents', [
        mkfile('avatar.jpg', ['size' => 100]),
        mkfile('passport.jpg', ['size' => 200]),
        mkfile('family.jpg',  ['size' => 150]),
        mkfile('addresses',  ['size' => 125]),
        mkdir('presentations')
    ]
);

function compressImages($tree)
{
    $children = getChildren($tree);
    $newChildren = array_map(function ($child) {
        if (isFile($child)) {
            if (pathinfo(getName($child), PATHINFO_EXTENSION) == 'jpg') {
                $size = getMeta($child)['size'];
                $newMeta['size'] = $size / 2;
                return mkfile(getName($child), $newMeta);
            } 
            return mkfile(getName($child), getMeta($child));
        }
        return mkdir(getName($child), getChildren($child), getMeta($child));
    }, $children);
    return mkdir(getName($tree), $newChildren, getMeta($tree));
}

$newTree = compressImages($tree);
print_r($newTree);
echo "/////////////////////////\n";
print_r($tree);
