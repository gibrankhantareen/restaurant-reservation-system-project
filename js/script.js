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
