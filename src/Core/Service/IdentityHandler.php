<?php


namespace BCode\Acl\Core\Service;

use BCode\Acl\Core\Entity;
use BCode\Acl\Core\Entity\Interfaces\Identity;


class IdentityHandler implements Entity\Interfaces\Identity
{

	/** @var Entity\Interfaces\Identity  */
	private $identity;

	/**
	 * @param Entity\Interfaces\Identity $identity
	 */
	public function __construct(Entity\Interfaces\Identity $identity)
	{
		$this->identity = $identity;
	}

	/**
	 * @return bool
	 */
	public function isAnonymous()
	{
		if ($this->identity->isAnonymous())
		{
			return true;
		}

		if ($parent = $this->identity->getParent())
		{
			return $parent->isAnonymous();
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function isRoot()
	{
		if ($this->identity->isRoot())
		{
			return true;
		}

		if ($parent = $this->identity->getParent())
		{
			return $parent->isRoot();
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function hasFullPermission()
	{
		if ($this->identity->hasFullPermission())
		{
			return true;
		}

		if ($parent = $this->identity->getParent())
		{
			return $parent->hasFullPermission();
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function getUniqueId()
	{
		return $this->identity->getUniqueId();
	}

	/**
	 * @return Identity|null
	 */
	public function getParent()
	{
		return $this->identity->getParent();
	}
}