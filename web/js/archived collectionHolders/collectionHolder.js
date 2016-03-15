var $collectionHolder;

// setup an "add a familyMember" link
var $addFamilyMemberLink = $('<a href="#" class="add_familyMember_link">Add a familyMember</a>');
var $newLinkLi = $('<li></li>').append($addFamilyMemberLink);

jQuery(document).ready(function() {

    // Get the ul that holds the collection of familyMembers
    $collectionHolder = $('ul.familyMembers');

    // add a delete link to all of the existing familyMember form li elements
    $collectionHolder.find('li').each(function() {
        addFamilyMemberFormDeleteLink($(this));
    });
    
    // Get the ul that holds the collection of familyMembers
    $collectionHolder = $('ul.familyMembers');

    // add the "add a familyMember" anchor and li to the familyMembers ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addFamilyMemberLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new familyMember form (see next code block)
        addFamilyMemberForm($collectionHolder, $newLinkLi);
    });
});

function addFamilyMemberForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a familyMember" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
    
	// add a delete link to the new form
    addFamilyMemberFormDeleteLink($newFormLi);
}

function addFamilyMemberFormDeleteLink($familyMemberFormLi) {
    var $removeFormA = $('<a href="#">delete this familyMember</a>');
    $familyMemberFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the familyMember form
        $familyMemberFormLi.remove();
    });
}
