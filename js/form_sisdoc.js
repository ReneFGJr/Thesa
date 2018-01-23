function confirmar() {
	if (!confirm('Confirma?'))
		return (false);
	return (true);
}

function fullwin(url) {
	NewWindow = window.open(url, 'padle', 'scrollbars=no,resizable=yes,width=1280,height=800,top=0,left=0');
	NewWindow.focus();
	void (0);
}

function newxy(url, xx, yy) {
	NewWindow = window.open(url, 'newwin', 'scrollbars=yes,resizable=no,width=' + xx + ',height=' + yy + ',top=10,left=10');
	NewWindow.focus();
	void (0);
}

function newwin(url) {
	NewWindow = window.open(url, 'newwin', 'scrollbars=yes,resizable=yes, width=800,height=450,top=10,left=10');
	NewWindow.focus();
	void (0);
}

function winclose() {
	close();
}

function wclose() {
	window.opener.location.reload();
	close();
}
