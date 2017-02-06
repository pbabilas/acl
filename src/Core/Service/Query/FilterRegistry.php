<?php


namespace BCode\Acl\Core\Service\Query;


use BCode\Acl\Core\Query\AbstractFilter;

class FilterRegistry
{
	/**
	 * Stores filters ordered by resource
	 * One resource may have many filters
	 *
	 * @var array
	 */
	private $filters = [];

	/**
	 * @param AbstractFilter $filter
	 */
	public function register(AbstractFilter $filter)
	{
		if (class_exists($filter->getResource()))
		{
			$this->filters[$filter->getResource()][] = $filter;
			return;
		}

		throw new \RuntimeException('Resource not exists. Provide existing class!');
	}

	/**
	 * @param string $symbol
	 * @return AbstractFilter[]
	 */
	public function get($symbol)
	{
		if (isset($this->filters[$symbol]))
		{
			$condition = clone($this->filters[$symbol]);

			return $condition;
		}

		$message = sprintf("Unregistered rule symbol: %s", $symbol);
		throw new \LogicException($message);
	}

	/**
	 * @return AbstractFilter[]
	 */
	public function all()
	{
		$collection = [];
		foreach ($this->filters as $filters)
		{
			$collection = array_merge($collection, $filters);
		}

		return $collection;
	}
}