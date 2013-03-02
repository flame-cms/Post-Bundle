<?php
/**
 * PostRepository
 *
 * @author  Jiří Šifalda
 * @package Flame
 *
 * @date    10.07.12
 */

namespace Flame\CMS\PostBundle\Model;

class PostRepository extends \Flame\Doctrine\Model\Repository
{

	/**
	 * @param $first
	 * @param $limit
	 * @return \Doctrine\ORM\Query
	 */
	public function findAllQuery($first, $limit)
	{
		$sql = 'Select p from Flame\CMS\PostBundle\Model\Post p order by p.id DESC';
		return $this->_em->createQuery($sql)->setFirstResult($first)->setMaxResults($limit);
	}

	/**
	 * @param $first
	 * @param $limit
	 * @return \Doctrine\ORM\Query
	 */
	public function findPublishedQuery($first, $limit)
	{
		$sql = 'Select p from Flame\CMS\PostBundle\Model\Post p where p.publish=1 order by p.id DESC';
		return $this->_em->createQuery($sql)->setFirstResult($first)->setMaxResults($limit);
	}

}
