<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;
//use Symfony\Component\ClassLoader\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';
//$loader->add('Faker', __DIR__.'/vendor/fzaninotto/faker/src');

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;
