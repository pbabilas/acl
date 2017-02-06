<?php


namespace BCode\Acl\Test\Mock;


use BCode\Acl\Core\Entity\Interfaces\ResourceInterface;

class ResourceItem implements ResourceInterface
{
	/** @var bool  */
	protected $requireRoot;
	/** @var string */
	private $rule;
	/** @var string */
	private $name;
	/** @var array  */
	private $permission;

	public function __construct($ruleSymbol, $name, array $permission = [], $root = false)
	{
		$this->rule = $ruleSymbol;
		$this->name = $name;
		$this->permission = $permission;
		$this->requireRoot = $root;
	}

	/**
	 * @return string
	 */
	public function getRuleSymbol()
	{
		return $this->rule;
	}

	/**
	 * @return array
	 */
	public function getPermission()
	{
		return $this->permission;
	}

	/**
	 * @return string
	 */
	public function getResourceName()
	{
		return $this->name;
	}

	/**
	 * Resource may be allowed only for root
	 *
	 * @return boolean
	 */
	public function requireRoot()
	{
		return $this->requireRoot;
	}
}