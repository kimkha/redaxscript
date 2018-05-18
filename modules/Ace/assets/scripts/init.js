rs.modules.Ace =
{
	init: rs.registry.adminParameter === 'new' || rs.registry.adminParameter === 'edit' && rs.registry.tableParameter === 'articles' || rs.registry.tableParameter === 'extras' || rs.registry.tableParameter === 'comments',
	dependency: typeof window.ace === 'object',
	selector: 'form textarea.rs-admin-js-editor-textarea',
	options:
	{
		ace:
		{
			mode: 'ace/mode/html',
			maxLines: Infinity
		}
	}
};
