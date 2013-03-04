<?php
/**
 * PostManager.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date    02.03.13
 */

namespace Flame\CMS\PostBundle\Model;

class PostManager extends \Flame\Model\Manager
{

	/** @var PostFacade */
	private $postFacade;

	/**
	 * @param PostFacade $postFacade
	 */
	public function injectPostFacade(PostFacade $postFacade)
	{
		$this->postFacade = $postFacade;
	}

	/** @var \Flame\CMS\UserBundle\Security\User */
	private $user;

	/**
	 * @param \Flame\CMS\UserBundle\Security\User $user
	 */
	public function injectUser(\Flame\CMS\UserBundle\Security\User $user)
	{
		$this->user = $user;
	}

	/**
	 * @param $data
	 * @return Post
	 * @throws \Nette\InvalidArgumentException
	 */
	public function update($data)
	{

		$values = $this->validateInput($data,
			array('name', 'slug', 'tags', 'category', 'description', 'keywords', 'content', 'publish', 'comment'));

		if($id = $this->getId($data)){
			if($post = $this->postFacade->getOne($id)){
				return $this->edit($post, $values);
			}else{
				throw new \Nette\InvalidArgumentException('Post with ID "' . $id . '" does not exist');
			}
		}else{
			return $this->create($data);
		}
	}

	/**
	 * @param $id
	 * @return bool|mixed
	 * @throws \Nette\InvalidArgumentException
	 */
	public function delete($id)
	{
		if($post = $this->postFacade->getOne($id)){
			$this->postFacade->delete($post);
			return true;
		}else{
			throw new \Nette\InvalidArgumentException('Post with ID "' . $id . '" does not exist');
		}
	}

	/**
	 * @param $id
	 * @return bool
	 * @throws \Nette\InvalidArgumentException
	 */
	public function changePublicState($id)
	{
		if($post = $this->postFacade->getOne($id)){
			$this->postFacade->changePublicState($post);
			return true;
		}else{
			throw new \Nette\InvalidArgumentException('Post with ID "' . $id . '" does not exist');
		}
	}

	/**
	 * @param Post $post
	 * @param $values
	 * @return Post
	 */
	protected function edit(Post $post, $values)
	{
		$post->setName($values['name'])
			->setSlug($values['slug'])
			->setDescription($values['description'])
			->setKeywords($values['keywords'])
			->setContent($values['content'])
			->setCategory($values['category'])
			->setPublish($values['publish'])
			->setComment($values['comment'])
			->setTags((array) $values['tags']);

		$this->postFacade->save($post);
		return $post;
	}

	/**
	 * @param $values
	 * @return Post
	 */
	protected function create($values)
	{

		$post = new \Flame\CMS\PostBundle\Model\Post(
			$this->user->getModel(),
			$values['name'],
			$values['slug'],
			$values['content'],
			$values['category']
		);

		$post->setComment($values['comment'])
			->setPublish($values['publish'])
			->setKeywords($values['keywords'])
			->setDescription($values['description'])
			->setTags((array) $values['tags']);

		$this->postFacade->save($post);

		return $post;

	}
}
