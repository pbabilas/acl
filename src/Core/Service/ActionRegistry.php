<?php


namespace BCode\Acl\Core\Service;


use BCode\Acl\Core\AbstractAction;

class ActionRegistry
{
	/**
	 * @var AbstractAction[]
	 */
	private $actions= [];

	/**
	 * @param AbstractAction $action
	 */
	public function register(AbstractAction $action)
	{
		//action may have many unique resources,
		if (isset($this->actions[$action->getName()][$action->getFor()]))
		{
			$msg = sprintf('Action %s for rule %s already registered', $action->getName(), $action->getFor());
			throw new \RuntimeException($msg);
		}

		$this->actions[$action->getName()][$action->getFor()] = $action;
	}

	/**
	 * @param string $symbol
	 * @return AbstractAction[]
	 */
	public function get($symbol)
	{
		if (isset($this->actions[$symbol]))
		{
			$actions = $this->actions[$symbol];

			return $actions;
		}

		$message = sprintf("Unregistered rule symbol: %s", $symbol);
		throw new \LogicException($message);
	}

	/**
	 * @return AbstractAction[]
	 */
	public function all()
	{
		return $this->actions;
	}
}