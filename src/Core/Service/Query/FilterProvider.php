<?php


namespace BCode\Acl\Core\Service\Query;


use BCode\Acl\Core\Query\AbstractFilter;

class FilterProvider
{

	/** @var FilterRegistry */
	private $registry;

	/**
	 * @param FilterRegistry $registry
	 */
	public function __construct(FilterRegistry$registry)
	{
		$this->registry = $registry;
	}

	/**
	 * @param $symbol
	 * @return AbstractFilter
	 */
	public function get($symbol)
	{
		return $this->registry->get($symbol);
	}

	/**
	 * @return AbstractFilter[]
	 */
	public function all()
	{
		return $this->registry->all();
	}

}