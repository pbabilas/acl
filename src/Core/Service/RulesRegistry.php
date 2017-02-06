<?php


namespace BCode\Acl\Core\Service;


use BCode\Acl\Core\AbstractRule;

class RulesRegistry
{

	/**
	 * @var AbstractRule[]
	 */
	private $rules = [];

	/**
	 * @param AbstractRule $rule
	 */
	public function register(AbstractRule $rule)
	{
		if (isset($this->rules[$rule->getSymbol()]))
		{
			$message = sprintf("Rule at offset %s is already registered", $rule->getSymbol());
			throw new \LogicException($message);
		}

		$this->rules[$rule->getSymbol()] = $rule;
	}

	/**
	 * @param string $symbol
	 * @return AbstractRule
	 */
	public function get($symbol)
	{
		if (isset($this->rules[$symbol]))
		{
			$condition = clone($this->rules[$symbol]);

			return $condition;
		}

		$message = sprintf("Unregistered rule symbol: %s", $symbol);
		throw new \LogicException($message);
	}

	/**
	 * @return AbstractRule[]
	 */
	public function all()
	{
		return $this->rules;
	}

}