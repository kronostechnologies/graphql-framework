<?php


namespace Kronos\GraphQLFramework\Relay;


use function array_key_exists;
use function is_object;
use Kronos\GraphQLFramework\FrameworkMiddleware;
use ReflectionClass;
use function str_replace;

class RelayMiddleware implements FrameworkMiddleware
{
    const RELAY_ENTITY_SUFFIX = '.entityName';

    /**
     * @var string
     */
    protected $idFieldName;

    /**
     * @var string
     */
    protected $strippedNamespacePath;

    /**
     * @param string $idFieldName
     * @param string $strippedNamespacePath
     */
    public function __construct($idFieldName, $strippedNamespacePath)
    {
        $this->idFieldName = $idFieldName;
        $this->strippedNamespacePath = $strippedNamespacePath;
    }

    /**
     * @param array|mixed $request
     * @return array|mixed
     * @throws Exception\InvalidPayloadException
     */
    public function modifyRequest($request)
    {
        if (is_array($request)) {
            if (array_key_exists($this->idFieldName, $request)) {
                $relayIdentifier = new RelayGlobalIdentifier();
                $relayIdentifier->deserialize($request[$this->idFieldName]);

                $request[$this->idFieldName] = $relayIdentifier->getIdentifier();
                $request[$this->idFieldName . self::RELAY_ENTITY_SUFFIX] = $relayIdentifier->getEntityName();
            }

            foreach ($request as $key => $value) {
                if (is_array($value)) {
                    $request[$key] = $this->modifyRequest($value);
                }
            }

            return $request;
        } else {
            return $request;
        }
    }

    /**
     * @param \stdClass|mixed $response
     * @return \stdClass|mixed
     * @throws \ReflectionException
     */
    public function modifyResponse($response)
    {
        if (is_array($response)) {
            // Array of DTOs
            foreach ($response as $key => $val) {
                $response[$key] = $this->modifyResponse($val);
            }

            return $response;
        } else if (is_object($response)) {
            $response = clone $response;
            $reflectionClass = new ReflectionClass($response);

            foreach ($reflectionClass->getProperties() as $property) {
                if ($property->getName() === $this->idFieldName) {
                    $relayGID = new RelayGlobalIdentifier();
                    $relayGID->setIdentifier($property->getValue($response));

                    $shortenedEntityName = str_replace(
                        $this->strippedNamespacePath,
                        "",
                        get_class($response)
                    );

                    $relayGID->setEntityName($shortenedEntityName);

                    $property->setValue($response, $relayGID->serialize());
                } else if (is_object($property->getValue($response))) {
                    $property->setValue($response, $this->modifyResponse($property->getValue()));
                }
            }

            return $response;
        } else {
            return $response;
        }
    }
}
