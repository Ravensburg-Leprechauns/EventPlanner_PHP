function validateForm() {
    var designation = document.forms["form_add_event"]["event_new_designation"].value;
    var description = document.forms["form_add_event"]["event_new_description"].value;
    var location = document.forms["form_add_event"]["event_new_location"].value;
    var time = document.forms["form_add_event"]["event_new_time"].value;
    var meetingLocation = document.forms["form_add_event"]["event_new_meeting_location"].value;
    var meetingTime = document.forms["form_add_event"]["event_new_meeting_time"].value;
    
    var seats = document.forms["form_add_event"]["event_new_seats"].value;
    var umps = document.forms["form_add_event"]["event_new_umpires"].value;
    var scorers = document.forms["form_add_event"]["event_new_scorers"].value;

    if (designation == "" || description == "" || location == "" || time == "" || meetingLocation == "" || meetingTime == "") {
        alert("Bitte vervollständigen Sie Ihre Eingabe");
        return false;
    }
}