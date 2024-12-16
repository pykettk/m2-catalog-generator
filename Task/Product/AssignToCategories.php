<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Task\Product;

use Qoliber\CatalogGenerator\Api\Task\TaskInterface;
use Qoliber\CatalogGenerator\Sql\InsertMultipleOnDuplicate;
use Qoliber\CatalogGenerator\Task\AbstractTask;

class AssignToCategories extends AbstractTask implements TaskInterface
{
    /**
     * Run Task
     *
     * @return \Qoliber\CatalogGenerator\Api\Task\TaskInterface
     * @throws \Exception
     */
    public function runTask(): TaskInterface
    {
        $productBatches = $this->connection->getEntityBatches('entity_id', 'catalog_product_entity');
        $categoryIds = $this->fetchCategoryIds();
        $productCategoryRelation = [];

        foreach ($productBatches as $productBatch) {
            $entityIdFrom = $productBatch['id_from'];
            $entityIdTo = $productBatch['id_to'];

            for ($i = $entityIdFrom; $i <= $entityIdTo; $i++) {
                $position = 0;
                $randomKeys = array_rand($categoryIds, rand(2, (int) (count($categoryIds) / 20))); // random categories

                foreach ($randomKeys as $randomKey) {
                    $categoryId = $categoryIds[$randomKey];

                    $productCategoryRelation[] = [
                        'category_id' => $categoryId,
                        'product_id' => $i,
                        'position' => $position++
                    ];
                }
            }
        }

        $insert = new InsertMultipleOnDuplicate();
        foreach (array_chunk($productCategoryRelation, 2500) as $dataBatch) {
            $prepareStatement = $insert->buildInsertQuery(
                'catalog_category_product',
                array_keys($dataBatch[0]),
                count($dataBatch)
            );

            $this->connection->execute($prepareStatement, InsertMultipleOnDuplicate::flatten($dataBatch));
        }

        return $this;
    }

    /**
     * Fetch All category Ids
     *
     * @return string[]
     */
    private function fetchCategoryIds(): array
    {
        $query = $this->connection->getConnection()->select()
            ->from($this->connection->getTableName('catalog_category_entity'), ['entity_id'])
            ->where('entity_id > 1');

        return $this->connection->getConnection()->fetchCol($query);
    }
}
