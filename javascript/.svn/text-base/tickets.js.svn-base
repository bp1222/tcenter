function expand( node ) {

	var focus = node.nextSibling;

	while( !focus.style )
		focus = focus.nextSibling;

	if( focus.style.display != 'none' && focus.style.display != "" ) {
		focus.style.display = 'none';
		node.style.backgroundImage = 'url( images/collapsed.png )';
	}
	else {
		focus.style.display = 'block';
		node.style.backgroundImage = 'url( images/expanded.png )';
	}
}
