var $appointmentCollectionHolder;

// setup an "add a appointment" link
var $addAppointmentLink = $('<td colspan="5"><a href="#" class="add_appointment_link">Add an appointment</a></td>');
var $newAppointmentLinkLi = $('<tr class="last"></tr>').append($addAppointmentLink);

jQuery(document).ready(function() {

    // Get the table that holds the collection of appointments
    $appointmentCollectionHolder = $('table.appointments');

    // add a delete link to all of the existing appointment form tr elements
    $appointmentCollectionHolder.find('tr.appointmentsRow').each(function() {
        addAppointmentFormDeleteLink($(this));
    });

    // add the "add a appointment" anchor and tr to the appointments table
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
    
    document.getElementById("appointmentsHeader").style.display = "table-row";
    
    //var x = document.getElementById("appointmentsTable").rows.length;

    // get the new index
    var appointmentIndex = $appointmentCollectionHolder.data('index');
    
	//document.getElementById("demo").innerHTML = x;

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newAppointmentForm = appointmentPrototype.replace(/__name__/g, appointmentIndex);

    // increase the index with one for the next item
    $appointmentCollectionHolder.data('index', appointmentIndex + 1);

    // Display the form in the page in an li, before the "Add a appointment" link li
    var $newAppointmentFormLi = $('<tr></tr>').append(newAppointmentForm);
    $newAppointmentLinkLi.before($newAppointmentFormLi);
    
	// add a delete link to the new form
    addAppointmentFormDeleteLink($newAppointmentFormLi, appointmentIndex);
}

function addAppointmentFormDeleteLink($appointmentFormLi, appointmentIndex) {
    var $removeFormA = $('<td class="whiteSpaceNoWrap"><a href="#">Delete this appointment</a></td>');
    $appointmentFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
		var r = confirm("Are you sure you want to delete this appointment record?");
			if (r == true) {
					
				// prevent the link from creating a "#" on the URL
				e.preventDefault();

				if	(document.getElementById("appointmentsTable").rows.length == 3)
				{
					document.getElementById("appointmentsHeader").style.display = "none";
				}

				// remove the tr for the appointment form
				$appointmentFormLi.remove();
			
			} else {
				return false;
			}
    });
}
