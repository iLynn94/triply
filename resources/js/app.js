import './bootstrap';
import './flipwords';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import "flatpickr/dist/themes/material_orange.css"; // Orange theme!
import ApexCharts from 'apexcharts';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

// Expose flatpickr and ApexCharts globally
window.flatpickr = flatpickr;
window.ApexCharts = ApexCharts;

// now you can register
// components using Alpine.data(...) and
// plugins using Alpine.plugin(...)

// Convert Livewire notify events to browser events for toast
// IMPORTANT: Register listener BEFORE starting Livewire
window.addEventListener('livewire:init', () => {
    Livewire.on('notify', (eventData) => {
        const data = Array.isArray(eventData) ? eventData[0] : eventData;
        window.dispatchEvent(new CustomEvent('notify', {
            detail: data
        }));
    });
}, { once: true });

Livewire.start();