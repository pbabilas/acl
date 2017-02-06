<?php

namespace BCode\Acl\Core\Entity;

use BCode\Acl\Core\Entity\Interfaces\Identity;
use BCode\Acl\Core\Entity\Interfaces\ResourceInterface;

/**
 * @Entity()
 * @Table(name="acl_permission", uniqueConstraints={@UniqueConstraint(name="resource_action_for_identity", columns={"identity_id", "resource_name"})})
 */
class Permission
{

	/**
	 * @var integer
	 *
	 * @Id
	 * @Column(type="guid")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @Column(type="string", name="identity_id")
	 */
	private $identityId;

	/**
	 * @var string
	 *
	 * @Column(type="string", name="resource_name")
	 */
	private $resource;

	/**
	 * @var array
	 *
	 * @Column(type="json_array")
	 */
	private $actions;

	/**
	 * @var Actions
	 */
	private $actionsObject;

	/**
	 * @param string $uuid
	 * @param Identity $identity
	 * @param string $resourceName
	 * @param array $actions
	 */
	public function __construct($uuid, Identity $identity, $resourceName, array $actions = [])
	{
		$this->id = $uuid;
		$this->identityId = $identity->getUniqueId();
		$this->resource = $resourceName;
		$this->actions = $actions;
	}

	/**
	 * @return string
	 */
	public function getResourceName()
	{
		return $this->resource;
	}

	/**
	 * @return string
	 */
	public function getIdentityId()
	{
		return $this->identityId;
	}

	/**
	 * @return Actions
	 */
	public function getActions()
	{
		if (is_null($this->actionsObject))
		{
			$this->actionsObject = new Actions($this->actions);
		}

		return $this->actionsObject;
	}
}