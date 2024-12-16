<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Service;

class ConfigurableAttributeCombinator
{
    /**
     * Generate all possible combinations from an array of attributes
     *
     * @param mixed[] $input
     * @return mixed[]
     */
    public function generateCombinations(array $input): array
    {
        $keys = array_keys($input);
        $values = array_values($input);
        $combinations = [[]];

        foreach ($values as $keyIndex => $valueGroup) {
            $newCombinations = [];
            foreach ($combinations as $combination) {
                foreach (array_keys($valueGroup) as $valueKey) {
                    $newCombinations[] = $combination + [$keys[$keyIndex] => $valueKey];
                }
            }
            $combinations = $newCombinations;
        }

        return $combinations;
    }
}
