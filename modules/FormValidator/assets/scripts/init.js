rs.modules.FormValidator =
{
	config:
	{
		frontend:
		{
			init: true,
			selector: 'form.rs-js-validate-form [required]',
			className:
			{
				fieldNote: 'rs-field-note',
				isError: 'rs-is-error'
			}
		},
		backend:
		{
			init: true,
			selector: 'form.rs-admin-js-validate-form [required]',
			className:
			{
				fieldNote: 'rs-admin-field-note',
				isError: 'rs-admin-is-error'
			}
		}
	}
};
