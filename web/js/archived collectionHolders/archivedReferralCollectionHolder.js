var $referralCollectionHolder;

// setup an "add a referral" link
var $addReferralLink = $('<a href="#" class="add_referral_link">Add a referral</a>');
var $newLinkLi = $('<li></li>').append($addReferralLink);

jQuery(document).ready(function() {

    // Get the ul that holds the collection of referrals
    $referralCollectionHolder = $('ul.referrals');

    // add a delete link to all of the existing referral form li elements
    $referralCollectionHolder.find('li').each(function() {
        addReferralFormDeleteLink($(this));
    });

    // add the "add a referral" anchor and li to the referrals ul
    $referralCollectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $referralCollectionHolder.data('index', $referralCollectionHolder.find(':input').length);

    $addReferralLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new referral form (see next code block)
        addReferralForm($referralCollectionHolder, $newLinkLi);
    });
});

function addReferralForm($referralCollectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $referralCollectionHolder.data('prototype');

    // get the new index
    var index = $referralCollectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $referralCollectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a referral" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
    
	// add a delete link to the new form
    addReferralFormDeleteLink($newFormLi);
}

function addReferralFormDeleteLink($referralFormLi) {
    var $removeFormA = $('<a href="#">delete this referral</a>');
    $referralFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the referral form
        $referralFormLi.remove();
    });
}
