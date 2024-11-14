<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Opencrm Gateway extension.
 *
 * (c) kikmedia
 *
 * @license LGPL-3.0-or-later
 */

use ContaoAcademy\ZammadNCApiBundle\Gateway\ZammadGateway;

$GLOBALS['TL_DCA']['tl_nc_gateway']['palettes']['__selector__'][] = 'opencrmAuthType';
$GLOBALS['TL_DCA']['tl_nc_gateway']['palettes'][OpencrmGateway::NAME] = '{title_legend},title,type;{gateway_legend},opencrmHost,opencrmAuthType';
$GLOBALS['TL_DCA']['tl_nc_gateway']['subpalettes']['opencrmAuthType_basic'] = 'opencrmUser,opencrmPassword';
$GLOBALS['TL_DCA']['tl_nc_gateway']['subpalettes']['opencrmAuthType_token'] = 'opencrmToken';

$GLOBALS['TL_DCA']['tl_nc_gateway']['fields']['opencrmHost'] = [
    'inputType' => 'text',
    'eval' => [
        'tl_class' => 'w50',
        'maxlength' => 255,
        'rgxp' => 'httpurl',
        'mandatory' => true,
    ],
    'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_nc_gateway']['fields']['opencrmAuthType'] = [
    'inputType' => 'select',
    'eval' => [
        'tl_class' => 'clr w50',
        'submitOnChange' => true,
        'includeBlankOption' => true,
        'mandatory' => true,
    ],
    'options' => ['basic', 'token'],
    'reference' => &$GLOBALS['TL_LANG']['tl_nc_gateway']['opencrmAuthTypes'],
    'exclude' => true,
    'sql' => ['type' => 'string', 'length' => 8, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_nc_gateway']['fields']['opencrmToken'] = [
    'inputType' => 'text',
    'eval' => [
        'tl_class' => 'w50',
        'maxlength' => 255,
        'mandatory' => true,
    ],
    'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_nc_gateway']['fields']['opencrmUser'] = [
    'inputType' => 'text',
    'eval' => [
        'tl_class' => 'w50',
        'maxlength' => 255,
        'mandatory' => true,
    ],
    'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_nc_gateway']['fields']['opencrmPassword'] = [
    'inputType' => 'text',
    'eval' => [
        'tl_class' => 'w50',
        'maxlength' => 255,
        'mandatory' => true,
    ],
    'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
];
