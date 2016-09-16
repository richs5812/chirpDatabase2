var $donationCollectionHolder;

// setup an "add a donation" link
var $addDonationLink = $('<td colspan="5"><a href="#" class="add_donation_link">Add a donation</a></td>');
var $newDonationLinkLi = $('<tr class="last"></tr>').append($addDonationLink);

jQuery(document).ready(function() {

    // Get the table that holds the collection of donations
    $donationCollectionHolder = $('table.donations');

    // add a delete link to all of the existing donation form tr elements
    $donationCollectionHolder.find('tr.donationsRow').each(function() {
        addDonationFormDeleteLink($(this));
    });

    // add the "add a donation" anchor and tr to the donations table
    $donationCollectionHolder.append($newDonationLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $donationCollectionHolder.data('index', $donationCollectionHolder.find(':input').length);

    $addDonationLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new donation form (see next code block)
        addDonationForm($donationCollectionHolder, $newDonationLinkLi);
    });
});

function addDonationForm($donationCollectionHolder, $newDonationLinkLi) {
    // Get the data-prototype explained earlier
    var donationPrototype = $donationCollectionHolder.data('prototype');
    
    document.getElementById("donationsHeader").style.display = "table-row";
    
    //var x = document.getElementById("donationsTable").rows.length;

    // get the new index
    var donationIndex = $donationCollectionHolder.data('index');
    
	//document.getElementById("demo").innerHTML = x;

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newDonationForm = donationPrototype.replace(/__name__/g, donationIndex);

    // increase the index with one for the next item
    $donationCollectionHolder.data('index', donationIndex + 1);

    // Display the form in the page in an li, before the "Add a donation" link li
    var $newDonationFormLi = $('<tr></tr>').append(newDonationForm);
    $newDonationLinkLi.before($newDonationFormLi);
    
	// add a delete link to the new form
    addDonationFormDeleteLink($newDonationFormLi, donationIndex);
}

function addDonationFormDeleteLink($donationFormLi, donationIndex) {
    var $removeFormA = $('<td class="whiteSpaceNoWrap"><a href="#">Delete this donation</a></td>');
    $donationFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
	
		var r = confirm("Are you sure you want to delete this donation record?");
			if (r == true) {
					
				// prevent the link from creating a "#" on the URL
				e.preventDefault();

				if	(document.getElementById("donationsTable").rows.length == 3)
				{
					document.getElementById("donationsHeader").style.display = "none";
				}

				// remove the tr for the donation form
				$donationFormLi.remove();
			
			} else {
				return false;
			}
		
    });
}
