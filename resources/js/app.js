import './bootstrap';

const initHeroCarousel = () => {
    const hero = document.querySelector('[data-carousel="hero"]');
    if (!hero) return;

    const slides = Array.from(hero.querySelectorAll('[data-slide]'));
    const dots = Array.from(hero.querySelectorAll('[data-dots] span'));
    const prevBtn = hero.querySelector('[data-prev]');
    const nextBtn = hero.querySelector('[data-next]');
    const heroBg = hero.querySelector('[data-hero-bg]');

    if (!slides.length) return;

    let activeIndex = 0;
    let timerId;

    const setActive = (index) => {
        activeIndex = (index + slides.length) % slides.length;

        slides.forEach((slide, idx) => {
            slide.classList.toggle('hidden', idx !== activeIndex);
        });

        const imageUrl = slides[activeIndex].dataset.image;
        if (heroBg && imageUrl) {
            heroBg.style.transition = 'transform 800ms ease';
            heroBg.style.transform = 'translateX(30px)';

            window.setTimeout(() => {
                heroBg.style.backgroundImage = `url('${imageUrl}')`;
                requestAnimationFrame(() => {
                    heroBg.style.transform = 'translateX(0)';
                });
            }, 120);
        }

        dots.forEach((dot, idx) => {
            dot.classList.toggle('bg-white', idx === activeIndex);
            dot.classList.toggle('bg-white/30', idx !== activeIndex);
            dot.classList.toggle('w-6', idx === activeIndex);
            dot.classList.toggle('rounded-full', true);
        });
    };

    const resetTimer = () => {
        if (timerId) clearInterval(timerId);
        timerId = setInterval(() => setActive(activeIndex + 1), 5000);
    };

    nextBtn?.addEventListener('click', () => {
        setActive(activeIndex + 1);
        resetTimer();
    });

    prevBtn?.addEventListener('click', () => {
        setActive(activeIndex - 1);
        resetTimer();
    });

    dots.forEach((dot, idx) => {
        dot.addEventListener('click', () => {
            setActive(idx);
            resetTimer();
        });
    });

    setActive(activeIndex);
    resetTimer();
};

const initScrollControls = () => {
    const containers = Array.from(document.querySelectorAll('[data-scroll-container]'));
    if (!containers.length) return;

    containers.forEach((container) => {
        const id = container.getAttribute('id');
        if (!id) return;

        const prevButtons = Array.from(
            document.querySelectorAll(`[data-scroll-prev][data-scroll-target="#${id}"]`)
        );
        const nextButtons = Array.from(
            document.querySelectorAll(`[data-scroll-next][data-scroll-target="#${id}"]`)
        );

        const scrollByStep = (direction, step) => {
            container.scrollBy({ left: direction * step, behavior: 'smooth' });
        };

        const bindButtons = (buttons, direction) => {
            buttons.forEach((btn) => {
                const step = Number(btn.dataset.scrollStep || container.clientWidth * 0.8);
                btn.addEventListener('click', () => scrollByStep(direction, step));
            });
        };

        bindButtons(prevButtons, -1);
        bindButtons(nextButtons, 1);
    });
};

const initActiveNav = () => {
    const links = Array.from(document.querySelectorAll('[data-nav-link]'));
    if (!links.length) return;

    const sections = links
        .map((link) => {
            const target = link.getAttribute('href') || '';
            if (!target.startsWith('#')) return null;
            return document.querySelector(target);
        })
        .filter(Boolean);

    const setActive = (hash) => {
        links.forEach((link) => {
            const match = link.getAttribute('href') === hash;
            link.classList.toggle('text-teal-600', match);
            link.classList.toggle('font-semibold', match);
            link.setAttribute('aria-current', match ? 'page' : 'false');
        });
    };

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    setActive(`#${entry.target.id}`);
                }
            });
        },
        { rootMargin: '-40% 0px -40% 0px', threshold: 0.3 }
    );

    sections.forEach((section) => observer.observe(section));

    links.forEach((link) => {
        link.addEventListener('click', () => setActive(link.getAttribute('href')));
    });

    const initialHash = window.location.hash || '#home';
    setActive(initialHash);
};

const initProgramFilters = () => {
    const list = document.querySelector('[data-program-list]');
    const searchInput = document.querySelector('[data-program-search]');
    const filterButtons = Array.from(document.querySelectorAll('[data-program-filter]'));
    const emptyState = document.querySelector('[data-program-empty]');

    if (!list || !searchInput) return;

    const cards = Array.from(list.querySelectorAll('[data-program-card]'));
    let activeCategory = 'semua';
    let searchTerm = '';

    const applyFilters = () => {
        let visible = 0;
        cards.forEach((card) => {
            const text = card.dataset.programText || '';
            const category = card.dataset.programCategory || '';
            const matchText = text.includes(searchTerm.toLowerCase());
            const matchCategory =
                activeCategory === 'semua' || category === activeCategory.toLowerCase();

            const show = matchText && matchCategory;
            card.classList.toggle('hidden', !show);
            if (show) visible += 1;
        });

        if (emptyState) {
            emptyState.classList.toggle('hidden', visible !== 0);
        }
    };

    searchInput.addEventListener('input', (e) => {
        searchTerm = (e.target.value || '').toLowerCase();
        applyFilters();
    });

    filterButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            activeCategory = btn.dataset.programFilter || 'semua';
            filterButtons.forEach((b) => {
                const isActive = b === btn;
                b.classList.toggle('bg-emerald-50', isActive);
                b.classList.toggle('text-teal-700', isActive);
                b.classList.toggle('border-emerald-200', isActive);
            });
            applyFilters();
        });
    });

    applyFilters();
};

document.addEventListener('DOMContentLoaded', () => {
    initHeroCarousel();
    initScrollControls();
    initActiveNav();
    initProgramFilters();
});
