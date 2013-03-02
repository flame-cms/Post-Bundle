<?php
/**
 * IPostControlFactory.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package Flame\CMS
 *
 * @date    15.12.12
 */

namespace Flame\CMS\PostBundle\Components;

interface IPostControlFactory
{

	/**
	 * @param array $post
	 * @return PostControl
	 */
	public function create(array $post);

}
