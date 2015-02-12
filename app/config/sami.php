<?php

use Sami\Sami;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('Resources')
    ->exclude('Tests')
    ->in($dir = __DIR__.'/../../src/Unistra/Profetes/')
;

$versions = GitVersionCollection::create($dir)
    ->addFromTags('v2.0.*')
    ->add('2.0', '2.0 branch')
    ->add('master', 'master branch')
;

return new Sami($iterator, array(
    'theme'                => 'enhanced',
    //'versions'             => $versions,
    'title'                => 'JU Symfony2 API',
    'build_dir'            => __DIR__.'/../../../doc/api/%version%',
    'cache_dir'            => __DIR__.'/../cache/sami/%version%',
    // use a custom theme directory
    //'template_dirs'        => array(__DIR__.'/themes/symfony'),
    'default_opened_level' => 2,
));
