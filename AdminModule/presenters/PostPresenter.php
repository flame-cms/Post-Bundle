<?php

namespace Flame\CMS\AdminModule;

/**
* PostPresenter
*/
class PostPresenter extends AdminPresenter
{

	/**
	 * @var \Flame\CMS\PostBundle\Model\Post
	 */
    private $post;

	/**
	 * @var \Flame\Components\FileUploader\FileUploaderControlFactory $fileUploaderControlFactory
	 */
	private $fileUploaderControlFactory;

	/**
	 * @autowire
	 * @var \Flame\CMS\PostBundle\Model\PostFacade
	 */
	protected $postFacade;

	/**
	 * @autowire
	 * @var \Flame\CMS\PostBundle\Forms\IPostFormFactory
	 */
	protected $postFormFactory;

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
		$posts = $this->postFacade->getLastPostsPaginator($paginator->offset, 25);
		$paginator->itemCount = count($posts);
		$this->template->posts = $posts;
	}

	/**
	 * @return \Flame\Addons\VisualPaginator\Paginator
	 */
	protected function createComponentPaginator()
	{
		$visualPaginator = new \Flame\Addons\VisualPaginator\Paginator;
		$visualPaginator->paginator->setItemsPerPage(25);
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
	 * @return \Flame\CMS\PostBundle\Forms\PostForm
	 */
	protected function createComponentPostForm()
	{

		$default = array();
		if($this->post instanceof \Flame\CMS\PostBundle\Model\Post)
			$default = $this->post->toArray();

		$form = $this->postFormFactory->create($default);
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
