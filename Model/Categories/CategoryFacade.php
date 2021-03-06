<?php
/**
 * CategoryFacade.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame
 *
 * @date    14.07.12
 */

namespace Flame\CMS\PostBundle\Model\Categories;

class CategoryFacade extends \Flame\Doctrine\Model\Facade
{

	/**
	 * @var string
	 */
	protected $repositoryName = '\Flame\CMS\PostBundle\Model\Categories\Category';

	/**
	 * @param $name
	 * @return object
	 */
	public function getOneByName($name)
	{
		return $this->repository->findOneBy(array('name' => (string) $name));
	}

	/**
	 * @return array
	 */
	public function getLastCategories()
	{
		return $this->repository->findBy(array(), array('id' => 'DESC'));
	}

}
