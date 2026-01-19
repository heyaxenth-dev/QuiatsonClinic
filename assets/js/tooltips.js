/**
 * Quiatson Clinic - Tooltip System
 * Centralized tooltip initialization for admin and client sections
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            trigger: 'hover',
            placement: 'auto',
            html: true
        });
    });

    // Initialize tooltips with custom data attributes
    var customTooltips = document.querySelectorAll('[data-tooltip]');
    customTooltips.forEach(function(element) {
        new bootstrap.Tooltip(element, {
            title: element.getAttribute('data-tooltip'),
            trigger: 'hover',
            placement: element.getAttribute('data-placement') || 'auto',
            html: true
        });
    });
});
