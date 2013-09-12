/**
 * Toggles checkboxes between check/uncheck state.
 */
var checkflag = false;
function toggle(checkboxes, checkall, uncheckall)
{
	if (checkflag == false) {
	  for (i = 0; i < checkboxes.length; i++) {
		  checkboxes[i].checked = true;
	  }
	  checkflag = true;
	  return uncheckall;
	}
	else {
	  for (i = 0; i < checkboxes.length; i++) {
		  checkboxes[i].checked = false;
	  }
	  checkflag = false;
	  return checkall;
	}
}

/**
 * The button $(id) will allow to check all / uncheck all 
 * all checkboxes with class .checkboxClass
 * 
 */
function setSelectUnselectAllButton(button_id, checkbox_class, checkallstr, uncheckallstr) {
	var checkflag = 'false';
	$('#'+button_id).mousedown(function() {
		$(this).attr('value', toggle(jQuery('.'+checkbox_class),checkallstr,uncheckallstr));
	});
}