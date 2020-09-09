function initMap() {

    var uluru = {lat: 48.8666, lng: 2.3333};

    var map = new google.maps.Map(
        document.getElementById('map'), {zoom: 10, center: uluru});

    var marker = new google.maps.Marker({position: uluru, map: map});
}