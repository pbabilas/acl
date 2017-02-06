<?php


namespace BCode\Acl\Core\Query;


use BCode\Acl\Core\Entity\Interfaces\Identity;
use BCode\Acl\Core\Entity\PermissionRepository;
use BCode\Acl\Core\Service\Query\FilterProvider;

abstract class AbstractHandler
{

	/** @var FilterProvider  */
	protected $provider;
	/** @var PermissionRepository  */
	protected $repository;

	public function __construct(FilterProvider $provider)
	{
		$this->provider = $provider;
	}

	/**
	 * @param Identity $identity
	 * @param string|null $resource
	 */
	abstract public function filter(Identity $identity, $resource = null);

	/**
	 * @param PermissionRepository $repository
	 */
	public function setRepository($repository)
	{
		$this->repository = $repository;
	}
}