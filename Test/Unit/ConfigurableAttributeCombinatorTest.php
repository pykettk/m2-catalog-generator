<?php
/**
 * Copyright © Qoliber. All rights reserved.
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Test\Unit;

use PHPUnit\Framework\TestCase;
use Qoliber\CatalogGenerator\Service\ConfigurableAttributeCombinator;

class ConfigurableAttributeCombinatorTest extends TestCase
{
    /**
     * @return void
     */
    public function testSingleAttribute(): void
    {
        $input = [
            "configurable_dropdown_attribute_1" => [
                519 => "value 1",
                520 => "value 2",
            ],
        ];

        $expected = [
            [
                "configurable_dropdown_attribute_1" => 519,
            ],
            [
                "configurable_dropdown_attribute_1" => 520,
            ],
        ];

        $combinator = new ConfigurableAttributeCombinator;
        $result = $combinator->generateCombinations($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return void
     */
    public function testTwoAttributes(): void
    {
        $input = [
            "configurable_dropdown_attribute_1" => [
                519 => "value 1",
                520 => "value 2",
            ],
            "configurable_dropdown_attribute_2" => [
                522 => "value 1",
                523 => "value 2",
            ],
        ];

        $expected = [
            [
                "configurable_dropdown_attribute_1" => 519,
                "configurable_dropdown_attribute_2" => 522,
            ],
            [
                "configurable_dropdown_attribute_1" => 519,
                "configurable_dropdown_attribute_2" => 523,
            ],
            [
                "configurable_dropdown_attribute_1" => 520,
                "configurable_dropdown_attribute_2" => 522,
            ],
            [
                "configurable_dropdown_attribute_1" => 520,
                "configurable_dropdown_attribute_2" => 523,
            ],
        ];

        $combinator = new ConfigurableAttributeCombinator;
        $result = $combinator->generateCombinations($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return void
     */
    public function testMultipleAttributes(): void
    {
        $input = [
            "configurable_dropdown_attribute_1" => [
                519 => "value 1",
                520 => "value 2",
            ],
            "configurable_dropdown_attribute_2" => [
                522 => "value 1",
                523 => "value 2",
            ],
            "configurable_dropdown_attribute_3" => [
                524 => "value 1",
                525 => "value 2",
            ],
        ];

        $expected = [
            [
                "configurable_dropdown_attribute_1" => 519,
                "configurable_dropdown_attribute_2" => 522,
                "configurable_dropdown_attribute_3" => 524,
            ],
            [
                "configurable_dropdown_attribute_1" => 519,
                "configurable_dropdown_attribute_2" => 522,
                "configurable_dropdown_attribute_3" => 525,
            ],
            [
                "configurable_dropdown_attribute_1" => 519,
                "configurable_dropdown_attribute_2" => 523,
                "configurable_dropdown_attribute_3" => 524,
            ],
            [
                "configurable_dropdown_attribute_1" => 519,
                "configurable_dropdown_attribute_2" => 523,
                "configurable_dropdown_attribute_3" => 525,
            ],
            [
                "configurable_dropdown_attribute_1" => 520,
                "configurable_dropdown_attribute_2" => 522,
                "configurable_dropdown_attribute_3" => 524,
            ],
            [
                "configurable_dropdown_attribute_1" => 520,
                "configurable_dropdown_attribute_2" => 522,
                "configurable_dropdown_attribute_3" => 525,
            ],
            [
                "configurable_dropdown_attribute_1" => 520,
                "configurable_dropdown_attribute_2" => 523,
                "configurable_dropdown_attribute_3" => 524,
            ],
            [
                "configurable_dropdown_attribute_1" => 520,
                "configurable_dropdown_attribute_2" => 523,
                "configurable_dropdown_attribute_3" => 525,
            ],
        ];

        $combinator = new ConfigurableAttributeCombinator;
        $result = $combinator->generateCombinations($input);

        $this->assertCount(8, $result);
        $this->assertContains([
            "configurable_dropdown_attribute_1" => 519,
            "configurable_dropdown_attribute_2" => 522,
            "configurable_dropdown_attribute_3" => 524,
        ], $result);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return void
     */
    public function testEmptyInput(): void
    {
        $input = [];
        $expected = [[]];
        $combinator = new ConfigurableAttributeCombinator;
        $result = $combinator->generateCombinations($input);
        $this->assertEquals($expected, $result);
    }
}
