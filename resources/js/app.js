import { initConfetti } from './confetti';

// Reveal-on-scroll for elements with the `reveal` class (re-run after Livewire navigations).
const reveal = () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal:not(.is-visible)').forEach((el) => observer.observe(el));
};

document.addEventListener('DOMContentLoaded', () => { reveal(); initConfetti(); });
document.addEventListener('livewire:navigated', () => { reveal(); initConfetti(); });
document.addEventListener('livewire:init', () => {
    Livewire.hook('morphed', () => requestAnimationFrame(reveal));
});

// Hide images that fail to load so the card background shows instead of a broken icon.
document.addEventListener('error', (event) => {
    if (event.target instanceof HTMLImageElement) {
        event.target.style.visibility = 'hidden';
    }
}, true);
