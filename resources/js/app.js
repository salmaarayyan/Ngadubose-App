
document.addEventListener('click', function (event) {
    const button = event.target.closest(
        '[data-evidence-prev], [data-evidence-next], [data-evidence-dot]'
    );

    if (!button) return;

    const slider = button.closest('[data-evidence-slider]');
    if (!slider) return;

    const slides = Array.from(
        slider.querySelectorAll('[data-evidence-slide]')
    );

    const dots = Array.from(
        slider.querySelectorAll('[data-evidence-dot]')
    );

    if (slides.length === 0) return;

    let current = slides.findIndex(
        slide => !slide.classList.contains('hidden')
    );

    if (current < 0) current = 0;

    if (button.hasAttribute('data-evidence-prev')) {
        current = (current - 1 + slides.length) % slides.length;
    } else if (button.hasAttribute('data-evidence-next')) {
        current = (current + 1) % slides.length;
    } else {
        current = Number(button.dataset.evidenceDot);

        if (!Number.isInteger(current) || current < 0 || current >= slides.length) {
            return;
        }
    }

    slides.forEach((slide, index) => {
        slide.classList.toggle('hidden', index !== current);
    });

    dots.forEach((dot, index) => {
        const active = index === current;

        dot.classList.toggle('bg-[#002B5B]', active);
        dot.classList.toggle('scale-110', active);
        dot.classList.toggle('bg-slate-300', !active);
    });

    const activeSlide = slides[current];
    const counter = slider.querySelector('[data-evidence-counter]');
    const name = slider.querySelector('[data-evidence-name]');
    const open = slider.querySelector('[data-evidence-open]');

    if (counter) {
        counter.textContent = `${current + 1} / ${slides.length}`;
    }

    if (name) {
        name.textContent = activeSlide.dataset.name || 'Lampiran';
    }

    if (open) {
        const url = activeSlide.dataset.openUrl || '';

        open.href = url || '#';
        open.classList.toggle('hidden', !url);
    }
});

