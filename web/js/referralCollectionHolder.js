var $referralCollectionHolder;

// setup an "add a referral" link
var $addReferralLink = $('<td colspan="5"><a href="#" class="add_referral_link">Add a referral</a></td>');
var $newReferralLinkLi = $('<tr class="last"></tr>').append($addReferralLink);

jQuery(document).ready(function() {

    // Get the table that holds the collection of referrals
    $referralCollectionHolder = $('table.referrals');

    // add a delete link to all of the existing referral form tr elements
    $referralCollectionHolder.find('tr.referralsRow').each(function() {
        addReferralFormDeleteLink($(this));
    });

    // add the "add a referral" anchor and tr to the referrals table
    $referralCollectionHolder.append($newReferralLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $referralCollectionHolder.data('index', $referralCollectionHolder.find(':input').length);

    $addReferralLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new referral form (see next code block)
        addReferralForm($referralCollectionHolder, $newReferralLinkLi);
    });
});

function addReferralForm($referralCollectionHolder, $newReferralLinkLi) {
    // Get the data-prototype explained earlier
    var referralPrototype = $referralCollectionHolder.data('prototype');
    
    document.getElementById("referralsHeader").style.display = "table-row";
    
    //var x = document.getElementById("referralsTable").rows.length;

    // get the new index
    var referralIndex = $referralCollectionHolder.data('index');
    
	//document.getElementById("demo").innerHTML = x;

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newReferralForm = referralPrototype.replace(/__name__/g, referralIndex);

    // increase the index with one for the next item
    $referralCollectionHolder.data('index', referralIndex + 1);

    // Display the form in the page in an li, before the "Add a referral" link li
    var $newReferralFormLi = $('<tr></tr>').append(newReferralForm);
    $newReferralLinkLi.before($newReferralFormLi);
    
	// add a delete link to the new form
    addReferralFormDeleteLink($newReferralFormLi, referralIndex);
}

function addReferralFormDeleteLink($referralFormLi, referralIndex) {
    var $removeFormA = $('<td class="whiteSpaceNoWrap"><a href="#">Delete this referral</a></td>');
    $referralFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
	
		var r = confirm("Are you sure you want to delete this referral record?");
			if (r == true) {
					
				// prevent the link from creating a "#" on the URL
				e.preventDefault();

				if	(document.getElementById("referralsTable").rows.length == 3)
				{
					document.getElementById("referralsHeader").style.display = "none";
				}

				// remove the tr for the referral form
				$referralFormLi.remove();
			
			} else {
				return false;
			}
		
    });
}
