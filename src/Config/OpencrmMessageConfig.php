<?php

declare(strict_types=1);

/*
 * This file is part of the Contao OpenCRM Gateway extension.
 *
 * (c) kikmedia
 *
 * @license LGPL-3.0-or-later
 */

namespace Kikmedia\OpencrmNCApiBundle\Config;

use Terminal42\NotificationCenterBundle\Config\AbstractConfig;

class OpencrmMessageConfig extends AbstractConfig
{
    public function getEmail(): string
    {
        return $this->getString('email');
    }

    public function getParameters(): array
    {
        return $this->get('parameters', []);
    }

    public function getTitle(): string
    {
        return $this->getString('title');
    }

    public function getGroup(): string
    {
        return $this->getString('group');
    }

    public function getBody(): string
    {
        return $this->getString('body');
    }
}
