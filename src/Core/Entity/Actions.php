<?php


namespace BCode\Acl\Core\Entity;


class Actions implements \ArrayAccess
{
	/** @var array  */
	private $actions;

	/**
	 * @param array $actions
	 */
	public function __construct(array $actions)
	{
		$this->actions = $actions;
	}

	/**
	 * @param $offset
	 * @return mixed|null
	 */
	public function get($offset)
	{
		if ($this->has($offset))
		{
			return $this->offsetGet($offset);
		}

		return null;
	}

	/**
	 * @param string $offset
	 * @return bool
	 */
	public function has($offset)
	{
		return isset($this->actions[$offset]);
	}

	/**
	 * @param Actions $actions
	 */
	public function merge(Actions $actions)
	{
		$this->actions = array_merge_recursive($this->actions, $actions->toArray());
	}

	/**
	 * @param string $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->actions[$offset];
	}

	/**
	 * Whether a offset exists
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 * An offset to check for.
	 * </p>
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 * @since 5.0.0
	 */
	public function offsetExists($offset)
	{
		return $this->has($offset);
	}

	/**
	 * Offset to set
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset <p>
	 * The offset to assign the value to.
	 * </p>
	 * @param mixed $value <p>
	 * The value to set.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetSet($offset, $value)
	{
		$this->actions[$offset] = $value;
	}

	/**
	 * Offset to unset
	 *
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 * The offset to unset.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetUnset($offset)
	{
		unset($this->actions[$offset]);
	}

	/**
	 * @return bool
	 */
	public function isEmpty()
	{
		return empty($this->actions);
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->actions;
	}
}