import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const lightbox = document.querySelector('#image-lightbox');
    const viewerImage = lightbox?.querySelector('[data-viewer-image]');
    const zoomIn = lightbox?.querySelector('[data-zoom-in]');
    const zoomOut = lightbox?.querySelector('[data-zoom-out]');
    const zoomReset = lightbox?.querySelector('[data-zoom-reset]');
    const closeBtn = lightbox?.querySelector('[data-close-viewer]');
    let scale = 1;

    const applyScale = () => {
        if (viewerImage) viewerImage.style.transform = `scale(${scale})`;
    };

    const openViewer = (src) => {
        if (!lightbox || !viewerImage) return;
        viewerImage.src = src;
        scale = 1;
        applyScale();
        lightbox.classList.remove('hidden');
    };

    const closeViewer = () => {
        if (!lightbox) return;
        lightbox.classList.add('hidden');
        scale = 1;
        applyScale();
    };

    document.querySelectorAll('[data-image-viewer]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const src = btn.getAttribute('data-image-src');
            if (src) openViewer(src);
        });
    });

    zoomIn?.addEventListener('click', () => {
        scale = Math.min(scale + 0.2, 3);
        applyScale();
    });

    zoomOut?.addEventListener('click', () => {
        scale = Math.max(scale - 0.2, 0.6);
        applyScale();
    });

    zoomReset?.addEventListener('click', () => {
        scale = 1;
        applyScale();
    });

    closeBtn?.addEventListener('click', closeViewer);
    lightbox?.addEventListener('click', (e) => {
        if (e.target === lightbox || e.target.classList.contains('bg-black/80')) {
            closeViewer();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeViewer();
    });

    // Drag-to-scroll horizontal lists
    document.querySelectorAll('[data-drag-scroll]').forEach((el) => {
        let isDown = false;
        let startX;
        let scrollLeft;

        el.addEventListener('mousedown', (e) => {
            isDown = true;
            el.classList.add('select-none');
            startX = e.pageX - el.offsetLeft;
            scrollLeft = el.scrollLeft;
        });

        el.addEventListener('mouseleave', () => {
            isDown = false;
            el.classList.remove('select-none');
        });

        el.addEventListener('mouseup', () => {
            isDown = false;
            el.classList.remove('select-none');
        });

        el.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - el.offsetLeft;
            const walk = (x - startX) * 1.2;
            el.scrollLeft = scrollLeft - walk;
        });
    });

    // Button-based horizontal scroll
    document.querySelectorAll('[data-scroll-prev],[data-scroll-next]').forEach((btn) => {
        const targetSelector = btn.getAttribute('data-scroll-target');
        const step = Number(btn.getAttribute('data-scroll-step')) || 320;
        const target = targetSelector ? document.querySelector(targetSelector) : null;
        if (!target) return;

        btn.addEventListener('click', () => {
            const direction = btn.hasAttribute('data-scroll-prev') ? -1 : 1;
            target.scrollBy({ left: direction * step, behavior: 'smooth' });
        });
    });
});
