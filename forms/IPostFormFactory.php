<?php
/**
 * IPostFormFactory.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date    02.03.13
 */

namespace Flame\CMS\PostBundle\Forms;

interface IPostFormFactory
{

	/**
	 * @param array $default
	 * @return PostForm
	 */
	public function create(array $default = array());

}
