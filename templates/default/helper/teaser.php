<?php
namespace Redaxscript;

$teaser = Db::forTablePrefix('extras')
	->where(
	[
		'alias' => 'teaser',
		'category' => Template\Tag::getRegistry('categoryId')
	])
	->findOne()
	->text;