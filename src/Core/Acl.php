<?php

namespace BCode\Acl\Core;

use BCode\Acl\Core\Entity;
use BCode\Acl\Core\Entity\Permission;
use BCode\Acl\Core\Exception\IdentityNotSetException;
use BCode\Acl\Core\Query\AbstractHandler;
use BCode\Acl\Core\Service\ActionProvider;
use BCode\Acl\Core\Service\PermissionProvider;
use BCode\Acl\Core\Service\RuleProvider;

class Acl
{

	/** @var Entity\Interfaces\Identity */
	protected $identity;
	/** @var AbstractHandler  */
	protected $handler;
	/** @var Entity\PermissionRepository  */
	private $repository;
	/** @var RuleProvider */
	private $provider;
	/** @var ActionProvider  */
	private $actionProvider;
	/** @var PermissionProvider */
	private $permissionProvider = null;

	/**
	 * @param Entity\PermissionRepository $repository
	 * @param RuleProvider $provider
	 * @param ActionProvider $actionProvider
	 * @param AbstractHandler $handler
	 */
	public function __construct(
		Entity\PermissionRepository $repository,
		RuleProvider $provider,
		ActionProvider $actionProvider,
		AbstractHandler $handler
	)
	{
		$this->repository = $repository;
		$this->provider = $provider;
		$this->actionProvider = $actionProvider;
		$this->handler = $handler;
		$this->permissionProvider = new PermissionProvider($this->repository);
	}

	/**
	 * Identity is user/customer etc
	 * Root has every permission
	 * Anonymous has no permissions
	 *
	 * @param Entity\Interfaces\Identity $identity
	 */
	public function setIdentity(Entity\Interfaces\Identity $identity)
	{
		$this->identity = $identity;
	}

	/**
	 *
	 * if resource is defined check if identity has permission
	 *    If no deny all
	 *    If yes and resource has ~args~ check if identity has permission to arg
	 *        if no deny
	 *        if yes allow
	 *    if resource has not ~args~ allow all
	 *
	 * @param Entity\Interfaces\ResourceInterface $resource
	 * @param string $action
	 * @param Entity\Interfaces\Identity $identity
	 *
	 * @return bool
	 */
	final private function isAble(Entity\Interfaces\ResourceInterface $resource, $action, Entity\Interfaces\Identity $identity = null)
	{
		try
		{
			if(is_null($identity))
			{
				$identity = $this->getIdentity();
			}

			if ($identity->isAnonymous())
			{
				return false;
			}

			if ($identity->isRoot())
			{
				return true;
			}

			if ($resource->requireRoot())
			{
				return $identity->isRoot();
			}

			if ($identity->hasFullPermission())
			{
				return true;
			}

			$rule = $this->provider->get($resource->getRuleSymbol());
			$rule->setResource($resource);

			$permission = $this->permissionProvider->get($identity, $resource->getResourceName());
			if ($permission)
			{
				$rule->setPermission($permission);
			}

			$action = $this->actionProvider->get($action, $rule->getSymbol());

			return $rule->execute($identity, $action);
		}
		catch(\LogicException $e)
		{
			return true;
		}
	}

	/**
	 * @param Entity\Interfaces\ResourceInterface $resource
	 * @param string $action
	 * @return bool
	 */
	public function can(Entity\Interfaces\ResourceInterface $resource, $action)
	{
		if ($this->isAble($resource, $action))
		{
			return true;
		}

		return false;
	}

	/**
	 * @return Entity\Interfaces\Identity
	 *
	 * @throws IdentityNotSetException
	 */
	private function getIdentity()
	{
		if (is_null($this->identity))
		{
			throw new IdentityNotSetException();
		}

		return $this->identity;
	}

	/**
	 * @param string $resourceName
	 * @param Entity\Interfaces\Identity $identity
	 * @param array $actions
	 */
	public function setPermission($resourceName, Entity\Interfaces\Identity $identity, array $actions)
	{
		/** @var Permission $old */
		$old = $this->repository->findOneBy([
			'identityId' => $identity->getUniqueId(),
			'resource' => $resourceName
		]);
		if ($old)
		{
			$this->repository->delete($old);
		}

		$permission = $this->repository->newInstance($identity, $resourceName, $actions);
		$this->repository->save($permission);
	}

	/**
	 * Should inject where conditions to query
	 *
	 * @param string $resource
	 */
	public function filterQuery($resource = null)
	{
		$this->handler->setRepository($this->repository);
		$this->handler->filter($this->identity, $resource);
	}
}