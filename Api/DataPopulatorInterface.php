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

interface DataPopulatorInterface
{
    /**
     * Get Attribute Data
     *
     * @param string $entityId
     * @param string $attributeCode
     * @return mixed[]
     */
    public function getAttributeData(string $entityId, string $attributeCode):  array;
}
