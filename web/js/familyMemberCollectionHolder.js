var $familyMemberCollectionHolder;

// setup an "add a familyMember" link
var $addFamilyMemberLink = $('<td colspan="5"><a href="#" class="add_familyMember_link">Add a family member</a></td>');
var $newFamilyMemberLinkLi = $('<tr class="last"></tr>').append($addFamilyMemberLink);

jQuery(document).ready(function() {

    // Get the table that holds the collection of familyMembers
    $familyMemberCollectionHolder = $('table.familyMembers');

    // add a delete link to all of the existing familyMember form tr elements
    $familyMemberCollectionHolder.find('tr.familyMembersRow').each(function() {
        addFamilyMemberFormDeleteLink($(this));
    });

    // add the "add a familyMember" anchor and tr to the familyMembers table
    $familyMemberCollectionHolder.append($newFamilyMemberLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $familyMemberCollectionHolder.data('index', $familyMemberCollectionHolder.find(':input').length);

    $addFamilyMemberLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new familyMember form (see next code block)
        addFamilyMemberForm($familyMemberCollectionHolder, $newFamilyMemberLinkLi);
    });
});

function addFamilyMemberForm($familyMemberCollectionHolder, $newFamilyMemberLinkLi) {
    // Get the data-prototype explained earlier
    var familyMemberPrototype = $familyMemberCollectionHolder.data('prototype');
    
    document.getElementById("familyMembersHeader").style.display = "table-row";
    
    //var x = document.getElementById("familyMembersTable").rows.length;

    // get the new index
    var familyMemberIndex = $familyMemberCollectionHolder.data('index');
    
	//document.getElementById("demo").innerHTML = x;

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newFamilyMemberForm = familyMemberPrototype.replace(/__name__/g, familyMemberIndex);

    // increase the index with one for the next item
    $familyMemberCollectionHolder.data('index', familyMemberIndex + 1);

    // Display the form in the page in an li, before the "Add a familyMember" link li
    var $newFamilyMemberFormLi = $('<tr></tr>').append(newFamilyMemberForm);
    $newFamilyMemberLinkLi.before($newFamilyMemberFormLi);
    
	// add a delete link to the new form
    addFamilyMemberFormDeleteLink($newFamilyMemberFormLi, familyMemberIndex);
}

function addFamilyMemberFormDeleteLink($familyMemberFormLi, familyMemberIndex) {
    var $removeFormA = $('<td class="whiteSpaceNoWrap"><a href="#">delete this family member</a></td>');
    $familyMemberFormLi.append($removeFormA);
	
    $removeFormA.on('click', function(e) {
    
		var r = confirm("Are you sure you want to delete this family member record?");
			if (r == true) {
						
				// prevent the link from creating a "#" on the URL
				e.preventDefault();

				if	(document.getElementById("familyMembersTable").rows.length == 3)
				{
					document.getElementById("familyMembersHeader").style.display = "none";
				}

				// remove the tr for the familyMember form
				$familyMemberFormLi.remove();
				
			} else {
				return false;
			}

    });
}
