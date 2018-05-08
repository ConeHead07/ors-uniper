function O(ElId) {
	return document.getElementById(ElId);
}

function ChgD(ElId) {
	// var o = document.getElementById(ElId);
	var o = O(ElId);
	D(ElId, o.style.display ? '' : 'none');
	// o.style.display = (o.style.display) ? '' : 'none';
}
function D(ElId) { // [, Option SetVal]
	// var o = document.getElementById(ElId);
	var o = O(ElId);
	if (arguments.length > 1) {
		o.style.display = arguments[1];
	}
	return o.style.display; 
}

function C(ElId) { // [, Option SetVal]
	// var o = document.getElementById(ElId);
	
	var o = O(ElId);
	if (arguments.length > 1) {
		o.className = arguments[1];
		// document.getElementById(ElId).className = arguments[1];
	}
	return o.className; 
}