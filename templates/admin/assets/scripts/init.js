rs.templates.admin =
{
	alias:
	{
		init: true,
		dependency: typeof window.getSlug === 'function',
		selector: 'form input.rs-admin-js-alias-input, form input.rs-admin-js-alias-output',
		options:
		{
			element:
			{
				related: 'input.rs-admin-js-alias-output'
			}
		}
	}
};
