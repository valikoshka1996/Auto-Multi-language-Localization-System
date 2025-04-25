let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;
const slider = document.querySelector('.slider');
const dotsContainer = document.getElementById('sliderDots');
let autoSlideInterval = null;


document.querySelector('.scroll-down-indicator')?.addEventListener('click', () => {
    // Надсилаємо повідомлення до батьківського вікна
    parent.postMessage({ type: 'scrollDown' }, '*');
});

// Створення точок
function createDots() {
    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (i === 0) dot.classList.add('active');
        dot.addEventListener('click', () => {
            changeSlide(i);
        });
        dotsContainer.appendChild(dot);
    }
}

function updateDots() {
    const dots = document.querySelectorAll('.dot');
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === currentSlide);
    });
}

// Запуск таймера автоперемикання
function startAutoSlide() {
    clearInterval(autoSlideInterval);
    autoSlideInterval = setInterval(() => {
        changeSlide(currentSlide + 1);
    }, 5000);
}

// Функція для зміни слайдів
function changeSlide(index) {
    if (index < 0) {
        currentSlide = totalSlides - 1;
    } else if (index >= totalSlides) {
        currentSlide = 0;
    } else {
        currentSlide = index;
    }
    slider.style.transform = `translateX(-${currentSlide * 100}%)`;
    updateDots();
    startAutoSlide();
}

// --- Свайп (touch) ---
let startX = 0;
let startY = 0;
let isSwiping = false;

slider.addEventListener('touchstart', (e) => {
    startX = e.touches[0].clientX;
    startY = e.touches[0].clientY;
    isSwiping = false;
}, { passive: false });

slider.addEventListener('touchmove', (e) => {
    const deltaX = e.touches[0].clientX - startX;
    const deltaY = e.touches[0].clientY - startY;

    if (Math.abs(deltaX) > Math.abs(deltaY)) {
        isSwiping = true;
        if (e.cancelable) {
            e.preventDefault(); // блокуємо вертикальний скрол
        }
    }
}, { passive: false });

slider.addEventListener('touchend', (e) => {
    const endX = e.changedTouches[0].clientX;
    const deltaX = endX - startX;

    if (isSwiping) {
        if (deltaX > 50) {
            changeSlide(currentSlide - 1);
        } else if (deltaX < -50) {
            changeSlide(currentSlide + 1);
        }
    }
});


// --- Перетягування мишкою ---
let isMouseDown = false;
let mouseStartX = 0;
slider.addEventListener('mousedown', (e) => {
    isMouseDown = true;
    mouseStartX = e.clientX;
});
slider.addEventListener('mousemove', (e) => {
    if (!isMouseDown) return;
    const moveX = mouseStartX - e.clientX;
    if (moveX > 50) {
        changeSlide(currentSlide + 1);
        isMouseDown = false;
    } else if (moveX < -50) {
        changeSlide(currentSlide - 1);
        isMouseDown = false;
    }
});
slider.addEventListener('mouseup', () => {
    isMouseDown = false;
});

// Запускаємо
createDots();
startAutoSlide();

let lastWheelTime = 0;
slider.addEventListener('wheel', (e) => {
    const now = new Date().getTime();

    // Щоб не перескакувало слайди надто швидко
    if (now - lastWheelTime < 800) return;

    if (Math.abs(e.deltaX) > Math.abs(e.deltaY)) {
        if (e.deltaX > 30) {
            changeSlide(currentSlide + 1);
        } else if (e.deltaX < -30) {
            changeSlide(currentSlide - 1);
        }
        lastWheelTime = now;
    }
}, { passive: true });
