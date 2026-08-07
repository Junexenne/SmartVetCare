const track = document.querySelector('.testimonial-track');
const nextBtn = document.querySelector('.next');
const prevBtn = document.querySelector('.prev');
const cards = document.querySelectorAll('.testimonial-card');
const dots = document.querySelectorAll('.testimonial-dots span');

let currentIndex = 0;

function getCardWidth() {
    if (cards.length > 0) {
        const cardWidth = cards[0].offsetWidth;
        const gap = parseInt(window.getComputedStyle(track).gap) || 20;
        return cardWidth + gap;
    }
    return 0;
}

function updateSlider(index) {
    const cardWidth = getCardWidth();
    track.scrollTo({
        left: index * cardWidth,
        behavior: 'smooth'
    });

    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === index);
    });
}

nextBtn.addEventListener('click', () => {
    if (currentIndex < cards.length - 3) {
        currentIndex++;
    } else {
        currentIndex = 0;
    }
    updateSlider(currentIndex);
});

prevBtn.addEventListener('click', () => {
    if (currentIndex > 0) {
        currentIndex--;
    } else {
        currentIndex = cards.length - 3;
    }
    updateSlider(currentIndex);
});

dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        if (index <= cards.length - 3) {
            currentIndex = index;
            updateSlider(currentIndex);
        }
    });
});