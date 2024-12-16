<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Api\Reader;

interface YamlReaderInterface
{
    /**
     * Read Configuration
     *
     * @param string $filePath
     * @return mixed[]
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function read(string $filePath): array;
}
