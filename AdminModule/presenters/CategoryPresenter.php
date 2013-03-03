<?php
/**
 * CategoryPresenter.php
 *
 * @author  Jiří Šifalda <jsifalda.jiri@gmail.com>
 * @package Flame
 *
 * @date    15.07.12
 */

namespace Flame\CMS\AdminModule;

class CategoryPresenter extends AdminPresenter
{

	/**
	 * @var \Flame\CMS\PostBundle\Model\Categories\Category
	 */
	private $category;

	/**
	 * @autowire
	 * @var \Flame\CMS\PostBundle\Model\Categories\CategoryFacade
	 */
	protected $categoryFacade;

	/**
	 * @autowire
	 * @var \Flame\CMS\PostBundle\Forms\Categories\ICategoryFormFactory
	 */
	protected $categoryFormFactory;

	/**
	 * @autowire
	 * @var \Flame\CMS\PostBundle\Model\Categories\CategoryManager
	 */
	protected $categoryManager;

	public function renderDefault()
	{
		$this->template->categories = $this->categoryFacade->getLastCategories();
	}

	/**
	 * @param $id
	 */
	public function actionUpdate($id = null)
	{
		$this->category = $this->categoryFacade->getOne($id);
		$this->template->category = $this->category;
	}

	/**
	 * @return \Flame\CMS\PostBundle\Forms\Categories\CategoryForm
	 */
	protected function createComponentCategoryForm()
	{
		$default = array();
		if($this->category instanceof \Flame\CMS\PostBundle\Model\Categories\Category)
			$default = $this->category->toArray();

		$form = $this->categoryFormFactory->create($default);
		$form->setCategories($this->categoryFacade->getLastCategories());

		if($this->category){
			$form->onSuccess[] = $this->lazyLink('this');
		}else{
			$form->onSuccess[] = $this->lazyLink('default');
		}

		return $form;
	}

	/**
	 * @param $id
	 */
	public function handleDelete($id)
	{
		if(!$this->getUser()->isAllowed('Admin:Category', 'delete')){
			$this->flashMessage('Access denied');
		}else{
			try {
				$this->categoryManager->delete($id);
			}catch (\Nette\InvalidArgumentException $ex){
				$this->presenter->flashMessage($ex->getMessage());
			}
		}

		$this->redirect('this');
	}
}
