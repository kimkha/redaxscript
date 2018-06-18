document.querySelectorAll('form.rs-js-validate-form input').forEach(fieldValue =>
{
	fieldValue.classList.add('rs-field-note');
	fieldValue.addEventListener('input', () =>
	{
		fieldValue.validity.valid ? fieldValue.classList.remove('rs-is-error') : fieldValue.classList.add('rs-is-error');
	});
	fieldValue.addEventListener('invalid', () =>
	{
		fieldValue.classList.add('rs-is-error');
	});
});