<?php


namespace BCode\Acl\Test\Core\Service;


use BCode\Acl\Core\Service\ActionProvider;
use BCode\Acl\Core\Service\ActionRegistry;
use BCode\Payment\BlueMedia\Action\AbstractAction;

class ActionProviderTest extends \PHPUnit_Framework_TestCase
{

	public function testGetNonUniqueActionForDifferentRules()
	{

		$registry = $this->getMockBuilder(ActionRegistry::class)
			->disableOriginalConstructor()
			->getMock();

		$action = $this->getMockBuilder(AbstractAction::class)
			->disableOriginalConstructor()
			->getMock();

		$return = [
			'action_one' => [
				'rule_one' => clone $action,
				'rule_two' => clone $action
			],
			'action_two' => ['rule_one' => clone $action]
		];
		$registry->method('all')
			->willReturn($return);
		/** @var ActionRegistry $registry */
		$provider = new ActionProvider($registry);
		$ordered = $provider->getAllOrdered();


		foreach($ordered as $ruleSymbol => $actions)
		{
			if ($ruleSymbol == 'rule_one')
			{
				$this->assertEquals(2, count($actions));
			}
			elseif($ruleSymbol == 'rule_two')
			{
				$this->assertEquals(1, count($actions));
			}
			else
			{
				$this->assertTrue(false, 'Wrong rule');
			}
		}
	}

}