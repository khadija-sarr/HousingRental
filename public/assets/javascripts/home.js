$(document).ready(function (){
    $(document).load($(window).bind("resize", checkPosition));

    function checkPosition()
    {
        if($(window).width() < 630)
        {
            $(".affichageTitre").html('La dernière offre !');
        } else {
            $(".affichageTitre").html('Nos dernières offres !');
        }
    }
})