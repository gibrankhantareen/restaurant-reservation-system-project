// Toggle mobile menu
function toggleMenu() {
    var x = document.getElementById("navbar");
    if (x.classList.contains("responsive")) {
        x.classList.remove("responsive");
    } else {
        x.classList.add("responsive");
    }
}
