const usersButton = document.querySelector("#getUsers");
const rentalsButton = document.querySelector("#getRentals");
const profileButton = document.querySelector("#editProfile");
if(usersButton) {
    usersButton.addEventListener("click", function(){
        console.log("Utilisateurs");
    });
}
if(rentalsButton) {
    rentalsButton.addEventListener("click", function(){
        console.log("Locations");
    });
}
if(profileButton) {
    profileButton.addEventListener("click", function(){
        console.log("Profile");
    });
}