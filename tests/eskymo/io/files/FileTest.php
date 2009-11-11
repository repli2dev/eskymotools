<?php

define("TEST_DIR", dirname(__FILE__) . "/temp");

/**
 * @author Jan Papousek
 */
class FileTest extends EskymoTestCase {

	/** @Skip */
	public function testAbsolutePath() {}

	/** @TestThrow(NullPointerException) */
	public function testConstructorEmpty() {
		$file = new File("");
	}

	public function testCreateNewFile() {
		$file = new File(TEST_DIR . "/temporary_file");
		$file->createNewFile();
		$this->assertTrue($file->exists());
	}

	public function testDelete() {
		$file = new File(TEST_DIR . "/temporary_file");
		$file->createNewFile();
		$file->delete();
		$this->assertFalse($file->exists());
	}

	public function testExtension() {
		$file = new File(TEST_DIR . "/temporary_file.ahdcs.txt");
		$this->assertEquals("txt", $file->getExtension());
	}

	public function testIsDirectory() {
		$dir = new File(TEST_DIR);
		$this->assertTrue($dir->isDirectory());
		$file = new File(TEST_DIR . "/temporary_file");
		$file->createNewFile();
		$this->assertFalse($file->isDirectory());
	}

	public function testIsFile() {
		$file = new File(TEST_DIR . "/temporary_file");
		$file->createNewFile();
		$this->assertTrue($file->isFile());
		$dir = new File(TEST_DIR);
		$this->assertFalse($dir->isFile());
	}

	public function testLastModified() {
		$file = new File(TEST_DIR . "/temporary_file.ahdcs.txt");
		if ($file->exists()) {
			$file->delete();
		}
		$created = time();
		$file->createNewFile();
		$this->assertEquals($created, $file->getLastModified());
	}

	/** @Skip */
	public function testListFiles() {}

	/** @Skip */
	public function testListPaths() {}

	/** @Skip */
	public function testMkdir() {}

	/** @Skip */
	public function testMkdirs() {}

	public function testName() {
		$file = new File(TEST_DIR . "/temporary file");
		$this->assertEquals("temporary file", $file->getName());
	}

	public function testPath() {
		$file = new File("/tmp/aaa/");
		$this->assertEquals("/tmp/aaa", $file->getPath());
	}

	public function testParent() {
		$file = new File("/tmp/aaa");
		$this->assertEquals("/tmp", $file->getParentFile()->getPath());
	}
}
