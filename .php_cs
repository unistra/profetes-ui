<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude('Resources')
    ->in(__DIR__.'/src/Unistra/Profetes*')
    ;

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->setUsingCache(true)
    ->finder($finder);
