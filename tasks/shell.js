module.exports = grunt =>
{
	'use strict';

	const config =
	{
		phpcpdRoot:
		{
			command: 'vendor/bin/phpcpd console.php index.php install.php'
		},
		phpcpdBase:
		{
			command: 'vendor/bin/phpcpd includes',
			options:
			{
				failOnError: false
			}
		},
		phpcpdModules:
		{
			command: 'vendor/bin/phpcpd modules',
			options:
			{
				failOnError: false
			}
		},
		phpstanRoot:
		{
			command: 'vendor/bin/phpstan analyse console.php index.php install.php --configuration=phpstan.neon --level 4 --no-progress'
		},
		phpstanBase:
		{
			command: 'vendor/bin/phpstan analyse includes --configuration=phpstan.neon --level 0 --no-progress'
		},
		phpstanModules:
		{
			command: 'vendor/bin/phpstan analyse modules --configuration=phpstan.neon --level 1 --no-progress'
		},
		phpmdRoot:
		{
			command: 'vendor/bin/phpmd console.php,index.php,install.php text unusedcode'
		},
		phpmdBase:
		{
			command: 'vendor/bin/phpmd includes text unusedcode',
			options:
			{
				failOnError: false
			}
		},
		phpmdModules:
		{
			command: 'vendor/bin/phpmd modules text unusedcode'
		},
		phpunit:
		{
			command: 'vendor/bin/phpunit ' + grunt.option.flags()
		},
		phpunitParallel:
		{
			command: 'vendor/bin/paratest --processes=10 ' + grunt.option.flags()
		},
		phpServer:
		{
			command: 'php -S 127.0.0.1:8000'
		},
		openBrowser:
		{
			command: 'opn http://localhost:8000'
		},
		removeBuild:
		{
			command: 'rm -rf build'
		},
		options:
		{
			stdout: true,
			failOnError: true
		}
	};

	return config;
};