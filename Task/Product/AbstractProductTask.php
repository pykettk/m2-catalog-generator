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

use Magento\Catalog\Model\ProductTypes\ConfigInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem\Io\File as IoFile;
use Qoliber\CatalogGenerator\Sql\Connection;
use Qoliber\CatalogGenerator\Task\AbstractTask;

abstract class AbstractProductTask extends AbstractTask
{
    /**
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productConfigInterface
     * @param \Magento\Framework\Filesystem\Io\File $ioFile
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Filesystem\Driver\File $file
     * @param \Qoliber\CatalogGenerator\Sql\Connection $connection
     * @param mixed[] $attributeData
     * @param string[] $compositeProductTypes
     */
    public function __construct(
        protected ConfigInterface $productConfigInterface,
        protected IoFile $ioFile,
        protected Filesystem $filesystem,
        protected File $file,
        protected Connection $connection,
        protected array $attributeData = [],
        private array $compositeProductTypes = [],
    ) {
        parent::__construct(
            $this->ioFile,
            $this->filesystem,
            $this->file,
            $this->connection,
            $this->attributeData
        );
    }

    /**
     * Get Composite Product Types
     *
     * @return string[]
     */
    public function getCompositeProductTypes(): array
    {
        if (empty($this->compositeProductTypes)) {
            $this->compositeProductTypes = array_keys(
                array_filter(
                    $this->productConfigInterface->getAll(),
                    fn($typeConfig) => !empty($typeConfig['composite']) && $typeConfig['composite'] === true
                )
            );
        }

        return $this->compositeProductTypes;
    }

    /**
     * Is product type a composite type
     *
     * @param string $productType
     * @return bool
     */
    public function isProductInCompositeTypes(string $productType): bool
    {
        return in_array($productType, $this->getCompositeProductTypes(), true);
    }
}
