<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Open CRM Gateway extension.
 *
 * (c) kikmedia
 *
 * @license LGPL-3.0-or-later
 */

namespace Kikmedia\OpencrmNCApiBundle\Migration;

use Contao\Config;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Kikmedia\OpencrmNCApiBundle\Gateway\ZammadGateway;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Column;

class OpencrmGatewayMigration extends AbstractMigration
{
    private static $fields = ['opencrmAuthType', 'opencrmHost', 'opencrmUser', 'opencrmPassword', 'opencrmToken'];

    public function __construct(
        private readonly Connection $db,
        private readonly ContaoFramework $contaoFramework,
    ) {
    }

    public function shouldRun(): bool
    {
        if (!$this->getLegacyConfigs()) {
            return false;
        }

        $schemaManager = $this->db->createSchemaManager();

        if (!$schemaManager->tablesExist(['tl_nc_gateway'])) {
            return false;
        }

        $columns = array_map(static fn (Column $column): string => $column->getName(), $schemaManager->listTableColumns('tl_nc_gateway'));

        if (array_diff(self::$fields, $columns)) {
            return false;
        }

        return (bool) $this->db->fetchOne("SELECT TRUE FROM tl_nc_gateway WHERE type = ? AND opencrmAuthType = ''", [OpencrmGateway::NAME]);
    }

    public function run(): MigrationResult
    {
        $this->db->update('tl_nc_gateway', $this->getLegacyConfigs(), ['opencrmAuthType' => '']);

        return $this->createResult(true);
    }

    private function getLegacyConfigs(): array
    {
        $this->contaoFramework->initialize();

        $configs = [];

        foreach (self::$fields as $field) {
            if ($value = Config::get($field)) {
                $configs[$field] = $value;
            }
        }

        return $configs;
    }
}
