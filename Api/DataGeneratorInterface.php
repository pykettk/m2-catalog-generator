<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Api;

interface DataGeneratorInterface
{
    /**
     * Generate Data
     *
     * @param string $prefix
     * @param mixed[] $options
     * @return mixed[]
     */
    public function generateRequiredData(string $prefix, array $options): array;

    /**
     * Populate required Tables
     *
     * @param int $parentProductId
     * @param mixed[] $dataOptions
     * @return mixed[]
     */
    public function populateRequiredTables(int $parentProductId, array $dataOptions): array;

    /**
     * Get Child Product Variations
     *
     * @param int $parentProductId
     * @param string $prefix
     * @param mixed[] $options
     * @return mixed[]
     */
    public function getChildProductVariations(int $parentProductId, string $prefix, array $options): array;
}
