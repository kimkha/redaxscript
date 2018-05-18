module.exports = () =>
{
	'use strict';

	const config =
	{
		templates:
		{
			src:
			[
				'templates/**/assets/**/*.css'
			]
		},
		modules:
		{
			src:
			[
				'modules/**/assets/**/*.css'
			]
		}
	};

	return config;
};