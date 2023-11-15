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
