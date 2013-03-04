<?php

namespace Flame\CMS\AdminModule;

/**
* PostPresenter
*/
class PostPresenter extends AdminPresenter
{

	/** @var int */
	private $itemsPerPage = 25;

	/**
	 * @var \Flame\CMS\PostBundle\Model\Post
	 */
    private $post;

	/**
	 * @autowire
	 * @var \Flame\CMS\PostBundle\Model\PostFacade
	 */
	protected $postFacade;

	/**
	 * @autowire
	 * @var \Flame\CMS\PostBundle\Model\Categories\CategoryFacade
	 */
	protected $categoryFacade;

	/**
	 * @autowire
	 * @var \Flame\CMS\PostBundle\Model\Tags\TagFacade
	 */
	protected $tagFacade;

	public function renderDefault()
	{
		$paginator = $this['paginator']->getPaginator();
		$posts = $this->postFacade->getLastPostsPaginator($paginator->offset, $this->itemsPerPage);
		$paginator->itemCount = count($posts);
		$this->template->posts = $posts;
	}

	/**
	 * @param \Flame\Addons\VisualPaginator\IPaginatorFactory $paginatorFactory
	 * @return \Flame\Addons\VisualPaginator\Paginator
	 */
	protected function createComponentPaginator(\Flame\Addons\VisualPaginator\IPaginatorFactory $paginatorFactory)
	{
		$visualPaginator = $paginatorFactory->create();
		$visualPaginator->paginator->setItemsPerPage($this->itemsPerPage);
		return $visualPaginator;
	}

	/**
	 * @param $id
	 */
	public function handleDelete($id)
	{
		if(!$this->getUser()->isAllowed('Admin:Post', 'delete')){
			$this->flashMessage('Access denied');
		}else{

			$post = $this->postFacade->getOne($id);

			if($post){
				$this->postFacade->delete($post);
			}else{
				$this->flashMessage('Required post to delete does not exist!');
			}
		}

		if(!$this->isAjax()){
			$this->redirect('this');
		}else{
			$this->invalidateControl('posts');
		}
	}

	/**
	 * @param $id
	 */
	public function handleMarkPublish($id)
	{
		if(!$this->getUser()->isAllowed('Admin:Post', 'publish')){
			$this->flashMessage('Access denied');
		}else{

			$post = $this->postFacade->getOne($id);

			if($post and (int)$post->getPublish() == 1){
				$post->setPublish(false);
                $this->postFacade->save($post);
			}else{
                $post->setPublish(true);
                $this->postFacade->save($post);
			}
		}

		if(!$this->isAjax()){
			$this->redirect('this');
		}else{
			$this->invalidateControl('posts');
		}
	}

	/**
	 * @param null $id
	 */
	public function actionUpdate($id = null)
	{
        $this->post = $this->postFacade->getOne($id);
		$this->template->post = $this->post;

	}

	/**
	 * @param \Flame\CMS\PostBundle\Forms\IPostFormFactory $postFormFactory
	 * @return \Flame\CMS\PostBundle\Forms\PostForm
	 */
	protected function createComponentPostForm(\Flame\CMS\PostBundle\Forms\IPostFormFactory $postFormFactory)
	{

		$default = array();
		if($this->post instanceof \Flame\CMS\PostBundle\Model\Post)
			$default = $this->post->toArray();

		$form = $postFormFactory->create($default);
		$form->setCategories($this->categoryFacade->getLastCategories());
		$form->setTags($this->tagFacade->getLastTags());

		if($this->post){
			$form->onSuccess[] = $this->lazyLink('this');
		}else{
			$form->onSuccess[] = $this->lazyLink('default');
		}

        return $form;
	}

}
