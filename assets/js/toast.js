function showToast(title, message, type = "success") {

    const oldToast = document.querySelector(".svc-toast");

    if (oldToast) oldToast.remove();

    const toast = document.createElement("div");

    toast.className = `svc-toast ${type}`;

    toast.innerHTML = `
        <div class="svc-toast-title">${title}</div>
        <div class="svc-toast-message">${message}</div>
    `;

    document.body.appendChild(toast);

    setTimeout(() => {

        toast.classList.add("show");

    }, 100);

    setTimeout(() => {

        toast.classList.remove("show");

        setTimeout(() => {

            toast.remove();

        }, 300);

    }, 2500);

}

window.showToast = showToast;