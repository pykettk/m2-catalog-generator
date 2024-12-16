<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Data\Generators\Product;

use Magento\Framework\App\ResourceConnection;
use Qoliber\CatalogGenerator\Api\DataGeneratorInterface;

class GroupedProductDataGenerator implements DataGeneratorInterface
{
    /**
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     */
    public function __construct(
        private readonly ResourceConnection $resourceConnection
    ) {
    }

    /**
     * Generate Required Data
     *
     * @param string $prefix
     * @param mixed[] $options
     * @return mixed[]
     */
    public function generateRequiredData(string $prefix, array $options): array
    {
        return [];
    }

    /**
     * Populate required Tables
     *
     * @param int $parentProductId
     * @param mixed[] $dataOptions
     * @return mixed[]
     */
    public function populateRequiredTables(int $parentProductId, array $dataOptions): array
    {
        $connection = $this->resourceConnection->getConnection();
        $productLinkTable = $this->resourceConnection
            ->getTableName('catalog_product_link');
        $productLinkAttributeDecimalTable = $this->resourceConnection
            ->getTableName('catalog_product_link_attribute_decimal');
        $productLinkAttributeIntTable = $this->resourceConnection
            ->getTableName('catalog_product_link_attribute_int');

        $productId = $parentProductId + 1;
        $productPosition = 0;

        foreach ($dataOptions as $productCount) {
            for ($i = 0; $i < $productCount; $i++) {
                $connection->insert($productLinkTable, [
                    'product_id' => $parentProductId,
                    'linked_product_id' => $productId++,
                    'link_type_id' => 3
                ]);

                // @phpstan-ignore-next-line
                $groupedProductLinkId = $connection->lastInsertId();
                $connection->insert($productLinkAttributeDecimalTable, [
                    'product_link_attribute_id' => 5,
                    'link_id' => $groupedProductLinkId,
                    'value' => 0.0000
                ]);

                $connection->insert($productLinkAttributeIntTable, [
                    'product_link_attribute_id' => 4,
                    'link_id' => $groupedProductLinkId,
                    'value' => $productPosition++
                ]);
            }
        }

        return [];
    }

    /**
     * Get Child Product Variations
     *
     * @param int $parentProductId
     * @param string $prefix
     * @param mixed[] $options
     * @return mixed[]
     * @throws \Random\RandomException
     */
    public function getChildProductVariations(int $parentProductId, string $prefix, array $options): array
    {
        $childProducts = [];
        $productId = $parentProductId + 1;

        foreach ($options as $bundleOptionCount) {
            for ($i = 0; $i < $bundleOptionCount; $i++) {
                $childProducts[] = [
                    'entity_id' => $productId++,
                    'attributes' => [
                        'visibility' => 2,
                        'price' => random_int(9, 499)
                    ]
                ];
            }
        }

        $this->populateRequiredTables(
            $parentProductId,
            $options
        );

        return $childProducts;
    }
}
