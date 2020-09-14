/* ------------------------------------------------------------------------------------------------------------------ */
/* JS - Why us buttons not clickable */
/* ------------------------------------------------------------------------------------------------------------------ */
const whyUsButtons = document.querySelectorAll('.whyUsLink');
if(whyUsButtons) {
    whyUsButtons.forEach(function(element) {
        element.addEventListener("click", function(event) {
            event.preventDefault();
        })
    });
}