<?php

/**
 * High level object oriented filesystem abstraction.
 *
 * @package filicious-core
 * @author  Tristan Lins <tristan.lins@bit3.de>
 * @author  Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author  Oliver Hoff <oliver@hofff.com>
 * @link    http://filicious.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Filicious\Test\Configuration;

use Filicious\Filesystem;
use Filicious\FilesystemConfig;
use Filicious\Test\DummyAdapter;
use Filicious\Test\DummyRootAdapter;
use PHPUnit_Framework_TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-10-17 at 10:24:36.
 */
class FilesystemConfigurationTest
	extends PHPUnit_Framework_TestCase
{
	/**
	 * @var DummyAdapter
	 */
	protected $adapter;

	/**
	 * @var Filesystem
	 */
	protected $fs;

	/**
	 * @var \Filicious\Internals\RootAdapter
	 */
	protected $rootAdapter;

	protected function setUp()
	{
		$this->adapter = new DummyAdapter(
			array(
			     FilesystemConfig::IMPLEMENTATION => 'Filicious\Test\DummyAdapter'
			)
		);

		$this->fs = new Filesystem(
			$this->adapter,
			array(
			     FilesystemConfig::STREAM_SUPPORTED => false
			)
		);

		/** @var \Filicious\Internals\RootAdapter $rootAdapter */
		$class               = new \ReflectionClass($this->fs);
		$rootAdapterProperty = $class->getProperty('adapter');
		$rootAdapterProperty->setAccessible(true);
		$this->rootAdapter = $rootAdapterProperty->getValue($this->fs);
	}

	protected function getAdapterConfigData()
	{
		return array(
		     'global' => array(
			     FilesystemConfig::IMPLEMENTATION => 'Filicious\Test\DummyAdapter'
		     )
		);
	}

	protected function getFilesystemConfigData()
	{
		return array(
		     '/'      => array(
			     FilesystemConfig::IMPLEMENTATION => 'Filicious\Test\DummyAdapter'
		     ),
		     'global' => array(
			     FilesystemConfig::STREAM_SUPPORTED => false
		     )
		);
	}

	/**
	 * @covers Filicious\FilesystemConfig
	 */
	public function testConfigurationLinking()
	{
		$this->assertAttributeEquals(
			$this->fs->getConfig(),
			'parentConfig',
			$this->adapter->getConfig()
		);
		$this->assertAttributeEquals(
			array(),
			'linkedConfigs',
			$this->adapter->getConfig()
		);
		$this->assertAttributeEquals(
			$this->adapter,
			'adapter',
			$this->adapter->getConfig()
		);
		$this->assertAttributeEquals(
			false,
			'opened',
			$this->adapter->getConfig()
		);
		$this->assertAttributeEquals(
			$this->getAdapterConfigData(),
			'data',
			$this->adapter->getConfig()
		);


		$this->assertAttributeEquals(
			null,
			'parentConfig',
			$this->fs->getConfig()
		);
		$this->assertAttributeEquals(
			array(
			     '/' => $this->adapter->getConfig()
			),
			'linkedConfigs',
			$this->fs->getConfig()
		);
		$this->assertAttributeEquals(
			$this->rootAdapter,
			'adapter',
			$this->fs->getConfig()
		);
		$this->assertAttributeEquals(
			false,
			'opened',
			$this->fs->getConfig()
		);
		$this->assertAttributeEquals(
			$this->getFilesystemConfigData(),
			'data',
			$this->fs->getConfig()
		);
	}

	/**
	 * @covers Filicious\FilesystemConfig
	 */
	public function testConfigurationOpenTwice()
	{
		$this->setExpectedException('Filicious\Exception\ConfigurationException');
		$this->fs
			->getConfig()
			->open()
			->open();
	}

	/**
	 * @covers Filicious\FilesystemConfig
	 */
	public function testConfigurationModifyNotOpened()
	{
		$this->setExpectedException('Filicious\Exception\ImmutableConfigException');
		$this->fs
			->getConfig()
			->set('foo', 'bar');
	}

	/**
	 * @covers Filicious\FilesystemConfig
	 */
	public function testConfigurationCommitNotOpened()
	{
		$this->setExpectedException('Filicious\Exception\ConfigurationException');
		$this->fs
			->getConfig()
			->commit();
	}

	/**
	 * @covers Filicious\FilesystemConfig
	 */
	public function testConfigurationCommitNotify()
	{
		$this->fs
			->getConfig()
			->open()
			->commit();
		$this->assertTrue($this->adapter->isNotified());
	}

	/**
	 * @covers Filicious\FilesystemConfig
	 */
	public function testConfigurationSetGetHas()
	{
		$config = $this->fs
			->getConfig()
			->open();

		$config->set('foo1', 'bar1');
		$this->assertTrue($config->has('foo1'));
		$this->assertEquals('bar1', $config->get('foo1'));

		$config->set('foo2', 'bar2', 'zap2');
		$this->assertTrue($config->has('foo2', 'zap2'));
		$this->assertEquals('bar2', $config->get('foo2', null, 'zap2'));

		$this->assertFalse($config->has('foo3'));
		$this->assertNull($config->get('foo3'));
		$this->assertEquals('bar3', $config->get('foo3', 'bar3'));

		$this->assertFalse($config->has('foo4', 'zap4'));
		$this->assertNull($config->get('foo4', null, 'zap4'));
		$this->assertEquals('bar4', $config->get('foo4', 'bar4', 'zap4'));

		$data = $this->getFilesystemConfigData();
		$data['global']['foo1'] = 'bar1';
		$data['zap2']['foo2'] = 'bar2';

		$this->assertAttributeEquals(
			$data,
			'data',
			$config
		);
	}

	/**
	 * @covers Filicious\FilesystemConfig
	 */
	public function testConfigurationSetRevert()
	{
		$config = $this->fs
			->getConfig()
			->open();

		$config->set('foo1', 'bar1');
		$config->set('foo2', 'bar2', 'zap2');

		$this->assertAttributeNotEquals(
			$this->getFilesystemConfigData(),
			'data',
			$config
		);

		$config->revert();

		$this->assertAttributeEquals(
			$this->getFilesystemConfigData(),
			'data',
			$config
		);

		// after revert, config has to be closed
		$this->setExpectedException('Filicious\Exception\ImmutableConfigException');
		$config->set('foo', 'bar');
	}
}
