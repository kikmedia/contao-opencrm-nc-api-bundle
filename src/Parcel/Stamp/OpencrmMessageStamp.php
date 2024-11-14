<?php

declare(strict_types=1);

/*
 * This file is part of the Contao OpenCRM Gateway extension.
 *
 * (c) kikmedia
 *
 * @license LGPL-3.0-or-later
 */

namespace Kikmedia\OpencrmNCApiBundle\Parcel\Stamp;

use Kikmedia\OpencrmNCApiBundle\Config\ZammadMessageConfig;
use Terminal42\NotificationCenterBundle\Parcel\Stamp\AbstractConfigStamp;
use Terminal42\NotificationCenterBundle\Parcel\Stamp\StampInterface;

class OpencrmMessageStamp extends AbstractConfigStamp
{
    public function __construct(public OpencrmMessageConfig $opencrmMessageConfig)
    {
        parent::__construct($this->opencrmMessageConfig);
    }

    public static function fromArray(array $data): StampInterface
    {
        return new self(opencrmMessageConfig: OpencrmMessageConfig::fromArray($data));
    }
}
