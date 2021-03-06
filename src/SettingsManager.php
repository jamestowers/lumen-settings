<?php
/**
 * Laravel 4 - Persistent Settings
 * 
 * @author   Andreas Lutro <anlutro@gmail.com>
 * @license  http://opensource.org/licenses/MIT
 * @package  l4-settings
 */

namespace anlutro\LaravelSettings;

use Illuminate\Support\Manager;
use Illuminate\Foundation\Application;

class SettingsManager extends Manager
{
	public function getDefaultDriver()
	{	
		return $this->getConfig('anlutro/l4-settings::store');
	}

	public function createJsonDriver()
	{
		$path = $this->getConfig('anlutro/l4-settings::path');

		return new JsonSettingStore($this->app['files'], $path);
	}

	public function createDatabaseDriver()
	{
		$connectionName = $this->getConfig('anlutro/l4-settings::connection');
		$connection = $this->app['db']->connection($connectionName);
		$table = $this->getConfig('anlutro/l4-settings::table');
		$keyColumn = $this->getConfig('anlutro/l4-settings::keyColumn');
		$valueColumn = $this->getConfig('anlutro/l4-settings::valueColumn');

		return new DatabaseSettingStore($connection, $table, $keyColumn, $valueColumn);
	}

	public function createMemoryDriver()
	{
		return new MemorySettingStore();
	}

	public function createArrayDriver()
	{
		return $this->createMemoryDriver();
	}

	protected function getConfig($key)
	{
		$key = str_replace('anlutro/l4-settings::', 'settings.', $key);

		return $this->app['config']->get($key);
	}
}
