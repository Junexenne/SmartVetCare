const faqs = document.querySelectorAll(".faq-item");

faqs.forEach(item => {

    item.querySelector(".faq-question").addEventListener("click", () => {

        item.classList.toggle("active");

    });

});