<?php

define("TEST_DIR", dirname(__FILE__) . "/temp");

/**
 * @author Jan Papousek
 */
class FileTest extends EskymoTestCase {

	/** @Skip */
	protected function testAbsolutePath() {}

	/** @TestThrow(NullPointerException) */
	protected function testConstructorEmpty() {
		$file = new File("");
	}

	protected function testCreateNewFile() {
		$file = new File(TEST_DIR . "/temporary_file");
		$file->createNewFile();
		$this->assertTrue($file->exists());
	}

	protected function testDelete() {
		$file = new File(TEST_DIR . "/temporary_file");
		$file->createNewFile();
		$file->delete();
		$this->assertFalse($file->exists());
	}

	protected function testExtension() {
		$file = new File(TEST_DIR . "/temporary_file.ahdcs.txt");
		$this->assertEquals("txt", $file->getExtension());
	}

	protected function testIsDirectory() {
		$dir = new File(TEST_DIR);
		$this->assertTrue($dir->isDirectory());
		$file = new File(TEST_DIR . "/temporary_file");
		$file->createNewFile();
		$this->assertFalse($file->isDirectory());
	}

	protected function testIsFile() {
		$file = new File(TEST_DIR . "/temporary_file");
		$file->createNewFile();
		$this->assertTrue($file->isFile());
		$dir = new File(TEST_DIR);
		$this->assertFalse($dir->isFile());
	}

	protected function testLastModified() {		
		$file = new File(TEST_DIR . "/temporary_file.ahdcs.txt");
		if ($file->exists()) {
			$file->delete();
		}
		$created = time();
		$file->createNewFile();
		$this->assertEquals($created, $file->getLastModified());
	}

	/** @Skip */
	protected function testListFiles() {}

	/** @Skip */
	protected function testListPaths() {}

	/** @Skip */
	protected function testMkdir() {}

	/** @Skip */
	protected function testMkdirs() {}

	protected function testName() {
		$file = new File(TEST_DIR . "/temporary file");
		$this->assertEquals("temporary file", $file->getName());
	}

	protected function testPath() {
		$file = new File("/tmp/aaa/");
		$this->assertEquals("/tmp/aaa", $file->getPath());
	}

	protected function testParent() {
		$file = new File("/tmp/aaa");
		$this->assertEquals("/tmp", $file->getParentFile()->getPath());
	}
}
