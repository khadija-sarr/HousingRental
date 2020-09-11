/* ------------------------------------------------------------------------------------------------------------------ */
/* JS - Sliders */
/* ------------------------------------------------------------------------------------------------------------------ */
const homeSlider = document.querySelector('div.homeSlider');
const whyUsSlider = document.querySelector('div.whyUsSlider');
const houseDetailsSlider = document.querySelector('div.houseDetailSlider');
/* ------------------------------------------------------------------------------------------------------------------ */
/* JS - Sliders animation */
/* ------------------------------------------------------------------------------------------------------------------ */
window.onload = function() {
    setInterval(function() {
        if(homeSlider.getAttribute('data-image') === 'image1') {
            homeSlider.classList.remove('background3');
            homeSlider.classList.add('background1');
            homeSlider.setAttribute('data-image', 'image2');
        } else if(homeSlider.getAttribute('data-image') === 'image2') {
            homeSlider.classList.remove('background1');
            homeSlider.classList.add('background2');
            homeSlider.setAttribute('data-image', 'image3');
        } else if(homeSlider.getAttribute('data-image') === 'image3') {
            homeSlider.classList.remove('background2');
            homeSlider.classList.add('background3');
            homeSlider.setAttribute('data-image', 'image1');
        }
    }, 5000);
    if(whyUsSlider) {
        setInterval(function() {
            if(whyUsSlider.getAttribute('data-image') === 'image1') {
                whyUsSlider.classList.remove('background3');
                whyUsSlider.classList.add('background1');
                whyUsSlider.setAttribute('data-image', 'image2');
            } else if(whyUsSlider.getAttribute('data-image') === 'image2') {
                whyUsSlider.classList.remove('background1');
                whyUsSlider.classList.add('background2');
                whyUsSlider.setAttribute('data-image', 'image3');
            } else if(whyUsSlider.getAttribute('data-image') === 'image3') {
                whyUsSlider.classList.remove('background2');
                whyUsSlider.classList.add('background3');
                whyUsSlider.setAttribute('data-image', 'image1');
            }
        }, 5000);
    }
    if(houseDetailsSlider) {
        setInterval(function() {
            if(houseDetailsSlider.getAttribute('data-image') === 'image1') {
                houseDetailsSlider.classList.remove('background8');
                houseDetailsSlider.classList.add('background1');
                houseDetailsSlider.setAttribute('data-image', 'image2');
            } else if(houseDetailsSlider.getAttribute('data-image') === 'image2') {
                houseDetailsSlider.classList.remove('background1');
                houseDetailsSlider.classList.add('background2');
                houseDetailsSlider.setAttribute('data-image', 'image3');
            } else if(houseDetailsSlider.getAttribute('data-image') === 'image3') {
                houseDetailsSlider.classList.remove('background2');
                houseDetailsSlider.classList.add('background3');
                houseDetailsSlider.setAttribute('data-image', 'image4');
            } else if(houseDetailsSlider.getAttribute('data-image') === 'image4') {
                houseDetailsSlider.classList.remove('background3');
                houseDetailsSlider.classList.add('background4');
                houseDetailsSlider.setAttribute('data-image', 'image5');
            } else if(houseDetailsSlider.getAttribute('data-image') === 'image5') {
                houseDetailsSlider.classList.remove('background4');
                houseDetailsSlider.classList.add('background5');
                houseDetailsSlider.setAttribute('data-image', 'image6');
            } else if(houseDetailsSlider.getAttribute('data-image') === 'image6') {
                houseDetailsSlider.classList.remove('background5');
                houseDetailsSlider.classList.add('background6');
                houseDetailsSlider.setAttribute('data-image', 'image7');
            } else if(houseDetailsSlider.getAttribute('data-image') === 'image7') {
                houseDetailsSlider.classList.remove('background6');
                houseDetailsSlider.classList.add('background7');
                houseDetailsSlider.setAttribute('data-image', 'image8');
            } else if(houseDetailsSlider.getAttribute('data-image') === 'image8') {
                houseDetailsSlider.classList.remove('background7');
                houseDetailsSlider.classList.add('background8');
                houseDetailsSlider.setAttribute('data-image', 'image1');
            }
        }, 5000);
    }
}