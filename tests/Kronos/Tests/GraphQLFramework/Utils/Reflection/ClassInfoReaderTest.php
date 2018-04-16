<?php


namespace Kronos\Tests\GraphQLFramework\Utils\Reflection;


use Kronos\GraphQLFramework\Utils\Reflection\ClassInfoReader;
use Kronos\GraphQLFramework\Utils\Reflection\Exception\NoClassNameFoundException;
use PHPUnit\Framework\TestCase;

class ClassInfoReaderTest extends TestCase
{
    const BASE_MOCKS_DIR = __DIR__ . '/../../../../Mocks/Utils/Reflection/';

    const NO_NS_FILE = self::BASE_MOCKS_DIR . 'SampleNonNamespacedClass.php';
    const NS_FILE = self::BASE_MOCKS_DIR . 'SampleNamespacedClass.php';
    const NO_CLASS_FILE = self::BASE_MOCKS_DIR . 'SampleNoClass.php';

    protected function getMockContent($mockFileName)
    {
        return file_get_contents($mockFileName);
    }

    public function test_NoNamespaceFile_read_ReturnsEmptyNamespace()
    {
        $mock = $this->getMockContent(self::NO_NS_FILE);
        $reader = new ClassInfoReader($mock);

        $retVal = $reader->read()->getNamespace();

        $this->assertSame("", $retVal);
    }

    public function test_NoNamespaceFile_read_ReturnsClassName()
    {
        $mock = $this->getMockContent(self::NO_NS_FILE);
        $reader = new ClassInfoReader($mock);

        $retVal = $reader->read()->getClassName();

        $this->assertSame("SampleNonNamespacedClass", $retVal);
    }

    public function test_NamespacedFile_read_ReturnsNamespace()
    {
        $mock = $this->getMockContent(self::NS_FILE);
        $reader = new ClassInfoReader($mock);

        $retVal = $reader->read()->getNamespace();

        $this->assertSame("\\Dummy\\NS", $retVal);
    }

    public function test_NamespacedFile_read_ReturnsClassName()
    {
        $mock = $this->getMockContent(self::NS_FILE);
        $reader = new ClassInfoReader($mock);

        $retVal = $reader->read()->getClassName();

        $this->assertSame("SampleNamespacedClass", $retVal);
    }

    public function test_NoClassFile_read_ThrowsNoClassNameException()
    {
        $mock = $this->getMockContent(self::NO_CLASS_FILE);
        $reader = new ClassInfoReader($mock);

        $this->expectException(NoClassNameFoundException::class);

        $reader->read();
    }
}