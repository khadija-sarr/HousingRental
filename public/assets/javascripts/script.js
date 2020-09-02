const usersButton = document.querySelector(".adminPanel #getUsers");
const rentalsButton = document.querySelector(".adminPanel #getRentals");
const profileButton = document.querySelector(".adminPanel #editProfile");
const mainContent = document.querySelector(".adminPanel > .mainContent").children;
if(usersButton) {
    usersButton.addEventListener("click", function(){
        mainContent[0].classList.add("showAdminMainContent");
        mainContent[1].classList.remove("showAdminMainContent");
        mainContent[2].classList.remove("showAdminMainContent");
    });
}
if(rentalsButton) {
    rentalsButton.addEventListener("click", function(){
        mainContent[0].classList.remove("showAdminMainContent");
        mainContent[1].classList.add("showAdminMainContent");
        mainContent[2].classList.remove("showAdminMainContent");
    });
}
if(profileButton) {
    profileButton.addEventListener("click", function(){
        mainContent[0].classList.remove("showAdminMainContent");
        mainContent[1].classList.remove("showAdminMainContent");
        mainContent[2].classList.add("showAdminMainContent");
    });
}
const removeFirstNav = document.querySelector("body > nav.navbar");
if(removeFirstNav) {
    removeFirstNav.remove();
}
const removeLastFooter = document.querySelector("footer");
if(removeLastFooter) {
    removeLastFooter.remove();
}