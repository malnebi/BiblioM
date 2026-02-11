import './bootstrap';
import Alpine from 'alpinejs'; // Додајте ово

window.Alpine = Alpine; // Додајте ово
Alpine.start(); // Додајте ово

import.meta.glob([
    '../images/**',
    '../fonts/**',
    '../styles/**',
    '../scripts/**',
    '../views/**',
]);