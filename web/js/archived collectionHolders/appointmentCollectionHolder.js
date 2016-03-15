var $appointmentCollectionHolder;

// setup an "add a appointment" link
var $addAppointmentLink = $('<a href="#" class="add_appointment_link">Add an appointment</a>');
var $newAppointmentLinkLi = $('<li></li>').append($addAppointmentLink);

jQuery(document).ready(function() {

    // Get the ul that holds the collection of appointments
    $appointmentCollectionHolder = $('ul.appointments');

    // add a delete link to all of the existing appointment form li elements
    $appointmentCollectionHolder.find('li').each(function() {
        addAppointmentFormDeleteLink($(this));
    });

    // add the "add a appointment" anchor and li to the appointments ul
    $appointmentCollectionHolder.append($newAppointmentLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $appointmentCollectionHolder.data('index', $appointmentCollectionHolder.find(':input').length);

    $addAppointmentLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new appointment form (see next code block)
        addAppointmentForm($appointmentCollectionHolder, $newAppointmentLinkLi);
    });
});

function addAppointmentForm($appointmentCollectionHolder, $newAppointmentLinkLi) {
    // Get the data-prototype explained earlier
    var appointmentPrototype = $appointmentCollectionHolder.data('prototype');

    // get the new index
    var appointmentIndex = $appointmentCollectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newAppointmentForm = appointmentPrototype.replace(/__name__/g, appointmentIndex);

    // increase the index with one for the next item
    $appointmentCollectionHolder.data('index', appointmentIndex + 1);

    // Display the form in the page in an li, before the "Add a appointment" link li
    var $newAppointmentFormLi = $('<li></li>').append(newAppointmentForm);
    $newAppointmentLinkLi.before($newAppointmentFormLi);
    
	// add a delete link to the new form
    addAppointmentFormDeleteLink($newAppointmentFormLi);
}

function addAppointmentFormDeleteLink($appointmentFormLi) {
    var $removeFormA = $('<a href="#">delete this appointment</a>');
    $appointmentFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the appointment form
        $appointmentFormLi.remove();
    });
}
