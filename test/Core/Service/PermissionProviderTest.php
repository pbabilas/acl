<?php


namespace BCode\Acl\Test\Core\Service;


use BCode\Acl\Core\Entity\Interfaces\Identity;
use BCode\Acl\Core\Entity\Interfaces\ResourceInterface;
use BCode\Acl\Core\Entity\Permission;
use BCode\Acl\Core\Entity\PermissionRepository;
use BCode\Acl\Core\Service\PermissionProvider;

class PermissionProviderTest extends \PHPUnit_Framework_TestCase
{

	public function getTestCases()
	{
		$identity = $this->getMockBuilder(Identity::class)
			->getMock();
		$identity->method('getUniqueId')
			->willReturn(1);

		$arr = ['ss' => ['klucz' => 'sd']];
		$arr2 = ['ss' => ['klucz' => 'a']];

		$permission = new Permission(123, $identity, 'Some', $arr);
		$permissionClone = clone $permission;
		$permission2 = new Permission(234, $identity, 'Some', ['kolejny' => 1]);
		$permission3 = new Permission(234, $identity, 'Some', $arr2);

		return [
			[null, $permission, null, 'ss'],
			[$identity, $permission, $permission2, 'kolejny'],
			[$identity, $permissionClone, $permission3, ['ss' => ['klucz' => ['sd', 'a']]]],
		];
	}

	/**
	 * @dataProvider getTestCases
	 *
	 * @param Identity $parent
	 * @param Permission $permissions
	 * @param Permission $permissions2
	 * @param $expected
	 */
	public function testGetPermission(Identity $parent = null, Permission $permissions, Permission $permissions2 = null, $expected)
	{
		$repository = $this->getMockBuilder(PermissionRepository::class)
			->disableOriginalConstructor()
			->getMock();

		$repository->expects($this->at(0))
			->method('findOneBy')
			->willReturn($permissions);
		if ($permissions2)
		{
			$repository->expects($this->at(1))
				->method('findOneBy')
				->willReturn($permissions2);
		}

		$identity = $this->getMockBuilder(Identity::class)
			->getMock();
		$identity->method('getParent')
			->willReturn($parent);
		$identity->method('getUniqueId')
			->willReturn(1);

		/** @var ResourceInterface $resource */
		$resource = $this->getMockBuilder(ResourceInterface::class)
			->getMock();
		$resource->method('getResourceName')
			->willReturn('SomeName');

		$provider = new PermissionProvider($repository);
		$permission = $provider->get($identity, $resource->getResourceName());

		if (is_array($expected))
		{
			$this->assertEquals($expected, $permission->getActions()->toArray());
		}
		else
		{
			$this->assertTrue($permission->getActions()->has($expected));
		}
	}

	/**
	 * When user has no permission gets from parent but id must stay from user.
	 */
	public function testPermissionWhenNoUserPermission()
	{
		$parent = $this->getMockBuilder(Identity::class)
			->getMock();
		$parent->method('getUniqueId')
			->willReturn('group');

		$arr = ['ss' => ['klucz' => 'sd']];

		$permission = new Permission(123, $parent, 'Some', $arr);

		$repository = $this->getMockBuilder(PermissionRepository::class)
			->disableOriginalConstructor()
			->getMock();

		$repository->expects($this->at(0))
			->method('findOneBy')
			->willReturn(null);

		$repository->expects($this->at(1))
			->method('findOneBy')
			->willReturn($permission);

		$identity = $this->getMockBuilder(Identity::class)
			->getMock();
		$identity->method('getParent')
			->willReturn($parent);
		$identity->method('getUniqueId')
			->willReturn('user');

		/** @var ResourceInterface $resource */
		$resource = $this->getMockBuilder(ResourceInterface::class)
			->getMock();
		$resource->method('getResourceName')
			->willReturn('SomeName');

		$repository->method('newInstance')
			->willReturn(new Permission('asd', $identity, $resource->getResourceName(), []));

		$provider = new PermissionProvider($repository);
		$permission = $provider->get($identity, $resource->getResourceName());

		$this->assertEquals('user', $permission->getIdentityId());

	}

}