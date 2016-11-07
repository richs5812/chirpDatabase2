$('#client_form').submit(function() {

	if (document.getElementById("saved_successfully") != null) {
		document.getElementById("saved_successfully").style.display = 'none';
		document.getElementById("unsaved_changes_top").style.display = 'block';
		document.getElementById("unsaved_changes_top_saving").style.display = 'block';
	}

	document.getElementById("unsaved_changes_top_notice").style.display = 'none';
	document.getElementById("unsaved_changes_top_saving").style.display = 'block';
// 	document.getElementById("unsaved_changes_bottom_notice").style.display = 'none';
	document.getElementById("unsaved_changes_bottom").style.display = 'block';
	document.getElementById("unsaved_changes_bottom_saving").style.display = 'block';
	return true;
});
