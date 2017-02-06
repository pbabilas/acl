<?php


namespace BCode\Acl\Core\Entity\Interfaces;


interface ResourceInterface
{

	/**
	 * @return string
	 */
	public function getRuleSymbol();

	/**
	 * @return array
	 */
	public function getPermission();

	/**
	 * @return string
	 */
	public function getResourceName();

	/**
	 * Resource may be allowed only for root
	 *
	 * @return boolean
	 */
	public function requireRoot();

}