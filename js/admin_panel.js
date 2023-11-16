// Toggle mobile menu
function toggleMenu() {
    var links = document.getElementById("admin-nav").getElementsByTagName("a");
    for (var i = 1; i < links.length; i++) {
        if (links[i].style.display === "block") {
            links[i].style.display = "none";
        } else {
            links[i].style.display = "block";
        }
    }
}

// Manage Booking alert
function manageBookingAlert() {
	alert("I am working hard to bring this feature soon! ~ @gibrankhantareen");
}

// Email Reminder
function sendEmailReminders() {
	alert("Reminder email sent to all guests");
}
