<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Config;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Driver\File;
use Qoliber\CatalogGenerator\Api\Config\CatalogConfigReaderInterface;
use Qoliber\CatalogGenerator\Api\Reader\YamlReaderInterface;

class CatalogConfigReader implements CatalogConfigReaderInterface
{
    /**
     * @param \Qoliber\CatalogGenerator\Api\Reader\YamlReaderInterface $yamlReader
     * @param \Magento\Framework\Filesystem\Driver\File $fileDriver
     * @param mixed[]|null $config
     */
    public function __construct(
        protected YamlReaderInterface $yamlReader,
        private readonly File $fileDriver,
        private ?array $config = null,
    ) {
    }

    /**
     * Initialize Config
     *
     * @param string $filePath
     * @return mixed[]
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function initializeConfig(string $filePath): array
    {
        if ($this->fileDriver->isExists($filePath) && $this->fileDriver->isFile($filePath)) {
            $this->config = $this->yamlReader->read($filePath);

            return $this->config;
        } else {
            throw new FileSystemException(__('File %1 does not exists', $filePath));
        }
    }

    /**
     * Get Config Value
     *
     * @param string $arrayKey
     * @return mixed
     */
    public function getConfig(string $arrayKey): mixed
    {
        return $this->config[$arrayKey] ?? null;
    }
}
