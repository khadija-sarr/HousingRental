/* ------------------------------------------------------------------------------------------------------------------ */
/* JS - Why Us Slider */
/* ------------------------------------------------------------------------------------------------------------------ */
$(document).ready(function(){

    var $carrousel = $('#carrousel'),
        $img = $('#carrousel img'),
        indexImg = $img.length - 1,
        i = 0,
        $currentImg = $img.eq(i);

    $img.css('display', 'none');
    $currentImg.css('display', 'block');

    function slideImg(){
        setTimeout(function(){

            if(i < indexImg){
                i++;
            }
            else{
                i = 0;
            }

            $img.css('display', 'none');
            $currentImg = $img.eq(i);
            $currentImg.css('display', 'block');

            slideImg();

        }, 2000);
    }

    slideImg();

});

