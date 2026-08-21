document.addEventListener("DOMContentLoaded", () => {
    const track = document.querySelector('.testimonial-track');
    const nextButton = document.querySelector('.testimonial-slider .next');
    const prevButton = document.querySelector('.testimonial-slider .prev');
    const dots = document.querySelectorAll('.testimonial-dots span');
    
    if (!track) return;

    let autoPlayInterval;

    function getScrollAmount() {
        const card = track.querySelector('.testimonial-card');
        if (!card) return 300;
        const cardWidth = card.offsetWidth;
        const gap = parseInt(window.getComputedStyle(track).gap) || 20;
        return cardWidth + gap;
    }

    function updateActiveDot() {
        const scrollLeft = track.scrollLeft;
        const scrollAmount = getScrollAmount();
        const index = Math.round(scrollLeft / scrollAmount);

        dots.forEach((dot, idx) => {
            if (idx === index) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }

    function scrollNext() {
        const scrollAmount = getScrollAmount();
        if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 10) {
            track.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }

    function scrollPrev() {
        const scrollAmount = getScrollAmount();
        if (track.scrollLeft <= 10) {
            track.scrollTo({ left: track.scrollWidth, behavior: 'smooth' });
        } else {
            track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        }
    }

    function startAutoPlay() {
        autoPlayInterval = setInterval(scrollNext, 10000);
    }

    function stopAutoPlay() {
        clearInterval(autoPlayInterval);
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            scrollNext();
            stopAutoPlay();
            startAutoPlay();
        });
    }

    if (prevButton) {
        prevButton.addEventListener('click', () => {
            scrollPrev();
            stopAutoPlay();
            startAutoPlay();
        });
    }

    dots.forEach((dot, idx) => {
        dot.addEventListener('click', () => {
            const scrollAmount = getScrollAmount();
            track.scrollTo({ left: scrollAmount * idx, behavior: 'smooth' });
            stopAutoPlay();
            startAutoPlay();
        });
    });

    track.addEventListener('scroll', updateActiveDot);

    const sliderContainer = document.querySelector('.testimonial-slider');
    if (sliderContainer) {
        sliderContainer.addEventListener('mouseenter', stopAutoPlay);
        sliderContainer.addEventListener('mouseleave', startAutoPlay);
    }

    startAutoPlay();
});