<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    // zestawy dla wersji PHP (to jest ok w Rector 2.x)
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,
    ]);

    // właściwa reguła dla Rector 1.x/2.x
    $rectorConfig->rules([
        DeclareStrictTypesRector::class,
    ]);
};
