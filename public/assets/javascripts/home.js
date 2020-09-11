/* ------------------------------------------------------------------------------------------------------------------ */
/* JS - Home slider */
/* ------------------------------------------------------------------------------------------------------------------ */
const homeSlider = document.querySelector('div.homeSlider');
/* ------------------------------------------------------------------------------------------------------------------ */
/* JS - Home why us slider */
/* ------------------------------------------------------------------------------------------------------------------ */
const whyUsSlider = document.querySelector('div.whyUsSlider');
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
    setInterval(function() {
        if(whyUsSlider.getAttribute('data-image') === 'image1') {
            whyUsSlider.classList.add('background2');
            whyUsSlider.classList.remove('background3');
            whyUsSlider.setAttribute('data-image', 'image2');
        } else if(whyUsSlider.getAttribute('data-image') === 'image2') {
            whyUsSlider.classList.remove('background2');
            whyUsSlider.classList.add('background3');
            whyUsSlider.setAttribute('data-image', 'image3');
        } else if(whyUsSlider.getAttribute('data-image') === 'image3') {
            whyUsSlider.classList.remove('background2');
            whyUsSlider.classList.remove('background3');
            whyUsSlider.setAttribute('data-image', 'image1');
        }
    }, 2000);
}