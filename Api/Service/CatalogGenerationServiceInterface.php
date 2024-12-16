<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Api\Service;

use Qoliber\CatalogGenerator\Api\EntityGeneratorInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface CatalogGenerationServiceInterface
{
    /**
     * Generate Catalog
     *
     * @param string $configFile
     * @return bool
     */
    public function generate(string $configFile): bool;

    /**
     * Set Output
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return \Qoliber\CatalogGenerator\Api\Service\CatalogGenerationServiceInterface
     */
    public function setOutput(OutputInterface $output): CatalogGenerationServiceInterface;

    /**
     * Get Entity Generator
     *
     * @param string $type
     * @return \Qoliber\CatalogGenerator\Api\EntityGeneratorInterface|null
     */
    public function getEntityGenerator(string $type): ?EntityGeneratorInterface;
}
