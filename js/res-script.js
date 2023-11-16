// Toggle mobile menu
function toggleMenu() {
    var links = document.getElementById("navbar").getElementsByTagName("a");
    for (var i = 1; i < links.length; i++) {
        if (links[i].style.display === "block") {
            links[i].style.display = "none";
        } else {
            links[i].style.display = "block";
        }
    }
}

// Caclulated amount to be shown on Amount tab
function calculateAmountDue() {
	var tables = document.querySelector('input[name="tables"]').value;
	var amountDue = tables * 1000;
	document.getElementById('amount_due').value = amountDue ? 'Rs ' + amountDue : '';
}

// Validating the form for correct input
function validateForm() {
    var name = document.forms["reservationForm"]["name"].value;
    var email = document.forms["reservationForm"]["email"].value;
    var phone = document.forms["reservationForm"]["phone"].value;
    var date = document.forms["reservationForm"]["date"].value;
    var time = document.forms["reservationForm"]["time"].value;

    // Email Validation
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert("Invalid email format.");
        return false;
    }

    // Phone Number Validation
    if (!/^\d{10}$/.test(phone)) {
        alert("Phone number must be 10 digits.");
        return false;
    }

    // Date and Time Validation
    var currentDate = new Date();
    var inputDate = new Date(date + 'T' + time);

    var currentDateString = currentDate.toISOString().split('T')[0];
    var inputDateString = inputDate.toISOString().split('T')[0];

    // Check if the selected date is not today
    if (inputDateString < currentDateString) {
        alert("The selected date must not be in past.");
        return false;
    }

    // Check if the selected time is in the past
    if (inputDate < currentDate) {
        alert("The time cannot be in the past.");
        return false;
    }

    return true;
}
