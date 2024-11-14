<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Opencrm Gateway extension.
 *
 * (c) kikmedia
 *
 * @license LGPL-3.0-or-later
 */

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Terminal42\NotificationCenterBundle\Token\TokenContext;

$GLOBALS['TL_DCA']['tl_nc_message']['fields']['opencrm_email'] = [
    'inputType' => 'text',
    'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'clr w50'],
    'exclude' => true,
    'nc_context' => TokenContext::Email,
    'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_nc_message']['fields']['opencrm_params'] = [
    'exclude' => true,
    'inputType' => 'keyValueWizard',
    'eval' => ['tl_class' => 'clr'],
    'sql' => ['type' => 'blob', 'notnull' => false],
];

$GLOBALS['TL_DCA']['tl_nc_message']['fields']['opencrm_title'] = [
    'exclude' => true,
    'inputType' => 'text',
    'default' => '',
    'eval' => ['tl_class' => 'w50', 'maxlength' => 64, 'mandatory' => true],
    'nc_context' => TokenContext::Text,
    'sql' => ['type' => 'string', 'length' => 64, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_nc_message']['fields']['opencrm_group'] = [
    'exclude' => true,
    'inputType' => 'text',
    'default' => '',
    'eval' => ['tl_class' => 'w50', 'maxlength' => 64, 'mandatory' => true],
    'nc_context' => TokenContext::Text,
    'sql' => ['type' => 'string', 'length' => 64, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_nc_message']['fields']['opencrm_body'] = [
    'exclude' => true,
    'inputType' => 'textarea',
    'default' => '',
    'eval' => ['mandatory' => true, 'tl_class' => 'clr'],
    'nc_context' => TokenContext::Html,
    'sql' => ['type' => 'text', 'length' => MySQLPlatform::LENGTH_LIMIT_TEXT, 'notnull' => false],
];

$GLOBALS['TL_DCA']['tl_nc_message']['palettes']['opencrm'] = '{title_legend},title,gateway;{opencrm_customer_legend},opencrm_email,opencrm_params;{opencrm_ticket_legend},opencrm_title,opencrm_group,opencrm_body;{publish_legend},published';
