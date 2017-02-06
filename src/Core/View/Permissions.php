<?php


namespace BCode\Acl\Core\View;


use BCode\Acl\Core\Entity\Permission;


class Permissions
{
	/** @var Permission[] */
	private $permission;

	/**
	 * @param Permission[] $permissions
	 */
	public function __construct(array $permissions)
	{
		$this->permission = $permissions;
	}

	/**
	 * @param string $resource
	 * @param string $action
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function has($resource, $action = null, $value = null)
	{
		if (isset($this->permission[$resource]) == false)
		{
			return false;
		}

		/** @var Permission $permission */
		$permission = $this->permission[$resource];

		if (is_null($action))
		{
			return $permission->getActions()->isEmpty();
		}


		if ($allowed = $permission->getActions()->get($action))
		{
			if (is_array($allowed))
			{
				return in_array($value, $allowed);
			}

			return $allowed == $value;
		}

		return false;

	}
}