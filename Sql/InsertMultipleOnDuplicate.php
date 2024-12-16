<?php
/**
 * Created by Q-Solutions Studio
 *
 * @category    Qoliber
 * @package     Qoliber_CatalogGenerator
 * @author      Jakub Winkler <jwinkler@qoliber.com>
 */

declare(strict_types=1);

namespace Qoliber\CatalogGenerator\Sql;

class InsertMultipleOnDuplicate
{
    /** @var null|mixed[] */
    private ?array $_onDuplicate = [];

    /**
     * On Duplicate Method
     *
     * @param string[] $columnsToUpdate
     * @return $this
     */
    public function onDuplicate(array $columnsToUpdate): self
    {
        $this->_onDuplicate = $columnsToUpdate;

        return $this;
    }

    // phpcs:disable
    /**
     * Flatten a multi-dimensional array
     *
     * @param mixed[][] $productData
     * @return mixed[]
     */
    public static function flatten(array $productData): array
    {
        $flattened = [];

        array_walk_recursive($productData, static function ($a) use (&$flattened) {
            $flattened[] = $a;
        });

        return $flattened;
    }
    // phpcs:enable

    /**
     * Build Query
     *
     * @param string $table
     * @param mixed[] $columnNames
     * @param int $rowCount
     * @return string
     */
    public function buildInsertQuery(string $table, array $columnNames, int $rowCount): string
    {
        $rowTemplate = sprintf('(%s)', implode(',', array_fill(0, count($columnNames), '?')));

        $statement = sprintf(
            'INSERT INTO %s (%s) VALUES %s%s',
            $table,
            implode(',', $columnNames),
            str_repeat(
                sprintf('%s, ', $rowTemplate),
                $rowCount - 1
            ),
            $rowTemplate
        );

        if (!$this->_onDuplicate) {
            return $statement;
        }

        $onDuplicateStatements = [];

        foreach ($this->_onDuplicate as $columnName) {
            $onDuplicateStatements[] = sprintf('%1$s = VALUES(%1$s)', $columnName);
        }

        return sprintf('%s ON DUPLICATE KEY UPDATE %s', $statement, implode(', ', $onDuplicateStatements));
    }
}
