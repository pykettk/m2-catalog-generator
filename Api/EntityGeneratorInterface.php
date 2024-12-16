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

use Qoliber\CatalogGenerator\Api\Task\TaskInterface;

interface EntityGeneratorInterface
{
    /**
     * Get Entity Table
     *
     * @return string
     */
    public function getEntityTable(): string;

    /**
     * Generate Entities. return entity array
     *
     * @param int|string $count
     * @param string $entityType
     * @param mixed[] $entityConfig
     * @return mixed[][]
     */
    public function generateEntities(int|string $count, string $entityType, array $entityConfig = []): array;

    /**
     * Generate Child Entities
     *
     * @param int $parentId
     * @param int $childCount
     * @param mixed[] $entityConfig
     * @return mixed[]
     */
    public function generateChildEntities(int $parentId, int $childCount, array $entityConfig = []): array;

    /**
     * Populate Attributes
     *
     * @param mixed[] $entityConfig
     * @param int $entityId
     * @return mixed[]
     */
    public function populateAttributes(array $entityConfig, int $entityId): array;

    /**
     * Get Entity Relates Tasks
     *
     * @param string $taskName
     * @return \Qoliber\CatalogGenerator\Api\Task\TaskInterface|null
     */
    public function getTask(string $taskName): ?TaskInterface;
}
