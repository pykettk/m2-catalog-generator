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

interface CleanUpServiceInterface
{
    /**
     * Clean All Data
     *
     * @return void
     */
    public function cleanUpData(): void;
}
