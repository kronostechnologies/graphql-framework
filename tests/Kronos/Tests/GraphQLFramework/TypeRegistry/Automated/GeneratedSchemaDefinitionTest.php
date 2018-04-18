<?php


namespace Kronos\Tests\GraphQLFramework\TypeRegistry\Automated;


use Kronos\GraphQLFramework\TypeRegistry\Automated\GeneratedSchemaDefinition;
use PHPUnit\Framework\TestCase;

class GeneratedSchemaDefinitionTest extends TestCase
{
    const DIR_WITH_TRAILING = '/adir/';
    const DIR_NO_TRAILING = '/adir';

    const EXPECTED_DTO_DIR = self::DIR_WITH_TRAILING . GeneratedSchemaDefinition::GENERATED_SCHEMA_DTO_DIR;
    const EXPECTED_TYPE_DIR = self::DIR_WITH_TRAILING . GeneratedSchemaDefinition::GENERATED_SCHEMA_TYPE_DIR;

    public function test_DirectorySetNoTrailingSlash_getTypesDirectory_ReturnsCorrectDirectory()
    {
        $segmenter = new GeneratedSchemaDefinition(self::DIR_NO_TRAILING);

        $retVal = $segmenter->getTypesDirectory();

        $this->assertSame(self::EXPECTED_TYPE_DIR, $retVal);
    }

    public function test_DirectorySetWithTrailingSlash_getTypesDirectory_ReturnsCorrectDirectory()
    {
        $segmenter = new GeneratedSchemaDefinition(self::DIR_WITH_TRAILING);

        $retVal = $segmenter->getTypesDirectory();

        $this->assertSame(self::EXPECTED_TYPE_DIR, $retVal);
    }

    public function test_DirectorySetNoTrailingSlash_getDTOsDirectory_ReturnsCorrectDirectory()
    {
        $segmenter = new GeneratedSchemaDefinition(self::DIR_NO_TRAILING);

        $retVal = $segmenter->getDTOsDirectory();

        $this->assertSame(self::EXPECTED_DTO_DIR, $retVal);
    }

    public function test_DirectorySetWithTrailingSlash_getDTOsDirectory_ReturnsCorrectDirectory()
    {
        $segmenter = new GeneratedSchemaDefinition(self::DIR_NO_TRAILING);

        $retVal = $segmenter->getDTOsDirectory();

        $this->assertSame(self::EXPECTED_DTO_DIR, $retVal);
    }
}