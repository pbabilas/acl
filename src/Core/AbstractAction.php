<?php


namespace BCode\Acl\Core;


use BCode\Acl\Core\Entity\Interfaces\Identity;
use BCode\Acl\Core\Entity\Interfaces\ResourceInterface;
use BCode\Acl\Core\Entity\Permission;

abstract class AbstractAction
{
	/** @var Permission */
	protected $permission;
	/** @var  Identity */
	protected $identity;


	/**
	 * @return string
	 */
	abstract public function getName();

	/**
	 * @return FormInterface|null
	 */
	public function getForm()
	{
		return null;
	}

	/**
	 * Symbol of Rule to connect
	 * @return string
	 */
	abstract public function getFor();

	/**
	 * @param ResourceInterface $resource
	 *
	 * @return bool
	 */
	abstract public function run(ResourceInterface $resource);

	/**
	 * @param Permission $permission
	 */
	public function setPermission(Permission $permission)
	{
		$this->permission = $permission;
	}

	/**
	 * @param string $ruleSymbol
	 * @return bool
	 */
	public function belongTo($ruleSymbol)
	{
		return $ruleSymbol == $this->getFor();
	}

	/**
	 * @param Identity $identity
	 */
	public function setIdentity(Identity $identity)
	{
		$this->identity = $identity;
	}

}