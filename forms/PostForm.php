<?php
/**
 * PostForm.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame
 *
 * @date    14.07.12
 */

namespace Flame\CMS\PostBundle\Forms;

class PostForm extends \Flame\CMS\PostBundle\Application\UI\Form
{

	/** @var \Flame\CMS\PostBundle\Model\PostManager */
	private $postManager;

	/**
	 * @param \Flame\CMS\PostBundle\Model\PostManager $postManager
	 */
	public function injectPostManager(\Flame\CMS\PostBundle\Model\PostManager $postManager)
	{
		$this->postManager = $postManager;
	}

	public function __construct(array $default = array())
	{
		parent::__construct();

		$this->configure();

		if(count($default)){
			$this->setDefaults($this->prepareDefaultValues($default));
			$this->addSubmit('send', 'Edit post')
				->setAttribute('class', 'btn-primary');
		}else{
			$this->setDefaults(array('publish' => true));
			$this->addSubmit('send', 'Create post')
				->setAttribute('class', 'btn-primary');
		}
		$this->onSuccess[] = $this->formSubmitted;
	}

	/**
	 * @param PostForm $form
	 */
	public function formSubmitted(PostForm $form)
	{
		try {
			$this->postManager->update($form->getValues());
			$form->presenter->flashMessage('Post management was successful', 'success');
		}catch (\Nette\InvalidArgumentException $ex){
			$form->addError($ex->getMessage());
		}
	}

	/**
	 * @param array $categories
	 */
	public function setCategories(array $categories)
	{
		$this['category']->setItems($this->prepareForFormItem($categories));
	}

	/**
	 * @param array $tags
	 */
	public function setTags(array $tags)
	{
		$this['tags']->setItems($this->prepareForFormItem($tags));
	}

	private function configure()
	{
		$this->addGroup('Main');

		$this->addText('name', 'Name:', 80)
			->addRule(self::FILLED)
			->addRule(self::MAX_LENGTH, null, 100);

		$this->addTextArea('content', 'Content:', 105, 30)
			->addRule(self::FILLED)
			->setAttribute('class', 'mceEditor');

		$this->addGroup('Meta options');

		$this->addText('slug', 'Name in URL:', 80)
			->addRule(self::MAX_LENGTH, null, 100);

		$this->addText('keywords', 'META Keywords:', 80)
			->addRule(self::MAX_LENGTH, null, 500);

		$this->addTextArea('description', 'Descriptions:', 90, 5)
			->addRule(self::MAX_LENGTH, null, 250);

		$this->addGroup('Category');

		$this->addSelect('category', 'Category:')
			->setPrompt('-- Select one --')
			->setOption('description', 'Select category or create new below.');

		$this->addText('categoryNew', 'Create new category', 80)
			->setAttribute('placeholder', 'Write name of new category');

		$this->addGroup('Tags');


		$this->addMultiSelect('tags', 'Tags:')
			->setAttribute('class', 'tags-multiSelect');

		$this->addText('tagsNew', 'Create new tags', 100)
			->setOption('description', 'Tags split with commas')
			->setAttribute('placeholder', 'Write names of new tags');

		$this->addGroup('Are you sure?');

		$this->addCheckbox('publish', 'Make this post published?');
		$this->addCheckbox('comment', 'Allow comments?');
	}

	/**
	 * @param array $defaults
	 * @return array
	 */
	private function prepareDefaultValues(array $defaults)
	{
		if(isset($defaults['tags'])){
			$tags = $defaults['tags']->toArray();
			if(is_array($tags)) $defaults['tags'] = array_map(function($tag){ return $tag->id; }, $tags);
		}
		return $defaults;
	}
}
