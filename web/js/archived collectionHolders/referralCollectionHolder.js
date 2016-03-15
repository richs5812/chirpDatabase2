var $referralCollectionHolder;

// setup an "add a referral" link
var $addReferralLink = $('<a href="#" class="add_referral_link">Add a referral</a>');
var $newReferralLinkLi = $('<li></li>').append($addReferralLink);

jQuery(document).ready(function() {

    // Get the ul that holds the collection of referrals
    $referralCollectionHolder = $('ul.referrals');

    // add a delete link to all of the existing referral form li elements
    $referralCollectionHolder.find('li').each(function() {
        addReferralFormDeleteLink($(this));
    });

    // add the "add a referral" anchor and li to the referrals ul
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

    // get the new index
    var referralIndex = $referralCollectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newReferralForm = referralPrototype.replace(/__name__/g, referralIndex);

    // increase the index with one for the next item
    $referralCollectionHolder.data('index', referralIndex + 1);

    // Display the form in the page in an li, before the "Add a referral" link li
    var $newReferralFormLi = $('<li></li>').append(newReferralForm);
    $newReferralLinkLi.before($newReferralFormLi);
    
	// add a delete link to the new form
    addReferralFormDeleteLink($newReferralFormLi);
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
