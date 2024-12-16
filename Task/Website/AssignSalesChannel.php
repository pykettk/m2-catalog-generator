<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Task\Website;

use Qoliber\CatalogGenerator\Api\Task\TaskInterface;
use Qoliber\CatalogGenerator\Sql\InsertMultipleOnDuplicate;
use Qoliber\CatalogGenerator\Task\AbstractTask;

class AssignSalesChannel extends AbstractTask implements TaskInterface
{
    /**
     * Run Task
     *
     * @return \Qoliber\CatalogGenerator\Api\Task\TaskInterface
     * @throws \Exception
     */
    public function runTask(): TaskInterface
    {
        $dataToInsert = [];
        $websiteCodes = $this->getWebsiteCodes();

        foreach ($websiteCodes as $websiteCode) {
            $dataToInsert[] = [
                'type' => 'website',
                'code' => $websiteCode,
                'stock_id' => 1 //TODO - implement multi warehouse inventory generation
            ];
        }

        $insert = new InsertMultipleOnDuplicate();
        $prepareStatement = $insert->buildInsertQuery(
            'inventory_stock_sales_channel',
            array_keys($dataToInsert[0]),
            count($dataToInsert)
        );

        $this->connection->execute($prepareStatement, InsertMultipleOnDuplicate::flatten($dataToInsert));

        return $this;
    }

    /**
     * Get Website Codes
     *
     * @return string[]
     */
    private function getWebsiteCodes(): array
    {
        $websiteCodesSql = $this->connection->getConnection()->select()
            ->from($this->connection->getTableName('store_website'), ['code'])
            ->where('website_id > 0');

        return $this->connection->getConnection()->fetchCol($websiteCodesSql);
    }
}
