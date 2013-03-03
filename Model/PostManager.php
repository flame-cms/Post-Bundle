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

	public function update($data)
	{

	}

	protected function edit(Post $category, $values)
	{

	}

	protected function create($values)
	{

	}
}
