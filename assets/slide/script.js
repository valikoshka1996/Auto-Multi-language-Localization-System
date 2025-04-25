let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;
const slider = document.querySelector('.slider');
const dotsContainer = document.getElementById('sliderDots');
let autoSlideInterval = null;

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

function startAutoSlide() {
    clearInterval(autoSlideInterval);
    autoSlideInterval = setInterval(() => {
        if (currentSlide < totalSlides - 1) {
            changeSlide(currentSlide + 1, true); // true = авто
        } else {
            changeSlide(0, true);
        }
    }, 5000);
}

function changeSlide(index) {
    if (index < 0) index = 0;
    if (index >= totalSlides) index = totalSlides - 1;
    currentSlide = index;
    slider.style.transition = 'transform 0.3s ease';
    slider.style.transform = `translateX(-${currentSlide * 100}%)`;
    updateDots();
    startAutoSlide();
}

// --- Свайп і мишка з блокуванням меж ---
let isDragging = false;
let startPos = 0;
let currentTranslate = 0;
let prevTranslate = 0;
let animationID = 0;



slider.addEventListener('mousedown', touchStart);
slider.addEventListener('touchstart', touchStart, { passive: true });

slider.addEventListener('mouseup', touchEnd);
slider.addEventListener('mouseleave', touchEnd);
slider.addEventListener('touchend', touchEnd);

slider.addEventListener('mousemove', touchMove);
slider.addEventListener('touchmove', touchMove, { passive: false });

function touchStart(e) {
    isDragging = true;
    startPos = getPositionX(e);
    slider.style.transition = 'none';
    cancelAnimationFrame(animationID);
    autoSlideInterval && clearInterval(autoSlideInterval);
}

function touchMove(e) {
    if (!isDragging) return;
    const currentPosition = getPositionX(e);
    const diff = currentPosition - startPos;

    if (Math.abs(diff) > 10 && e.cancelable) e.preventDefault();

    currentTranslate = prevTranslate + diff;
    slider.style.transform = `translateX(${currentTranslate}px)`;
}

function touchEnd() {
    if (!isDragging) return;
    isDragging = false;
    const movedBy = currentTranslate - prevTranslate;

    if (movedBy < -50 && currentSlide < totalSlides - 1) {
        currentSlide++;
    } else if (movedBy > 50 && currentSlide > 0) {
        currentSlide--;
    }

    setPositionByIndex();
}

function getPositionX(e) {
    return e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
}

function setPositionByIndex() {
    currentTranslate = -currentSlide * slider.offsetWidth;
    prevTranslate = currentTranslate;
    slider.style.transition = 'transform 0.3s ease';
    slider.style.transform = `translateX(${currentTranslate}px)`;
    updateDots();
    startAutoSlide();
}

// --- Колесо миші з блокуванням меж ---
let lastWheelTime = 0;

slider.addEventListener('wheel', (e) => {
    // Пріоритет: горизонтальний скрол
    if (Math.abs(e.deltaX) > Math.abs(e.deltaY)) {
        e.preventDefault(); // Забороняємо браузеру переходити назад/вперед

        const now = Date.now();
        if (now - lastWheelTime < 800) return;

        if (e.deltaX > 30 && currentSlide < totalSlides - 1) {
            changeSlide(currentSlide + 1);
            lastWheelTime = now;
        } else if (e.deltaX < -30 && currentSlide > 0) {
            changeSlide(currentSlide - 1);
            lastWheelTime = now;
        }
    }
}, { passive: false }); // ВАЖЛИВО!



// --- Ініціалізація ---
createDots();
startAutoSlide();

// Scroll-down message
document.querySelector('.scroll-down-indicator')?.addEventListener('click', () => {
    parent.postMessage({ type: 'scrollDown' }, '*');
});

//adaptive slider
window.addEventListener('resize', () => {
    setPositionByIndex(); // адаптує позицію під новий розмір
});
