<?php


use Doctrine\DBAL\Query\QueryBuilder;

interface DoctrineFilterInterface extends FilterInterface
{
	public function applyToQueryBuilder(QueryBuilder $queryBuilder);
}