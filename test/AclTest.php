<?php


namespace BCode\Acl\Test;


use BCode\Acl\Core\AbstractAction;
use BCode\Acl\Core\AbstractRule;
use BCode\Acl\Core\Acl;
use BCode\Acl\Core\Entity\Interfaces\Identity;
use BCode\Acl\Core\Entity\Permission;
use BCode\Acl\Core\Entity\PermissionRepository;
use BCode\Acl\Core\Query\AbstractHandler;
use BCode\Acl\Core\Service\ActionProvider;
use BCode\Acl\Core\Service\RuleProvider;
use BCode\Acl\Test\Mock\ResourceItem;

class AclTest extends \PHPUnit_Framework_TestCase
{
	/** @var AbstractHandler */
	protected $handler;
	/** @var PermissionRepository */
	private $repository;
	/** @var RuleProvider */
	private $provider;
	/** @var Acl */
	private $acl;
	/** @var ActionProvider */
	private $actionProvider;

	public function setUp()
	{
		$this->repository = $this->getMockBuilder(PermissionRepository::class)
			->disableOriginalConstructor()
			->getMock();

		$this->provider = $this->getMockBuilder(RuleProvider::class)
			->disableOriginalConstructor()
			->getMock();

		$this->actionProvider = $this->getMockBuilder(ActionProvider::class)
			->disableOriginalConstructor()
			->getMock();

		$this->handler = $this->getMockForAbstractClass(AbstractHandler::class, [], 'AnyHandler', false);
		$this->acl = new Acl($this->repository, $this->provider, $this->actionProvider, $this->handler);
	}

	/**
	 * @expectedException \Module\Acl\Core\Exception\IdentityNotSetException
	 */
	public function testCheckIdentityException()
	{
		$resource = new ResourceItem('any', 'any');
		$this->acl->can($resource, 'test');
	}

	/**
	 * @return array
	 */
	public function dataProvider()
	{
		$anonymous = $this->getMockBuilder(Identity::class)
			->getMock();
		$anonymous->method('isAnonymous')
			->willReturn(true);

		$data[] = [$anonymous, false];

		$root = $this->getMockBuilder(Identity::class)
			->getMock();
		$root->method('isAnonymous')
			->willReturn(false);
		$root->method('isRoot')
			->willReturn(true);

		$data[] = [$root, true];

		return $data;
	}

	/**
	 * @dataProvider dataProvider
	 *
	 * @param Identity $identity
	 * @param boolean $expected
	 */
	public function testIdentities(Identity $identity, $expected)
	{
		$this->acl->setIdentity($identity);
		$resource = new ResourceItem('any', 'any');
		$this->assertEquals($expected, $this->acl->can($resource, 'some'));
	}

	/**
	 * When resource was found and identity has no permission rule execute
	 */
	public function testWhenNoPermissionForIdentity()
	{
		$identity = $this->getMockBuilder(Identity::class)
			->getMock();
		$identity->method('isAnonymous')
			->willReturn(false);
		$identity->method('isRoot')
			->willReturn(false);

		$this->repository->method('findOneBy')
			->willReturn(null);

		$rule = $this->getMockForAbstractClass(AbstractRule::class, [], 'Rule', false, false, true, ['execute']);
		$rule->method('execute')
			->willReturn(true);

		$this->provider->method('get')
			->willReturn($rule);

		$this->acl->setIdentity($identity);

		$action = $this->getMockBuilder(AbstractAction::class)
			->getMock();

		$this->actionProvider->method('get')
			->willReturn($action);

		$resource = new ResourceItem('any', 'any');
		$this->assertTrue($this->acl->can($resource, 'any'));
	}

	/**
	 * When resource and permission was found check rule
	 */
	public function testWhenPermissionForIdentity()
	{
		$identity = $this->getMockBuilder(Identity::class)
			->getMock();
		$identity->method('isAnonymous')
			->willReturn(false);
		$identity->method('isRoot')
			->willReturn(false);

		$rule = $this->getMockForAbstractClass(AbstractRule::class, [], 'Rule', false, false, true, ['execute']);
		$rule->method('execute')
			->willReturn(true);

		$this->provider->method('get')
			->willReturn($rule);

		$permission = $this->getMockBuilder(Permission::class)
			->disableOriginalConstructor()
			->getMock();

		$this->repository->method('findOneBy')
			->willReturn($permission);

		$action = $this->getMockBuilder(AbstractAction::class)
			->getMock();
		$this->actionProvider->method('get')
			->willReturn($action);

		$this->acl->setIdentity($identity);

		$resource = new ResourceItem('any', 'any');
		$this->assertTrue($this->acl->can($resource, 'some'));
	}

	/**
	 * When identity has no permission to resource but hasFullAccess allow
	 */
	public function testWhenHasFullPermission()
	{
		$identity = $this->getMockBuilder(Identity::class)
			->disableOriginalConstructor()
			->getMock();
		$identity->method('isAnonymous')
			->willReturn(false);
		$identity->method('isRoot')
			->willReturn(false);
		$identity->method('hasFullPermission')
			->willReturn(true);

		$this->acl->setIdentity($identity);
		$resource = new ResourceItem('any', 'any', []);

		$this->assertTrue($this->acl->can($resource, 'ss'));
	}

	public function dataProvider3()
	{
		$identity = $this->getMockBuilder(Identity::class)
			->disableOriginalConstructor()
			->getMock();
		$identity->method('isAnonymous')
			->willReturn(false);
		$identity->method('isRoot')
			->willReturn(false);

		$identity2 = $this->getMockBuilder(Identity::class)
			->disableOriginalConstructor()
			->getMock();
		$identity2->method('isAnonymous')
			->willReturn(false);
		$identity2->method('isRoot')
			->willReturn(true);

		return [
			[$identity, false],
			[$identity2, true]
		];
	}

	/**
	 * When resource need technical user denny
	 *
	 * technical is checking in acl
	 *
	 * @dataProvider dataProvider3
	 * @param Identity $identity
	 * @param boolean $expected
	 */
	public function testTechnicalAccess(Identity $identity, $expected)
	{
		$this->acl->setIdentity($identity);
		$resource = new ResourceItem('any', 'any', [], true);
		$this->assertEquals($expected, $this->acl->can($resource, 'show'));
	}

}