<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Data\Generators;

use Magento\Framework\Exception\LocalizedException;
use Qoliber\CatalogGenerator\Api\EntityGeneratorInterface;

class ProductGenerator extends AbstractGenerator implements EntityGeneratorInterface
{
    /** @var string  */
    private const ENTITY_TABLE = 'catalog_product_entity';

    /**
     * Get Entity Table
     *
     * @return string
     */
    public function getEntityTable(): string
    {
        return self::ENTITY_TABLE;
    }

    /**
     * Generate Entities. return entity array
     *
     * @param int|string $count
     * @param string $entityType
     * @param mixed[] $entityConfig
     * @return mixed[][]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function generateEntities(int|string $count, string $entityType, array $entityConfig = []): array
    {
        $productId = 0;
        $productEntityArray = [$this->getEntityTable() => []];

        foreach ($this->getProductTypes() as $productType => $productTypeConfig) {
            $productTypeGenerator = $this->getDataGenerator(sprintf('product/%s', $productType));

            for ($i = 0; $i < $productTypeConfig['count']; $i++) {
                $productId++;
                $productEntityArray[$this->getEntityTable()][] = $this->prepareEntityData($productId, $productType);
                $attributeData = $this->populateAttributes($entityConfig, $productId);
                $this->populateEntityTableArray($attributeData, $productEntityArray);

                if ($productTypeGenerator) {
                    $childProducts = $productTypeGenerator->getChildProductVariations(
                        $productId,
                        $this->configReader->getConfig('prefix'),
                        $productTypeConfig['options']
                    );

                    foreach ($childProducts as $childProduct) {
                        $productEntityArray[$this->getEntityTable()][] =
                            $this->prepareEntityData($childProduct['entity_id'], 'simple');
                        $productData = $entityConfig;
                        $productData['attributes'] = [
                            ...$entityConfig['attributes'], // Spread operator to include attributes from $entityConfig
                            ...$childProduct['attributes'] // Spread operator to include attributes from $childProduct
                        ];
                        $attributeData = $this->populateAttributes($productData, $childProduct['entity_id']);
                        $this->populateEntityTableArray($attributeData, $productEntityArray);
                    }

                    $productId += count($childProducts);
                }
            }
        }

        return $productEntityArray;
    }

    /**
     * Populate / hydrate attributes
     *
     * @param mixed[] $entityConfig
     * @param int $entityId
     * @return mixed[]
     */
    public function populateAttributes(array $entityConfig, int $entityId): array
    {
        $dataPopulator = $this->getDataPopulator('product/attributes');
        $attributeEntityData = [];

        if ($dataPopulator) {
            foreach ($entityConfig['attributes'] as $attributeCode => $attributeValue) {
                $attributeData = $dataPopulator->getAttributeData('4', $attributeCode);
                $attributeTable = $this->resourceConnection->getConnection()->getTableName(
                    sprintf('%s_%s', $this->getEntityTable(), $attributeData['backend_type'])
                );
                $attributeId = $attributeData['attribute_id'];
                $attributeEntityData[$attributeTable][] = [
                    'attribute_id' => $attributeId,
                    'store_id' => 0,
                    'entity_id' => $entityId,
                    'value' => $this->getAttributeValue($attributeValue)
                ];
            }
        }

        return $attributeEntityData;
    }

    /**
     * Get Product Types
     *
     * @return mixed[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getProductTypes(): array
    {
        if ($productTypes = $this->configReader->getConfig('entities')['product']['types'] ?? null) {
            return $productTypes;
        } else {
            throw new LocalizedException(__('Invalid configuration file'));
        }
    }

    /**
     * Prepare Entity
     *
     * @param int $i
     * @param string $entityType
     * @param bool $hasOptions
     * @param bool $requiredOptions
     * @return array<string, string|int|bool>
     */
    private function prepareEntityData(
        int $i,
        string $entityType,
        bool $hasOptions = false,
        bool $requiredOptions = false
    ): array {
        return [
            'entity_id' => $i,
            'attribute_set_id' => 4,
            'type_id' => $entityType,
            'sku' => sprintf(
                '%s_%d',
                $this->configReader->getConfig('prefix'),
                $i
            ),
            'has_options' => $hasOptions,
            'required_options' => $requiredOptions,
        ];
    }
}
