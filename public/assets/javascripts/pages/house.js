window.onload = function() {
    setInterval(function() {
        changeImage()
    }, 3000);
}

// fonction changeImage pour le carousel
function changeImage() {
    var monCarousel = document.getElementById('carousel');
    var imageEnCours = monCarousel.getAttribute('data-image');

    if(imageEnCours === "image1") {
        document.querySelector('#carousel img:first-child').style.opacity = 0;
        document.querySelector('#carousel img:nth-child(2)').style.opacity = 1;
        monCarousel.setAttribute('data-image',"image2");
    } else if(imageEnCours === "image2") {
        document.querySelector('#carousel img:nth-child(2)').style.opacity = 0;
        document.querySelector('#carousel img:nth-child(3)').style.opacity = 1;
        monCarousel.setAttribute('data-image', "image3");
    } else if(imageEnCours === "image3") {
        document.querySelector('#carousel img:nth-child(3)').style.opacity = 0;
        document.querySelector('#carousel img:nth-child(4)').style.opacity = 1;
        monCarousel.setAttribute('data-image', "image4");
    } else if(imageEnCours === "image4") {
    document.querySelector('#carousel img:nth-child(4)').style.opacity = 0;
    document.querySelector('#carousel img:nth-child(5)').style.opacity = 1;
    monCarousel.setAttribute('data-image', "image5");
    } else if(imageEnCours === "image5") {
        document.querySelector('#carousel img:nth-child(5)').style.opacity = 0;
        document.querySelector('#carousel img:nth-child(6)').style.opacity = 1;
        monCarousel.setAttribute('data-image', "image6");
    } else if(imageEnCours === "image6") {
        document.querySelector('#carousel img:nth-child(6)').style.opacity = 0;
        document.querySelector('#carousel img:nth-child(7)').style.opacity = 1;
        monCarousel.setAttribute('data-image', "image7");
    } else if(imageEnCours === "image7") {
        document.querySelector('#carousel img:nth-child(7)').style.opacity = 0;
        document.querySelector('#carousel img:nth-child(8)').style.opacity = 1;
        monCarousel.setAttribute('data-image', "image8");
    } else {
        document.querySelector('#carousel img:nth-child(8)').style.opacity = 0;
        document.querySelector('#carousel img:nth-child(1)').style.opacity = 1;
        monCarousel.setAttribute('data-image', "image1");
    }
}