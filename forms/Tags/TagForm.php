<?php
/**
 * TagForm.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame
 *
 * @date    15.07.12
 */

namespace Flame\CMS\PostBundle\Forms\Tags;

class TagForm extends \Flame\Application\UI\Form
{

	/** @var \Flame\CMS\PostBundle\Model\Tags\TagManager */
	private $tagManager;

	/**
	 * @param \Flame\CMS\PostBundle\Model\Tags\TagManager $tagManager
	 */
	public function injectTagManager(\Flame\CMS\PostBundle\Model\Tags\TagManager $tagManager)
	{
		$this->tagManager = $tagManager;
	}

	/**
	 * @param array $default
	 */
	public function __construct(array $default = array())
	{
		parent::__construct();
		$this->configure();

		$this->setRenderer(new \Kdyby\BootstrapFormRenderer\BootstrapRenderer);

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
	 * @param TagForm $form
	 */
	public function formSubmitted(TagForm $form)
	{
		try {
			$this->tagManager->update($form->getValues());
			$form->presenter->flashMessage('Tag management was successful', 'success');
		}catch (\Nette\InvalidArgumentException $ex){
			$form->addError($ex->getMessage());
		}
	}

	private function configure()
	{
		$this->addText('name', 'Name:')
			->addRule(self::FILLED)
			->addRule(self::MAX_LENGTH, null, 100);

		$this->addText('slug', 'Slug:')
			->addRule(self::MAX_LENGTH, null, 100);
	}

}
