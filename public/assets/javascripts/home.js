/* ------------------------------------------------------------------------------------------------------------------ */
/* JS - Home slider */
/* ------------------------------------------------------------------------------------------------------------------ */
const homeSlider = document.querySelector('div.homeSlider');
/* ------------------------------------------------------------------------------------------------------------------ */
/* JS - Home background image animation */
/* ------------------------------------------------------------------------------------------------------------------ */
window.onload = function() {
    setInterval(function() {
        if(homeSlider.getAttribute('data-image') === 'image1') {
            homeSlider.classList.add('background2');
            homeSlider.classList.remove('background3');
            homeSlider.setAttribute('data-image', 'image2');
        } else if(homeSlider.getAttribute('data-image') === 'image2') {
            homeSlider.classList.remove('background2');
            homeSlider.classList.add('background3');
            homeSlider.setAttribute('data-image', 'image3');
        } else if(homeSlider.getAttribute('data-image') === 'image3') {
            homeSlider.classList.remove('background2');
            homeSlider.classList.remove('background3');
            homeSlider.setAttribute('data-image', 'image1');
        }
    }, 2000);
}