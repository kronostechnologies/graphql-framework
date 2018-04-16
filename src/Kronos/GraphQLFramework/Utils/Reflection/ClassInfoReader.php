<?php


namespace Kronos\GraphQLFramework\Utils\Reflection;


use Kronos\GraphQLFramework\Utils\Reflection\Exception\NoClassNameFoundException;

class ClassInfoReader
{
    /**
     * @var string
     */
    protected $classFileContent;

    /**
     * @var ClassInfoReaderResult|null
     */
    protected $result;

    /**
     * @param string $classFileContent
     */
    public function __construct($classFileContent)
    {
        $this->classFileContent = $classFileContent;
    }

    /**
     * @return ClassInfoReaderResult
     * @throws NoClassNameFoundException
     */
    public function read()
    {
        if ($this->result === null) {
            $this->readIntoResult();
        }

        return $this->result;
    }

    /**
     * @throws NoClassNameFoundException
     */
    protected function readIntoResult()
    {
        $tokens = token_get_all($this->classFileContent);

        $namespace = $this->extractNamespace($tokens);
        $className = $this->extractClassName($tokens);

        $this->result = new ClassInfoReaderResult($namespace, $className);
    }

    /**
     * @param array $tokens
     * @return string
     */
    protected function extractNamespace(array $tokens)
    {
        $extractedNamespace = "";
        $inNamespace = false;

        foreach ($tokens as $token) {
            if (!$inNamespace) {
                if ($token[0] === T_NAMESPACE) {
                    $inNamespace = true;
                }
            } else {
                if ($token[0] === T_STRING) {
                    $extractedNamespace .= "\\{$token[1]}";
                } else if ($token === '{' || $token === ';') {
                    break;
                }
            }
        }

        return $extractedNamespace;
    }

    /**
     * @param array $tokens
     * @return string
     * @throws NoClassNameFoundException
     */
    protected function extractClassName(array $tokens)
    {
        foreach ($tokens as $idx => $token) {
            if ($token[0] === T_CLASS) {
                return $tokens[$idx + 2][1];
            }
        }

        throw new NoClassNameFoundException();
    }
}