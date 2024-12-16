<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Api\Task;

interface TaskInterface
{
    /**
     * Run Task
     *
     * @return \Qoliber\CatalogGenerator\Api\Task\TaskInterface
     */
    public function runTask(): TaskInterface;
}
