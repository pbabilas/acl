<?php


namespace BCode\Acl\Core\Query;


use BCode\Acl\Core\Entity\Interfaces\Identity;
use BCode\Acl\Core\Entity\Permission;

abstract class AbstractFilter
{
	/** @var Identity */
	protected $identity;
	/** @var Permission */
	protected $permission;

	/**
	 * @param Identity $identity
	 */
	public function setIdentity(Identity $identity)
	{
		$this->identity = $identity;
	}

	/**
	 * Resource is entity class
	 *
	 * @return string
	 */
	abstract public function getResource();

	/**
	 * @return mixed
	 */
	abstract public function doGetConditions();

	/**
	 * @return mixed
	 */
	public function getConditions()
	{
		$this->beforeFilter();
		return $this->doGetConditions();
	}

	public function setPermission($permissions)
	{
		$this->permission = $permissions;
	}

	/**
	 * Use if needed.
	 *
	 * @return void
	 */
	protected function beforeFilter()
	{
	}
}