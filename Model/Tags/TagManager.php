<?php
/**
 * TagManager.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date    01.03.13
 */

namespace Flame\CMS\PostBundle\Model\Tags;

class TagManager extends \Flame\Model\Manager
{

	/** @var TagFacade */
	private $tagFacade;

	/**
	 * @param TagFacade $tagFacade
	 */
	public function injectTagFacade(TagFacade $tagFacade)
	{
		$this->tagFacade = $tagFacade;
	}

	/**
	 * @param $data
	 * @return Tag
	 * @throws \Nette\InvalidArgumentException
	 */
	public function update($data)
	{
		$values = $this->validateInput($data, array('name', 'slug'));

		if($id = $this->getId($data)){
			if($tag = $this->tagFacade->getOne($id)){
				return $this->edit($tag, $values);
			}else{
				throw new \Nette\InvalidArgumentException('Tag with ID "' . $id . '" does not exist');
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
		if($tag = $this->tagFacade->getOne($id)){
			$this->tagFacade->delete($tag);
			return true;
		}else{
			throw new \Nette\InvalidArgumentException('Tag with ID "' . $id . '" does not exist');
		}
	}

	/**
	 * @param $values
	 * @return Tag
	 */
	protected function create($values)
	{
		$tag = new Tag($values->name, $values->slug);
		$this->tagFacade->save($tag);
		return $tag;
	}

	/**
	 * @param Tag $tag
	 * @param $values
	 * @return Tag
	 */
	protected function edit(Tag $tag, $values)
	{
		$tag->setName($values->name)
			->setSlug($values->slug);
		$this->tagFacade->save($tag);
		return $tag;
	}

}
