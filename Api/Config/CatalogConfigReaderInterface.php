<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Api\Config;

interface CatalogConfigReaderInterface
{
    /**
     * Initialize Config
     *
     * @param string $filePath
     * @return mixed[]
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function initializeConfig(string $filePath): array;

    /**
     * Get Config Value
     *
     * @param string $arrayKey
     * @return mixed
     */
    public function getConfig(string $arrayKey): mixed;
}
