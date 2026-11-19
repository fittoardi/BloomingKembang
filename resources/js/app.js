import { animate, inView, scroll } from 'framer-motion/dom';

/**
 * BloomingKembang Framer Motion Engine
 * Powered by framer-motion/dom with hardware acceleration
 */
window.Motion = { animate, inView, scroll };

function initMotionReveal() {
    // Respect user's motion accessibility settings
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.querySelectorAll(
            '.motion-fade-up, .motion-fade-in, .motion-scale-up, .motion-slide-left, .motion-slide-right'
        ).forEach((el) => el.classList.add('motion-visible'));
        return;
    }

    const motionElements = document.querySelectorAll(
        '.motion-fade-up:not(.motion-visible), .motion-fade-in:not(.motion-visible), .motion-scale-up:not(.motion-visible), .motion-slide-left:not(.motion-visible), .motion-slide-right:not(.motion-visible)'
    );

    motionElements.forEach((el) => {
        const delay = parseInt(el.getAttribute('data-motion-delay') || '0', 10);

        try {
            inView(el, () => {
                if (delay > 0) {
                    setTimeout(() => {
                        el.classList.add('motion-visible');
                    }, delay);
                } else {
                    el.classList.add('motion-visible');
                }
            }, { margin: '0px 0px -30px 0px' });
        } catch {
            // Safe fallback if observer fails
            el.classList.add('motion-visible');
        }
    });
}

// Subtle header scroll styling via Framer Motion scroll observer
function initHeaderScrollEffect() {
    const header = document.querySelector('header');
    if (!header || header.dataset.motionScrollInitialized) {
        return;
    }
    header.dataset.motionScrollInitialized = 'true';

    try {
        scroll((progress, info) => {
            const y = info?.y?.current ?? window.scrollY;
            if (y > 20) {
                header.classList.add('shadow-sm', 'bg-white/95');
                header.classList.remove('bg-white/85');
            } else {
                header.classList.remove('shadow-sm', 'bg-white/95');
                header.classList.add('bg-white/85');
            }
        });
    } catch {
        // Safe fallback
    }
}

function initAllMotion() {
    initMotionReveal();
    initHeaderScrollEffect();
}

// Lifecycle listeners: initial DOM + Livewire wire:navigate support
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAllMotion);
} else {
    initAllMotion();
}

document.addEventListener('livewire:navigated', () => {
    initAllMotion();
});

// Window load safety flush (ensures no content stays hidden if offscreen calculations shift)
window.addEventListener('load', () => {
    setTimeout(initMotionReveal, 300);
});
