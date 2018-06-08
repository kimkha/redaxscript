Object.keys(rs.modules.Debugger.data).forEach(dataValue =>
{
	const data = rs.modules.Debugger.data[dataValue];

	console.log(dataValue.toUpperCase() + ' (' + dataValue.length + ')');
	console.table(data);
});
