<?php

declare(strict_types=1);

/*
 * This file is part of the Contao OpenCRM Gateway extension.
 *
 * (c) kikmedia
 *
 * @license LGPL-3.0-or-later
 */

namespace Kikmedia\OpencrmNCApiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class OpencrmNCApiExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        (new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config')))
            ->load('services.yaml')
        ;
    }
}
