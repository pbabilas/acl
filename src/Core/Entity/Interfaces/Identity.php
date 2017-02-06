<?php


namespace BCode\Acl\Core\Entity\Interfaces;


interface Identity
{

	/**
	 * @return string
	 */
	public function getUniqueId();

	/**
	 * Allows all for any resources
	 *
	 * @return boolean
	 */
	public function isRoot();

	/**
	 * @return boolean
	 */
	public function isAnonymous();

	/**
	 * @return Identity|null
	 */
	public function getParent();

	/**
	 * Allows all for resource not technical
	 *
	 * @return bool
	 */
	public function hasFullPermission();
}