rs.modules.Analytics =
{
	init: rs.registry.loggedIn !== rs.registry.token,
	dependency: typeof window.ga === 'function',
	selector: 'a.rs-js-track-click, button.rs-js-track-click',
	options:
	{
		id: 'UA-00000000-0',
		cookieDomain: 'auto',
		anonymizeIp: true
	}
};
