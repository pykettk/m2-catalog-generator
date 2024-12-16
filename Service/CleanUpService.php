<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Service;

use Qoliber\CatalogGenerator\Api\Service\CleanUpServiceInterface;
use Qoliber\CatalogGenerator\Sql\Connection;
use Qoliber\CatalogGenerator\Sql\CleanUp;

class CleanUpService implements CleanUpServiceInterface
{
    /**
     * @param \Qoliber\CatalogGenerator\Sql\Connection $connection
     */
    public function __construct(
        protected Connection $connection
    ) {
    }

    /**
     * Clear Data, based on SQL query list
     * //TODO - remove all elastic indices / opensearch indices as well
     *
     * @return void
     */
    public function cleanUpData(): void
    {
        foreach (CleanUp::CLEAN_UP_QUERIES as $cleanUpQuery) {
            $this->connection->executeQuery(
                $this->insertTableNameToQuery($cleanUpQuery)
            );
        }

        foreach (CleanUp::WILDCARD_SUFFIXES as $tableSuffix) {
            $this->getWildCardTablesAndTruncate($tableSuffix);
        }
    }

    /**
     * Get Real Table name, in case there is a prefix
     *
     * @param string $query
     * @return string
     */
    private function insertTableNameToQuery(string $query): string
    {
        $pattern = '/`([^`]+)`/';

        if (preg_match($pattern, $query, $matches)) {
            $extracted = $matches[1];
            $replacement = $this->connection->getTableName($extracted);
            $query = (string) preg_replace($pattern, "`$replacement`", $query, 1);
        }

        return $query;
    }

    /**
     * Get ALl Tables based on wildcard characters, to cleanup
     *
     * @param string $pattern
     * @return void
     */
    private function getWildCardTablesAndTruncate(string $pattern): void
    {
        $query = sprintf('SHOW TABLES LIKE "%s"', $pattern);
        $result = $this->connection->getConnection()->fetchCol($query);

        foreach ($result as $tableName) {
            $this->connection->executeQuery(sprintf('TRUNCATE TABLE `%s`', $tableName));
        }
    }
}
