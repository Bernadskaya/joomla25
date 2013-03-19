

function add_more_attachments()
{
	add_more_fields('k2importfield_attachment');
	add_more_fields('k2importfield_attachment_title');
	add_more_fields('k2importfield_attachment_title_attribute');
	count_attachments++;

}



function selectDropdownOption(element,wert){
	for (var i=0; i < element.options.length; i++){
		if (element.options[i].value == wert) {
			element.options[i].selected = true;
		} else {
			element.options[i].selected = false;
		}
	}
}

//http://javascript.jstruebig.de/javascript/35
function stripHTML(str){

	// remove all string within tags

	var tmp = str.replace(/(<.*['"])([^'"]*)(['"]>)/g, 

			function(x, p1, p2, p3) { return  p1 + p3;}

	);

	// now remove the tags

	return tmp.replace(/<\/?[^>]+>/gi, '');

}



