<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Reader;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Driver\File;
use Qoliber\CatalogGenerator\Api\Reader\YamlReaderInterface;
use Symfony\Component\Yaml\Yaml;

class YamlReader implements YamlReaderInterface
{
    /**
     * @param \Magento\Framework\Filesystem\Driver\File $fileDriver
     */
    public function __construct(
        private readonly File $fileDriver,
    ) {
    }

    /**
     * Read Configuration
     *
     * @param string $filePath
     * @return mixed[]
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function read(string $filePath): array
    {
        if (!$this->fileDriver->isExists($filePath)) {
            throw new FileSystemException(__('File does not exist: %1', $filePath));
        }

        try {
            $content = $this->fileDriver->fileGetContents($filePath);
            return Yaml::parse($content);
        } catch (\Exception $e) {
            throw new FileSystemException(__('Error parsing YML file: %1', $e->getMessage()));
        }
    }
}
