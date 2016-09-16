var $volunteerSessionCollectionHolder;

// setup an "add a volunteerSession" link
var $addVolunteerSessionLink = $('<td colspan="5"><a href="#" class="add_volunteerSession_link">Add a volunteer session</a></td>');
var $newVolunteerSessionLinkLi = $('<tr class="last"></tr>').append($addVolunteerSessionLink);

jQuery(document).ready(function() {

    // Get the table that holds the collection of volunteerSessions
    $volunteerSessionCollectionHolder = $('table.volunteerSessions');

    // add a delete link to all of the existing volunteerSession form tr elements
    $volunteerSessionCollectionHolder.find('tr.volunteerSessionsRow').each(function() {
        addVolunteerSessionFormDeleteLink($(this));
    });

    // add the "add a volunteerSession" anchor and tr to the volunteerSessions table
    $volunteerSessionCollectionHolder.append($newVolunteerSessionLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $volunteerSessionCollectionHolder.data('index', $volunteerSessionCollectionHolder.find(':input').length);

    $addVolunteerSessionLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new volunteerSession form (see next code block)
        addVolunteerSessionForm($volunteerSessionCollectionHolder, $newVolunteerSessionLinkLi);
    });
});

function addVolunteerSessionForm($volunteerSessionCollectionHolder, $newVolunteerSessionLinkLi) {
    // Get the data-prototype explained earlier
    var volunteerSessionPrototype = $volunteerSessionCollectionHolder.data('prototype');
    
    document.getElementById("volunteerSessionsHeader").style.display = "table-row";
    
    //var x = document.getElementById("volunteerSessionsTable").rows.length;

    // get the new index
    var volunteerSessionIndex = $volunteerSessionCollectionHolder.data('index');
    
	//document.getElementById("demo").innerHTML = x;

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newVolunteerSessionForm = volunteerSessionPrototype.replace(/__name__/g, volunteerSessionIndex);

    // increase the index with one for the next item
    $volunteerSessionCollectionHolder.data('index', volunteerSessionIndex + 1);

    // Display the form in the page in an li, before the "Add a volunteerSession" link li
    var $newVolunteerSessionFormLi = $('<tr></tr>').append(newVolunteerSessionForm);
    $newVolunteerSessionLinkLi.before($newVolunteerSessionFormLi);
    
	// add a delete link to the new form
    addVolunteerSessionFormDeleteLink($newVolunteerSessionFormLi, volunteerSessionIndex);
}

function addVolunteerSessionFormDeleteLink($volunteerSessionFormLi, volunteerSessionIndex) {
    var $removeFormA = $('<td class="whiteSpaceNoWrap"><a href="#">Delete this volunteer session</a></td>');
    $volunteerSessionFormLi.append($removeFormA);
	
    $removeFormA.on('click', function(e) {
    
		var r = confirm("Are you sure you want to delete this volunteer session record?");
			if (r == true) {
						
				// prevent the link from creating a "#" on the URL
				e.preventDefault();

				if	(document.getElementById("volunteerSessionsTable").rows.length == 3)
				{
					document.getElementById("volunteerSessionsHeader").style.display = "none";
				}

				// remove the tr for the volunteerSession form
				$volunteerSessionFormLi.remove();
				
			} else {
				return false;
			}

    });
}
