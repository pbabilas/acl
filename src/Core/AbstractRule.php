<?php


namespace BCode\Acl\Core;


use BCode\Acl\Core\Entity\Interfaces\Identity;
use BCode\Acl\Core\Entity\Interfaces\ResourceInterface;
use BCode\Acl\Core\Entity\Permission;

abstract class AbstractRule
{
	/** @var Permission */
	protected $permission;
	/** @var  ResourceInterface */
	protected $resource;
	/** @var AbstractAction */
	protected $action;

	/**
	 * @return string
	 */
	abstract public function getSymbol();

	/**
	 * @param Identity $identity
	 * @return bool
	 */
	abstract protected function doExecute(Identity $identity);

	/**
	 * @param Identity $identity
	 * @param AbstractAction $action
	 * @return bool
	 */
	public function execute(Identity $identity, AbstractAction $action)
	{
		$isIdentityChecked = $this->permission && ($this->permission->getIdentityId() != $identity->getUniqueId());

		if ($isIdentityChecked)
		{
			return false;
		}

		$action->setIdentity($identity);
		if ($this->permission)
		{
			$action->setPermission($this->permission);
		}

		$this->action = $action;

		return $this->doExecute($identity);
	}

	/**
	 * @param ResourceInterface $resource
	 */
	public function setResource(ResourceInterface $resource)
	{
		$this->resource = $resource;
	}

	/**
	 * @param Permission $permission
	 */
	public function setPermission(Permission $permission)
	{
		$this->permission = $permission;
	}

	/**
	 * [ condition, entity ] or empty
	 *
	 * @return array
	 */
	public function getQueryConditions()
	{
		return [

		];
	}

	/**
	 * Translated rule name to presentation
	 *
	 * @return string
	 */
	abstract public function getName();

	/**
	 * Rule may have global settings
	 *
	 * @return FormInterface|null
	 */
	public function getForm()
	{
		return null;
	}
}