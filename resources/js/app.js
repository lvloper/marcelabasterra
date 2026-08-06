// // Función para manejar el zoom
// function handleZoom() {
//     // Obtener zoom guardado o calcular uno nuevo
//     let zoom = localStorage.getItem('userZoom');
    
//     if (!zoom && window.devicePixelRatio > 1 && window.devicePixelRatio < 1.5) {
//         zoom = 1 / window.devicePixelRatio;
//         localStorage.setItem('userZoom', zoom);
//     }
    
//     if (zoom) {
//         document.body.style.zoom = zoom;
//     }
// }

// // Aplicar zoom al cargar la página
// handleZoom();

import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';
// Import Swiper and required modules
import { Swiper } from 'swiper/core';
import { Pagination } from 'swiper/modules';
// import function to register Swiper custom elements
import { register } from 'swiper/element';
// Import lite-youtube-embed
import 'lite-youtube-embed';
import 'lite-youtube-embed/src/lite-yt-embed.css';
// register Swiper custom elements

register();

Livewire.start();

// Initialize Swiper elements after page load and navigation
function initSwiperElements() {
    const swiperElements = document.querySelectorAll('swiper-container');
    swiperElements.forEach(element => {
        if (!element.initialized) {
            Object.assign(element, {
                modules: [Pagination]
            });
            element.initialize();
        }
    });
    equalizeCarouselHeights();
}

function equalizeCarouselHeights() {
    document.querySelectorAll('swiper-container.carousel-videos').forEach(track => {
        const slides = Array.from(track.querySelectorAll('swiper-slide'));
        if (slides.length === 0) return;
        slides.forEach(slide => { slide.style.height = ''; });
        const tallest = slides.reduce((max, slide) => Math.max(max, slide.getBoundingClientRect().height), 0);
        if (tallest > 0) {
            slides.forEach(slide => { slide.style.height = `${tallest}px`; });
        }
    });
}

let carouselResizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(carouselResizeTimer);
    carouselResizeTimer = setTimeout(equalizeCarouselHeights, 150);
});

// Expose for external contexts (e.g., iframe previews) and simple event hook
// Safe if defined multiple times across navigations
// eslint-disable-next-line no-undef
if (typeof window !== 'undefined') {
    // Make re-init callable from outside this document (e.g. parent editor)
    // This keeps module references (Pagination) inside this window context.
    window.initSwiperElements = initSwiperElements;
    window.addEventListener('swiper:refresh', () => initSwiperElements());
}

// Initialize on first load
document.addEventListener('DOMContentLoaded', initSwiperElements);

// Mantener zoom en navegación Livewire
document.addEventListener('livewire:init', () => {
    const zoom = document.body.style.zoom;
    
    Livewire.hook('commit.prepare', ({ component }) => {
        if (zoom) {
            document.body.style.zoom = zoom;
        }
    });
});

window.addEventListener('livewire:navigated', () => {
    setTimeout(() => {
        initSwiperElements(); // Initialize Swiper after Livewire navigation
        equalizeCarouselHeights();
        if (window.location.hash == "") {
            window.scrollTo({top: 0});
        } else {
            const element = document.querySelector(window.location.hash)
            if (element) {
                element.scrollIntoView({
                    behavior: 'smooth'
                })
            }
        }
    }, 1)
})