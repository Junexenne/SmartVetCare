function showToast(title, message, type = "success") {

    const icons = {
        success: "✅",
        error: "❌",
        warning: "⚠️",
        info: "ℹ️"
    };

    const toast = document.createElement("div");

    toast.className = `toast ${type}`;

    toast.innerHTML = `
        <div class="toast-header">
            <span class="toast-icon">${icons[type]}</span>

            <div class="toast-text">

                <h4>${title}</h4>

                <p>${message}</p>

            </div>

        </div>

        <div class="toast-progress"></div>
    `;

    document.body.appendChild(toast);

    setTimeout(() => {

        toast.classList.add("show");

    },100);

    setTimeout(() => {

        toast.classList.remove("show");

        setTimeout(()=>{

            toast.remove();

        },400);

    },3500);

}

window.showToast = showToast;