<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Opencrm Gateway extension.
 *
 * (c) kikmedia
 *
 * @license LGPL-3.0-or-later
 */

use Kikmedia\OpencrmNCApiBundle\Gateway\OpencrmGateway;

$GLOBALS['TL_LANG']['tl_nc_gateway']['type'][OpencrmGateway::NAME] = 'OpenCRM-API';
$GLOBALS['TL_LANG']['tl_nc_gateway']['opencrm_settings'] = 'OPENCRM-Einstellungen';
$GLOBALS['TL_LANG']['tl_nc_gateway']['opencrmAuthType'] = ['Authentication Type', 'Bitte wählen Sie einen authentication type aus.'];
$GLOBALS['TL_LANG']['tl_nc_gateway']['opencrmHost'] = ['Host', 'Bitte geben Sie die URL der verwendeten OPENCRM Instanz ein z.B https://yourname.opencrm.com'];
$GLOBALS['TL_LANG']['tl_nc_gateway']['opencrmUser'] = ['Benutzer', 'Bitte geben Sie Ihren OpenCRM-Benutzer ein.'];
$GLOBALS['TL_LANG']['tl_nc_gateway']['opencrmPassword'] = ['Passwort', 'Bitte geben Sie Ihr OpenCRM Passwort ein.'];
$GLOBALS['TL_LANG']['tl_nc_gateway']['opencrTmoken'] = ['Token', 'Bitte geben Sie Ihr OpenCRM-Token ein.'];
$GLOBALS['TL_LANG']['tl_nc_gateway']['opencrmAuthTypes']['token'] = 'Token';
$GLOBALS['TL_LANG']['tl_nc_gateway']['opencrmAuthTypes']['basic'] = 'HTTP Basic Authentication';
