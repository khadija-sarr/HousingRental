var slider1 = document.getElementById("myRangeMin");
var priceMin = document.getElementById("form_priceMin");
priceMin.value = slider1.value;
slider1.oninput = function() {
    priceMin.value = this.value;

}
var slider2 = document.getElementById("myRangeMax");
var priceMax = document.getElementById("form_priceMax");
priceMax.value = slider2.value;
slider2.oninput = function() {
    priceMax.value = this.value;
}