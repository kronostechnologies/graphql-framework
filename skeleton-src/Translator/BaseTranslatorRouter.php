<?php

/**
 * Allows conversions of an entity to another in a GraphQL context.
 */
abstract class BaseTranslatorRouter
{
	/**
	 * In-memory cache of translator instances.
	 *
	 * @var TranslatorInterface[]
	 */
	private $instancedTranslators = [];

	/**
	 * Gets a list of available translators for an entity. The declaration of such an array should
	 * be "Target DTO FQN" => "Target Translator FQN".
	 *
	 * @return string[]
	 */
	protected abstract function getMappedTranslators();

	/**
	 * Returns the instance of a translator with the specified DTO FQN.
	 *
	 * @param string $entityFQN
	 * @return null|TranslatorInterface
	 */
	private function getInstanceOfTranslatorByEntityFQN($entityFQN)
	{
		foreach ($this->getMappedTranslators() as $mappedTargetEntityFQN => $mappedTranslatorFQN)
		{
			if ($entityFQN === $mappedTargetEntityFQN)
			{
				foreach ($this->instancedTranslators as $instancedTranslator)
				{
					if ($instancedTranslator instanceof $mappedTranslatorFQN)
					{
						return $instancedTranslator;
					}
				}

				$translatorInstance = new $mappedTranslatorFQN();
				$this->instancedTranslators[] = $translatorInstance;

				return $translatorInstance;
			}
		}

		return null;
	}

	/**
	 * Translates an entity to another and returns its value.
	 *
	 * @param mixed $entity
	 * @param string $targetEntityFQN
	 * @return mixed
	 * @throws Exception
	 */
	public function translate($entity, $targetEntityFQN)
	{
		$translatorInstance = $this->getInstanceOfTranslatorByEntityFQN($targetEntityFQN);

		if ($translatorInstance === null)
		{
			$selfFQN = get_class($this);

			throw new \Exception("No translator bound for {$targetEntityFQN} in {$selfFQN}");
		}

		return $translatorInstance->translate($entity);
	}
}