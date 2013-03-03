<?php
/**
 * CategoryForm.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame
 *
 * @date    15.07.12
 */

namespace Flame\CMS\PostBundle\Forms\Categories;

class CategoryForm extends \Flame\CMS\PostBundle\Application\UI\Form
{

	/** @var \Flame\CMS\PostBundle\Model\Categories\CategoryManager */
	private $categoryManager;

	/**
	 * @param \Flame\CMS\PostBundle\Model\Categories\CategoryManager $categoryManager
	 */
	public function injectCategoryManager(\Flame\CMS\PostBundle\Model\Categories\CategoryManager $categoryManager)
	{
		$this->categoryManager = $categoryManager;
	}

	/**
	 * @param array $default
	 */
	public function __construct(array $default = array())
	{
		parent::__construct();

		$this->configure();

		if(count($default)){
			$this->setDefaults($default);
			$this->addSubmit('send', 'Edit')
				->setAttribute('class', 'btn-primary');
		}else{
			$this->addSubmit('send', 'Add')
				->setAttribute('class', 'btn-primary');
		}
		$this->onSuccess[] = $this->formSubmitted;
	}

	/**
	 * @param array $categories
	 */
	public function setCategories(array $categories)
	{
		$this['parent']->setItems($this->prepareForFormItem($categories));
	}

	/**
	 * @param CategoryForm $form
	 */
	public function formSubmitted(CategoryForm $form)
	{
		try{
			$this->categoryManager->update($form->getValues());
			$form->presenter->flashMessage('Category management was successful', 'success');
		}catch (\Nette\InvalidArgumentException $ex){
			$form->addError($ex->getMessage());
		}
	}

	private function configure()
	{
		$this->addText('name', 'Name:', 50)
			->addRule(self::FILLED)
			->addRule(self::MAX_LENGTH, null, 100);

		$this->addText('slug', 'Slug:', 50)
			->addRule(self::MAX_LENGTH, null, 100);

		$this->addSelect('parent', 'In category:')
			->setPrompt('-- No parent category --');

		$this->addTextArea('description', 'Description')
			->addRule(self::MAX_LENGTH, null, 250);
	}

}
