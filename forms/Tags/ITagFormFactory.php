<?php
/**
 * ITagFormFactory.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date    01.03.13
 */

namespace Flame\CMS\PostBundle\Forms\Tags;

interface ITagFormFactory
{

	/**
	 * @param array $default
	 * @return TagForm
	 */
	public function create(array $default = array());
}
