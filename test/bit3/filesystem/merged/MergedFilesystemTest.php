<?php
namespace bit3\filesystem\merged;

require_once(__DIR__ . '/../../../bootstrap.php');

use bit3\filesystem\local\LocalFilesystem;
use bit3\filesystem\iterator\FilesystemIterator;
use bit3\filesystem\iterator\RecursiveFilesystemIterator;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-10-17 at 10:47:54.
 */
class MergedFilesystemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MergedFilesystem
     */
    protected $merged;

    /**
     * @var LocalFilesystem
     */
    protected $src;

    /**
     * @var LocalFilesystem
     */
    protected $test;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->merged = new MergedFilesystem();
        $this->src = new LocalFilesystem(__DIR__ . '/../../../../src');
        $this->test = new LocalFilesystem(__DIR__ . '/../../../../test');

        $this->merged->mount($this->src, 'lib/php-filesystem/src');
        $this->merged->mount($this->test, 'lib/php-filesystem/test');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers bit3\filesystem\merged\MergedFilesystem::mount
     * @todo   Implement testMount().
     * /
    public function testMount()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers bit3\filesystem\merged\MergedFilesystem::umount
     * @todo   Implement testUmount().
     * /
    public function testUmount()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers bit3\filesystem\merged\MergedFilesystem::getRoot
     * @todo   Implement testGetRoot().
     * /
    public function testGetRoot()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers bit3\filesystem\merged\MergedFilesystem::getFile
     * @todo   Implement testGetFile().
     * /
    public function testGetFile()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers bit3\filesystem\merged\MergedFilesystem::diskFreeSpace
     * @todo   Implement testDiskFreeSpace().
     * /
    public function testDiskFreeSpace()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers bit3\filesystem\merged\MergedFilesystem::diskTotalSpace
     * @todo   Implement testDiskTotalSpace().
     * /
    public function testDiskTotalSpace()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers bit3\filesystem\merged\MergedFilesystem::glob
     * @todo   Implement testGlob().
     * /
    public function testGlob()
    {
        $root = $this->merged->getRoot();

        var_dump($root->listAll());

        var_dump($this->merged->glob('*'));
    }
    */

    public function testTree()
    {
        $root = $this->merged->getRoot();

        $filesystemIterator = new RecursiveFilesystemIterator($root, FilesystemIterator::CURRENT_AS_FILENAME);
        $treeIterator = new \RecursiveTreeIterator($filesystemIterator);

        foreach ($treeIterator as $path) {
            echo $path . "\n";
        }
    }
}
