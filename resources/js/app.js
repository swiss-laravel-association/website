import './bootstrap';
import './ascii';

import.meta.glob([
  '../images/**',
], { eager: true });


import Splide from '@splidejs/splide';
import { AutoScroll } from '@splidejs/splide-extension-auto-scroll';
import '@splidejs/splide/css';

document.querySelectorAll('.splide').forEach((el) => {
    new Splide(el, {
        type: 'loop',
        drag: 'free',
        focus: 'center',
        perPage: 4,
        gap: '1.5rem',
        arrows: false,
        pagination: false,
        autoWidth: false,
        pauseOnHover: true,
        pauseOnFocus: false,
        breakpoints: {
            1024: { perPage: 3 },
            640: { perPage: 2 },
        },
        autoScroll: {
            speed: 0.5,
            pauseOnHover: true,
            pauseOnFocus: false,
        },
    }).mount({ AutoScroll });
});
