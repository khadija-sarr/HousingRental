/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Getting main */
/* ----------------------------------------------------------------------------------------------------------------- */
const main = document.querySelector("main");
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Getting base navbar & footer */
/* ----------------------------------------------------------------------------------------------------------------- */
const removeFirstNav = document.querySelector("body > nav.navbar");
const removeLastFooter = document.querySelector("footer");
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Getting admin page buttons */
/* ----------------------------------------------------------------------------------------------------------------- */
const usersButton = document.querySelector(".adminPanel #getUsers");
const rentalsButton = document.querySelector(".adminPanel #getRentals");
const deleteButtons = document.querySelectorAll(".delete > a");
const settingsButton = document.querySelector("#adminPageSettings");
const themeButton = document.querySelector(".switch");
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Getting admin page containers */
/* ----------------------------------------------------------------------------------------------------------------- */
const mainContent = document.querySelector(".adminPanel > .mainContent").children;
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Getting parent of the previous containers */
/* ----------------------------------------------------------------------------------------------------------------- */
const adminPanel = document.querySelector(".adminPanel");
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Getting admin page modal */
/* ----------------------------------------------------------------------------------------------------------------- */
const adminModal = document.querySelector(".confirmAction");
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Getting admin page flash message */
/* ----------------------------------------------------------------------------------------------------------------- */
const adminFlashMessage = document.querySelector(".flashMessage");
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Getting settings window */
/* ----------------------------------------------------------------------------------------------------------------- */
const settingsWindow = document.querySelector(".settings");
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Getting admin page modal's dynamic message */
/* ----------------------------------------------------------------------------------------------------------------- */
const adminModalMessage = document.querySelector("#elementToBeDeleted");
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Removing base navbar */
/* ----------------------------------------------------------------------------------------------------------------- */
if(removeFirstNav) {
    removeFirstNav.remove();
}
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Removing base footer */
/* ----------------------------------------------------------------------------------------------------------------- */
if(removeLastFooter) {
    removeLastFooter.remove();
}
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Removing main HTML tag's classes */
/* ----------------------------------------------------------------------------------------------------------------- */
main.classList.remove("container-fluid", "d-flex");
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Switching container's display */
/* ----------------------------------------------------------------------------------------------------------------- */
if(usersButton) {
    usersButton.addEventListener("click", function() {
        mainContent[0].classList.add("showAdminMainContent");
        mainContent[1].classList.remove("showAdminMainContent");
        adminPanel.classList.add("darkFilter");
    });
}
if(rentalsButton) {
    rentalsButton.addEventListener("click", function() {
        mainContent[0].classList.remove("showAdminMainContent");
        mainContent[1].classList.add("showAdminMainContent");
        adminPanel.classList.add("darkFilter");
    });
}
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Calling function if admin tries to delete a user for confirmation */
/* ----------------------------------------------------------------------------------------------------------------- */
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
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Showing settings window in admin page */
/* ----------------------------------------------------------------------------------------------------------------- */
if(settingsButton) {
    settingsButton.addEventListener("click", function() {
        settingsWindow.classList.toggle("showSettings");
    })
}
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Removing flash messages after few seconds */
/* ----------------------------------------------------------------------------------------------------------------- */
if(adminFlashMessage) {
    adminFlashMessage.addEventListener("click", function() {
        adminFlashMessage.classList.add("fadeOut");
        setTimeout(function() {
            adminFlashMessage.remove();
        }, 500);
    });
}
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Switching theme */
/* ----------------------------------------------------------------------------------------------------------------- */
themeButton.addEventListener("change", function() {
    let theme = document.querySelector("#themeSwitch");
    if(theme.checked === true) {
        document.body.setAttribute("data-theme", "light");
        localStorage.setItem("theme", "Light");
    } else {
        document.body.setAttribute("data-theme", "dark");
        localStorage.setItem("theme", "Dark");
    }
});
/* ----------------------------------------------------------------------------------------------------------------- */
/* JS Loading selected theme */
/* ----------------------------------------------------------------------------------------------------------------- */
window.onload = function() {
    let theme = document.querySelector("#themeSwitch");
    if(localStorage.getItem('theme') === "Light") {
        theme.checked = true;
        document.body.setAttribute("data-theme", "light");
    } else {
        theme.checked = false;
        document.body.setAttribute("data-theme", "dark");
    }
}