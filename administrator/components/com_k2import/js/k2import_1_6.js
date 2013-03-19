function hide_view_extra_field_group_selection()
{

	if (document.id('k2category').get('value')=='take_from_csv')
	{
		document.id('k2extrafieldgroup_tr').setStyle('display', 'table-row');
		document.id('k2ignore_level_tr').setStyle('display', 'table-row');
	}
	else
	{
		document.id('k2extrafieldgroup_tr').setStyle('display', 'none');
		document.id('k2ignore_level_tr').setStyle('display', 'none');
	}
	
}


function add_more_fields(k2importfield_name)
{
	k2importfield=$(k2importfield_name);

	var k2importselect_div=k2importfield.getLast().getLast(); 
	var new_k2importselect_div=k2importselect_div.clone();

	var new_k2importselect=new_k2importselect_div.getElement('select');



	for (var i=0; i < new_k2importselect.options.length; i++){
		if (new_k2importselect.options[i].text.toLowerCase() == k2importfield_name.substr(14).replace("_"," ").replace("_"," ")+count_attachments) {
			new_k2importselect.options[i].selected = true;
		} else {
			new_k2importselect.options[i].selected = false;
		}
	}	

	//alert(new_k2importselect.options[2].value);

	new_k2importselect_div.getFirst().set('text','['+count_attachments+']');
	new_k2importselect.setProperty('name','k2importfields['+k2importfield_name.substr(14)+count_attachments+']');
	new_k2importselect_div.injectAfter(k2importselect_div);

}




