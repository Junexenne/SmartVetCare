// Global Toast Function
window.showToast = function(title, message, type = "success") {
    let toast = document.getElementById("toast");
    let toastIcon = document.getElementById("toastIcon");
    let toastTitle = document.getElementById("toastTitle");
    let toastMessage = document.getElementById("toastMessage");

    if (!toast) return;

    // I-set ang text at uri
    toastTitle.innerText = title;
    toastMessage.innerText = message;

    // Alisin ang dating klase at idagdag ang bago
    toast.className = "toast " + type;

    if (type === "success") {
        toastIcon.className = "fa-solid fa-circle-check";
    } else {
        toastIcon.className = "fa-solid fa-circle-exclamation";
    }

    // Ipakita ang toast
    setTimeout(() => {
        toast.classList.add("show");
    }, 100);

    // Itago pagkalipas ng 3 segundo
    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
};