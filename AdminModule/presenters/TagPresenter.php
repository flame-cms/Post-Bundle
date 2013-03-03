<?php
/**
 * TagPresenter.php
 *
 * @author  Jiří Šifalda <jsifalda.jiri@gmail.com>
 * @package Flame
 *
 * @date    15.07.12
 */

namespace Flame\CMS\AdminModule;

use Flame\Utils\Strings;

class TagPresenter extends AdminPresenter
{

	/** @var \Flame\CMS\PostBundle\Model\Tags\Tag */
	private $tag;

	/**
	 * @autowire
	 * @var \Flame\CMS\PostBundle\Model\Tags\TagFacade
	 */
	protected $tagFacade;

	/**
	 * @autowire
	 * @var \Flame\CMS\PostBundle\Forms\Tags\ITagFormFactory
	 */
	protected $tagFormFactory;

	/**
	 * @autowire
	 * @var \Flame\CMS\PostBundle\Model\Tags\TagManager
	 */
	protected $tagManager;

	public function renderDefault()
	{
		$this->template->tags = $this->tagFacade->getLastTags();
	}

	/**
	 * @param null $id
	 */
	public function actionUpdate($id = null)
	{
		$this->tag = $this->tagFacade->getOne($id);
		$this->template->tag = $this->tag;
	}

	/**
	 * @return \Flame\CMS\PostBundle\Forms\Tags\TagForm
	 */
	protected function createComponentTagForm()
	{
		$default = array();
		if($this->tag instanceof \Flame\CMS\PostBundle\Model\Tags\Tag)
			$default = $this->tag->toArray();

		$form = $this->tagFormFactory->create($default);

		if($this->tag){
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
		if(!$this->getUser()->isAllowed('Admin:Tag', 'delete')){
			$this->flashMessage('Access denied');
		}else{
			try {
				$this->tagManager->delete($id);
			}catch (\Nette\InvalidArgumentException $ex){
				$this->flashMessage($ex->getMessage());
			}
		}

		$this->redirect('this');
	}
}
