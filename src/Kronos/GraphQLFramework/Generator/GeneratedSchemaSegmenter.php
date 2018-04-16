<?php


namespace Kronos\GraphQLFramework\Generator;


class GeneratedSchemaSegmenter
{
    const GENERATED_SCHEMA_DTO_DIR = 'DTO';
    const GENERATED_SCHEMA_TYPE_DIR = 'Types';

    /**
     * @var string
     */
    protected $generatedSchemaDirectory;

    /**
     * @param string $generatedSchemaDirectory
     */
    public function __construct($generatedSchemaDirectory)
    {
        $this->generatedSchemaDirectory = $generatedSchemaDirectory;
    }

    /**
     * @return string
     */
    public function getGeneratedSchemaDirectory()
    {
        return $this->generatedSchemaDirectory;
    }

    /**
     * @return string
     */
    public function getTypesDirectory()
    {

    }

    /**
     * @return string
     */
    public function getDTOsDirectory()
    {

    }
}