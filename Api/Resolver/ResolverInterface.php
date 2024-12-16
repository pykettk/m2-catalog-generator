<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Api\Resolver;

interface ResolverInterface
{
    /**
     * Resolve Data
     *
     * @return string
     */
    public function resolveData(): string;
}
