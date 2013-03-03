<?php
/**
 * CategoryManager.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date    02.03.13
 */

namespace Flame\CMS\PostBundle\Model\Categories;

class CategoryManager extends \Flame\Model\Manager
{

	/** @var CategoryFacade */
	private $categoryFacade;

	/**
	 * @param CategoryFacade $categoryFacade
	 */
	public function injectCategoryFacade(CategoryFacade $categoryFacade)
	{
		$this->categoryFacade = $categoryFacade;
	}

	/**
	 * @param $data
	 * @return Category
	 * @throws \Nette\InvalidArgumentException
	 */
	public function update($data)
	{

		$values = $this->validateInput($data, array('name', 'slug', 'description', 'parent'));

		if($id = $this->getId($data)){
			if($category = $this->categoryFacade->getOne($id)){
				return $this->edit($category, $values);
			}else{
				throw new \Nette\InvalidArgumentException('Category with ID "' . $id . '" does not exist');
			}

		}else{
			return $this->create($values);
		}

	}

	/**
	 * @param $id
	 * @return bool
	 * @throws \Nette\InvalidArgumentException
	 */
	public function delete($id)
	{
		if($category = $this->categoryFacade->getOne($id)){
			$this->categoryFacade->delete($category);
			return true;
		}else{
			throw new \Nette\InvalidArgumentException('Category with ID "' . $id  .'" does not exist.');
		}
	}

	/**
	 * @param Category $category
	 * @param $values
	 * @return Category
	 */
	protected function edit(Category $category, $values)
	{

		$parent = $this->categoryFacade->getOne($values['parent']);
		$category->setName($values['name'])
			->setSlug($values->slug)
			->setDescription($values['description'])
			->setParent($parent);

		$this->categoryFacade->save($category);
		return $category;

	}

	/**
	 * @param $values
	 * @return Category
	 * @throws \Nette\InvalidArgumentException
	 */
	protected function create($values)
	{
		if($this->categoryFacade->getOneByName($values['name'])){
			throw new \Nette\InvalidArgumentException('Category with name "' . $values['name'] . '" exist.');
		}else{
			$category = new \Flame\CMS\PostBundle\Model\Categories\Category($values['name'], $values->slug);
			$category->setDescription($values['description']);

			if($parent = $this->categoryFacade->getOne($values['parent'])){
				$category->setParent($parent);
			}

			$this->categoryFacade->save($category);
			return $category;
		}
	}

}
