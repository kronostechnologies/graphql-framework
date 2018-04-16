<?php


namespace Kronos\Tests\GraphQLFramework\Utils;


use Kronos\GraphQLFramework\Utils\DirectoryStringBuilder;
use PHPUnit\Framework\TestCase;

class DirectoryStringBuilderTest extends TestCase
{
    public function test_RootDirectory_join_ReturnsRootDirectory()
    {
        $retVal = DirectoryStringBuilder::join("/");

        $this->assertSame("/", $retVal);
    }

    public function test_RootDirectoryBackslash_join_ReturnsRootDirectoryForwardSlash()
    {
        $retVal = DirectoryStringBuilder::join("\\");

        $this->assertSame("/", $retVal);
    }

    public function test_ThreeUnslashedDirs_join_ReturnsCorrectDirectory()
    {
        $retVal = DirectoryStringBuilder::join("DirOne", "DirTwo", "DirThree");

        $this->assertSame("/DirOne/DirTwo/DirThree", $retVal);
    }

    public function test_ThreeSlashedDirs_join_ReturnsCorrectDirectory()
    {
        $retVal = DirectoryStringBuilder::join("DirOne/", "/DirTwo", "DirThree//");

        $this->assertSame("/DirOne/DirTwo/DirThree", $retVal);
    }

    public function test_InitialRelativeDir_join_ReturnsCorrectDirectory()
    {
        $retVal = DirectoryStringBuilder::join("./", "/DirTwo", "DirThree//");

        $this->assertSame("./DirTwo/DirThree", $retVal);
    }

    public function test_RootDir_joinFilename_ReturnsCorrectString()
    {
        $retVal = DirectoryStringBuilder::joinFilename("/", "AFile.txt");

        $this->assertSame("/AFile.txt", $retVal);
    }

    public function test_ThreeLevelDir_joinFilename_ReturnsCorrectString()
    {
        $retVal = DirectoryStringBuilder::joinFilename("/1/2/3", "AFile.txt");

        $this->assertSame("/1/2/3/AFile.txt", $retVal);
    }
}