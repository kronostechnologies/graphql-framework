<?php

/**
 * Serves as a baseline class to attach logic services to a mutation.
 */
abstract class BaseMutationService
{
	/**
	 * Seeks the correct mutation translator for the entity, and returns the translation result.
	 *
	 * @param string $destinationDTOFQN
	 * @param mixed $result
	 * @return mixed
	 */
	protected function translateResultToGraphQLDTO($destinationDTOFQN, $result)
	{

	}

	/**
	 * Seeks out the correct mutation translator for the entity, and returns the translation result.
	 *
	 * @param string $destinationResultFQN
	 * @param mixed $result
	 * @return mixed
	 */
	protected function translateGraphQLDTOToResult($destinationResultFQN, $result)
	{

	}
}