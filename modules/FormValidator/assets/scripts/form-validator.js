rs.modules.FormValidator.validate = config =>
{
	if (config.init)
	{
		document.querySelectorAll(config.selector).forEach(fieldValue =>
		{
			fieldValue.classList.add(config.className.fieldNote);
			fieldValue.addEventListener('input', () =>
			{
				fieldValue.validity.valid ? fieldValue.classList.remove(config.className.isError) : fieldValue.classList.add(config.className.isError);
			});
			fieldValue.addEventListener('invalid', () =>
			{
				fieldValue.classList.add(config.className.isError);
			});
		});
	}
};

/* run as needed */

rs.modules.FormValidator.validate(rs.modules.FormValidator.config.frontend);
if (rs.registry.loggedIn === rs.registry.token)
{
	rs.modules.FormValidator.validate(rs.modules.FormValidator.config.backend);
}