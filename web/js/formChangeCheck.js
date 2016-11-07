var _isDirty = false;
$(document).ready(function() { 
	$(":input").change(function(){
	// 	_isDirty = true;
// 		document.getElementById('clientForm').style.backgroundColor = 'hsl(64, 100%, 94%)';
// 		document.getElementById('clientForm').style.backgroundColor = 'rgb(253, 248, 237)';
// 		document.getElementById('clientForm').style.border = '2px dashed dimgray';
// 		document.getElementById('clientForm').style.transition = 'all .5s';
		document.getElementById("unsaved_changes_top").style.display = 'block';
		document.getElementById("unsaved_changes_top_notice").innerHTML = "* Unsaved changes";
// 		document.getElementById("unsaved_changes_bottom").style.display = 'block';
// 		document.getElementById("unsaved_changes_bottom_notice").innerHTML = "* Unsaved changes";
		
		if (document.getElementById("saved_successfully") != null) {
			document.getElementById("saved_successfully").style.display = 'none';
		}
	});
});
