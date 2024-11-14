<?php

declare(strict_types=1);

/*
 * This file is part of the Contao OpenCRM Gateway extension.
 *
 * (c) kikmedia
 *
 * @license LGPL-3.0-or-later
 */

namespace Kikmedia\OpencrmNCApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OpencrmNCApiBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
