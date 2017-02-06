<?php


namespace BCode\Acl\Core\Service;


use BCode\Acl\Core\AbstractAction;

class ActionProvider
{
	/** @var ActionRegistry  */
	private $registry;

	/**
	 * @param ActionRegistry $registry
	 */
	public function __construct(ActionRegistry $registry)
	{
		$this->registry = $registry;
	}

	/**
	 * @param string $symbol
	 * @param string $ruleSymbol
	 * @return \BCode\Acl\Core\AbstractAction
	 */
	public function get($symbol, $ruleSymbol)
	{
		foreach ($this->registry->get($symbol) as $action)
		{
			if ($action->belongTo($ruleSymbol))
			{
				return $action;
			}
		}

		throw new \RuntimeException('Action does not belong to resource');
	}

	/**
	 * returns array with actions
	 * [
	 * 		resource => [
	 * 			action1, action2, action3, ..., actionN
	 * 		]
	 * ]
	 *
	 * @return array
	 */
	public function getAllOrdered()
	{
		$all = [];
		/**
		 * @var string $actionName
		 * @var array $data
		 */
		foreach ($this->registry->all() as $actionName => $data)
		{
			/**
			 * @var string $ruleSymbol
			 * @var AbstractAction $action
			 */
			foreach($data as $ruleSymbol => $action)
			{
				$all[$ruleSymbol][] = $action;
			}
		}

		return $all;
	}
}