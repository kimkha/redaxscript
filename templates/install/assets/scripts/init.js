rs.templates.install =
{
	behavior:
	{
		init: true,
		selector: 'form.rs-install-js-form',
		config:
		{
			element:
			{
				fieldType: '#db-type',
				fieldRelated: '#db-name, #db-user, #db-password',
				fieldRequired: '#db-name, #db-user',
				fieldHost: '#db-host'
			}
		}
	}
};