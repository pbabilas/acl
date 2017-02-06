<?php


namespace BCode\Acl\Core\Entity;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use BCode\Acl\Core\Entity\Interfaces\Identity;
use BCode\Acl\Core\Entity\Interfaces\ResourceInterface;
use BCode\Acl\Core\Exception\RecordNotFoundException;
use Ramsey\Uuid\Uuid;
use BCode\Acl\Core\Entity;

class PermissionRepository extends EntityRepository
{

	const CACHE_KEY = Permission::class;
	const ALIAS = Permission::class;

	/**
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager)
	{
		$class = $entityManager->getClassMetadata(Permission::class);
		parent::__construct($entityManager, $class);
	}

	/**
	 * @param mixed $id
	 * @return null|object
	 * @throws RecordNotFoundException
	 */
	public function find($id)
	{
		$result = $this->getEntityManager()->find($this->_entityName, $id);

		if (is_null($result)) {
			throw new RecordNotFoundException("Entity does not exists");
		}

		return $result;
	}

	/**
	 * Override - use cache.
	 *
	 * @param array $criteria
	 * @param array|null $orderBy
	 * @return mixed
	 */
	public function findOneBy(array $criteria, array $orderBy = null)
	{
		$result = $this->findBy($criteria, $orderBy, 1);

		return $result ? reset($result) : $result;
	}

	/**
	 * @param array $criteria
	 * @param array $orderBy
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
	{
		$queryBuilder = $this->createQueryBuilder(self::ALIAS);
		foreach($criteria as $field => $value) {
			$queryBuilder->andWhere(self::ALIAS . ".{$field} = :{$field}")->setParameter($field, $value);
		}
		if (is_array($orderBy)) {
			foreach ($orderBy as $field => $dir) {
				$queryBuilder->addOrderBy($field, $dir);
			}
		}
		if ($limit)
		{
			$queryBuilder->setMaxResults($limit);
		}

		$query = $queryBuilder->getQuery();

		$query->useResultCache(true, 3600, self::CACHE_KEY);

		$result = $query->getResult();

		return $result;
	}

	/**
	 * @param Permission $entity
	 */
	public function delete(Permission $entity)
	{
		$this->_em->remove($entity);
		$this->_em->flush();
	}

	/**
	 * @param Identity $identity
	 * @param string|ResourceInterface $resource
	 * @param array $actions
	 *
	 * @return Permission
	 * @throws \Exception
	 */
	public function newInstance(Identity $identity, $resource, array $actions = [])
	{
		if (is_string($resource))
		{
			$resourceName = $resource;
		}
		elseif($resource instanceof ResourceInterface)
		{
			$resourceName = $resource->getResourceName();
		}
		else
		{
			throw new \Exception('Arg 2 should be string or ResourceInterface.');
		}

		return new Permission(Uuid::uuid4(), $identity, $resourceName, $actions);
	}

	/**
	 * @param Permission $permission
	 */
	public function save(Permission $permission)
	{
		$this->_em->persist($permission);
		$this->_em->flush();
	}

}