var $focusGroupCollectionHolder;

// setup an "add a focusGroup" link
var $addFocusGroupLink = $('<td><a href="#" class="add_focusGroup_link">Add a focus group</a></td>');
var $newFocusGroupLinkLi = $('<tr class="last"></tr>').append($addFocusGroupLink);

jQuery(document).ready(function() {

    // Get the table that holds the collection of focusGroups
    $focusGroupCollectionHolder = $('table.focusGroups');

    // add a delete link to all of the existing focusGroup form tr elements
    $focusGroupCollectionHolder.find('tr.focusGroupsRow').each(function() {
        addFocusGroupFormDeleteLink($(this));
    });

    // add the "add a focusGroup" anchor and tr to the focusGroups table
    $focusGroupCollectionHolder.append($newFocusGroupLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $focusGroupCollectionHolder.data('index', $focusGroupCollectionHolder.find(':input').length);

    $addFocusGroupLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new focusGroup form (see next code block)
        addFocusGroupForm($focusGroupCollectionHolder, $newFocusGroupLinkLi);
    });
});

function addFocusGroupForm($focusGroupCollectionHolder, $newFocusGroupLinkLi) {
    // Get the data-prototype explained earlier
    var focusGroupPrototype = $focusGroupCollectionHolder.data('prototype');
    
    document.getElementById("focusGroupsHeader").style.display = "table-row";
    
    //var x = document.getElementById("focusGroupsTable").rows.length;

    // get the new index
    var focusGroupIndex = $focusGroupCollectionHolder.data('index');
    
	//document.getElementById("demo").innerHTML = x;

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newFocusGroupForm = focusGroupPrototype.replace(/__name__/g, focusGroupIndex);

    // increase the index with one for the next item
    $focusGroupCollectionHolder.data('index', focusGroupIndex + 1);

    // Display the form in the page in an li, before the "Add a focusGroup" link li
    var $newFocusGroupFormLi = $('<tr></tr>').append(newFocusGroupForm);
    $newFocusGroupLinkLi.before($newFocusGroupFormLi);
    
	// add a delete link to the new form
    addFocusGroupFormDeleteLink($newFocusGroupFormLi, focusGroupIndex);
}

function addFocusGroupFormDeleteLink($focusGroupFormLi, focusGroupIndex) {
    var $removeFormA = $('<td class="whiteSpaceNoWrap"><a href="#">delete this focus group</a></td>');
    $focusGroupFormLi.append($removeFormA);
	
    $removeFormA.on('click', function(e) {
    
		var r = confirm("Are you sure you want to delete this focus group record?");
			if (r == true) {
						
				// prevent the link from creating a "#" on the URL
				e.preventDefault();

				if	(document.getElementById("focusGroupsTable").rows.length == 3)
				{
					document.getElementById("focusGroupsHeader").style.display = "none";
				}

				// remove the tr for the focusGroup form
				$focusGroupFormLi.remove();
				
			} else {
				return false;
			}

    });
}
