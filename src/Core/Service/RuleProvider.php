<?php


namespace BCode\Acl\Core\Service;


use BCode\Acl\Core\AbstractRule;

class RuleProvider
{
	/** @var RulesRegistry */
	private $registry;

	/**
	 * @param RulesRegistry $registry
	 */
	public function __construct(RulesRegistry $registry)
	{
		$this->registry = $registry;
	}

	/**
	 * @param $symbol
	 * @return AbstractRule
	 */
	public function get($symbol)
	{
		return $this->registry->get($symbol);
	}

}