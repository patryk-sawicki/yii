<?php
/**
 * CPgsqlCommandBuilder class file.
 *
 * @author Timur Ruziev <resurtm@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CPgsqlCommandBuilder provides basic methods to create query commands for tables.
 *
 * @author Timur Ruziev <resurtm@gmail.com>
 * @package system.db.schema.pgsql
 * @since 1.1.14
 */
class CPgsqlCommandBuilder extends CDbCommandBuilder
{
	/**
	 * Returns default value of the integer/serial primary key. Default value means that the next
	 * autoincrement/sequence value would be used.
	 * @return string default value of the integer/serial primary key.
	 * @since 1.1.14
	 */
	protected function getIntegerPrimaryKeyDefaultValue()
	{
		return 'DEFAULT';
	}

	/**
	 * Creates a SELECT command for a single table.
	 * Modified to pass limit and offset to the created CDbCommand object when useCursors are on.
	 * @param mixed $table the table schema ({@link CDbTableSchema}) or the table name (string).
	 * @param CDbCriteria $criteria the query criteria
	 * @param string $alias the alias name of the primary table. Defaults to 't'.
	 * @return NetDbCommand query command.
	 */
	public function createFindCommand($table, $criteria, $alias='t')
	{
		$command = parent::createFindCommand($table, $criteria, $alias);
		if ($this->getDbConnection()->useCursors) {
			$command->limit($criteria->limit, $criteria->offset);
		}
		return $command;
	}

	/**
	 * Alters the SQL to apply LIMIT and OFFSET, unless cursors are to be used.
	 * @param string $sql SQL query string without LIMIT and OFFSET.
	 * @param integer $limit maximum number of rows, -1 to ignore limit.
	 * @param integer $offset row offset, -1 to ignore offset.
	 * @return string SQL with LIMIT and OFFSET
	 */
	public function applyLimit($sql, $limit, $offset)
	{
		if ($this->getDbConnection()->useCursors && stripos($sql, 'select ') === 0) {
			return $sql;
		}
		return parent::applyLimit($sql, $limit, $offset);
	}
}
