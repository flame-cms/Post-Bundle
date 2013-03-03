<?php
/**
 * TagFacade.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame
 *
 * @date    14.07.12
 */

namespace Flame\CMS\PostBundle\Model\Tags;

class TagFacade extends \Flame\Doctrine\Model\Facade
{

	/**
	 * @var string
	 */
	protected $repositoryName = '\Flame\CMS\PostBundle\Model\Tags\Tag';

	/**
	 * @param $name
	 * @return object
	 */
	public function getOneByName($name)
	{
		return $this->repository->findOneBy(array('name' => (string) $name));
	}

	/**
	 * @param null $limit
	 * @return array
	 */
	public function getLastTags($limit = null)
	{
		return $this->repository->findBy(array(), array('id' => 'DESC'), $limit);
	}
}
