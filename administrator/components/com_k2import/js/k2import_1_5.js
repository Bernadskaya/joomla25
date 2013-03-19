function hide_view_extra_field_group_selection()
{

	if ($('k2category').getValue()=='take_from_csv')
	{
		$('k2extrafieldgroup_tr').setStyle('display', 'table-row');
		$('k2ignore_level_tr').setStyle('display', 'table-row');
	}
	else
	{
		$('k2extrafieldgroup_tr').setStyle('display', 'none');
		$('k2ignore_level_tr').setStyle('display', 'none');
	}
	
}


function add_more_fields(k2importfield_name)
{
	k2importfield=$(k2importfield_name);

	var k2importselect_div=k2importfield.getLast().getLast(); 
	var new_k2importselect_div=k2importselect_div.clone();

	var new_k2importselect=$E('select', new_k2importselect_div);



	for (var i=0; i < new_k2importselect.options.length; i++){
		if (new_k2importselect.options[i].text.toLowerCase() == k2importfield_name.substr(14).replace("_"," ").replace("_"," ")+count_attachments) {
			new_k2importselect.options[i].selected = true;
		} else {
			new_k2importselect.options[i].selected = false;
		}
	}	

	//alert(new_k2importselect.options[2].value);

	new_k2importselect_div.getFirst().setText('['+count_attachments+']');
	new_k2importselect.setProperty('name','k2importfields['+k2importfield_name.substr(14)+count_attachments+']');
	new_k2importselect_div.injectAfter(k2importselect_div);

}




