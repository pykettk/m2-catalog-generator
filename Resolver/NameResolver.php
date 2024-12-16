<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Resolver;

use Qoliber\CatalogGenerator\Api\Resolver\ResolverInterface;

class NameResolver implements ResolverInterface
{
    /** @var array|string[]  */
    protected array $syllables = [
        'al', 'be', 'co', 'da', 'el', 'fa', 'gi', 'ha', 'in', 'ju',
        'ka', 'lo', 'mi', 'na', 'om', 'pa', 'qu', 'ro', 'su', 'ta',
        'ul', 'vi', 'wa', 'xi', 'yo', 'za'
    ];

    /**
     * Generate Unique Name
     *
     * @param int $minWords
     * @param int $maxWords
     * @param int $minSyllables
     * @param int $maxSyllables
     * @return string
     */
    public function generateName(
        int $minWords = 1,
        int $maxWords = 5,
        int $minSyllables = 2,
        int $maxSyllables = 4
    ): string {
        $numWords = rand($minWords, $maxWords);
        $productName = [];

        for ($i = 0; $i < $numWords; $i++) {
            $numSyllables = rand($minSyllables, $maxSyllables);
            $word = $this->generateWord($numSyllables);
            $productName[] = ucfirst($word); // Capitalize each word
        }

        return implode(' ', $productName);
    }

    /**
     * Generate a random word based on a number of syllables
     *
     * @param int $numSyllables
     * @return string
     */
    protected function generateWord(int $numSyllables): string
    {
        $word = '';
        for ($i = 0; $i < $numSyllables; $i++) {
            $word .= $this->syllables[array_rand($this->syllables)];
        }
        return $word;
    }

    /**
     * Resolve Data
     *
     * @return string
     */
    public function resolveData(): string
    {
        return $this->generateName();
    }
}
