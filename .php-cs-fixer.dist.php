<?php

/*
 * Config file for PHP Coding Standards Fixer
 *
 * https://github.com/FriendsOfPHP/PHP-CS-Fixer
 */

$finder = PhpCsFixer\Finder::create()
    ->in(['src'])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;
