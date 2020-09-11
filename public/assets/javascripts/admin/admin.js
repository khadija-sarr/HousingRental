/* ------------------------------------------------------------------------------------------------------------------ */
/* JS - Back office buttons */
/* ------------------------------------------------------------------------------------------------------------------ */
const showUsers = document.querySelector('a#showUsers');
const showProperties = document.querySelector('a#showProperties');
const deleteButtons = document.querySelectorAll(".delete > a");
/* ------------------------------------------------------------------------------------------------------------------ */
/* JS - Back office tables */
/* ------------------------------------------------------------------------------------------------------------------ */
const usersList = document.querySelector('table#usersList');
const propertiesList = document.querySelector('table#propertiesList');
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS - Admin confirm action popup */
/* ----------------------------------------------------------------------------------------------------------------- */
const adminModal = document.querySelector(".confirmAction");
const adminModalMessage = document.querySelector("#elementToBeDeleted");
/* ------------------------------------------------------------------------------------------------------------------ */
/* JS - Back office event listeners */
/* ------------------------------------------------------------------------------------------------------------------ */
showUsers.addEventListener("click", function(event) {
    event.preventDefault();
    usersList.style.display = "initial";
    propertiesList.style.display = "none";
    setTimeout(function() {
        usersList.style.opacity = "1";
        propertiesList.style.opacity = "0";
    }, 200);
});
showProperties.addEventListener("click", function(event) {
    event.preventDefault();
    usersList.style.display = "none";
    propertiesList.style.display = "initial";
    setTimeout(function() {
        usersList.style.opacity = "0";
        propertiesList.style.opacity = "1";
    }, 200);
});
deleteButtons.forEach(function(element) {
    let confirmButton = document.querySelector("#confirm");
    let cancelButton = document.querySelector("#cancel");
    element.addEventListener("click", function(event) {
        event.preventDefault();
        let redirectIfConfirmed = element.getAttribute("href");
        let userData = element.getAttribute("data-user");
        let houseData = element.getAttribute("data-house");
        adminModal.classList.add("switchDisplay");
        setTimeout(function() {
            adminModal.classList.add("showModal");
        }, 100);
        if(userData) {
            adminModalMessage.innerHTML = userData;
        }
        if(houseData) {
            adminModalMessage.innerHTML = houseData;
        }
        confirmButton.addEventListener("click", function() {
            adminModal.classList.remove("showModal");
            setTimeout(function(){
                adminModal.classList.remove("switchDisplay");
            }, 600);
            window.location.replace("http://localhost:8000" + redirectIfConfirmed);
        });
        cancelButton.addEventListener("click", function() {
            adminModal.classList.remove("showModal");
            setTimeout(function(){
                adminModal.classList.remove("switchDisplay");
            }, 600);
        });
    });
});