<?php


namespace BCode\Acl\Core\Service;


use BCode\Acl\Core\Entity\Interfaces\Identity;
use BCode\Acl\Core\Entity\Interfaces\ResourceInterface;
use BCode\Acl\Core\Entity\PermissionRepository;
use BCode\Acl\Core\Entity;

class PermissionProvider
{
	/** @var PermissionRepository  */
	private $repository;

	/**
	 * @param PermissionRepository $repository
	 */
	public function __construct(PermissionRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * @param Identity $identity
	 * @param string $resource
	 * @return Entity\Permission|null
	 */
	public function get(Identity $identity, $resource)
	{
		/** @var Entity\Permission $permission */
		$permission = $this->repository->findOneBy([
			'resource'   => $resource,
			'identityId' => $identity->getUniqueId()
		]);

		if ($parent = $identity->getParent())
		{
			/** @var Entity\Permission $parentPermission */
			$parentPermission = $this->repository->findOneBy([
				'resource'   => $resource,
				'identityId' => $parent->getUniqueId()
			]);

			if ($parentPermission)
			{
				if ($permission)
				{
					$permission->getActions()->merge($parentPermission->getActions());
				}
				else
				{
					$permission = $this->repository->newInstance(
						$identity, $resource, $parentPermission->getActions()->toArray()
					);
				}
			}
		}

		return $permission;
	}
}