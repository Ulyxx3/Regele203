// js/script.js
// Main JavaScript file for CineMMI

document.addEventListener('DOMContentLoaded', () => {
    // Add active class to current nav item if not already set by PHP
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-links a');
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Mobile menu toggle (Basic implementation)
    const header = document.querySelector('header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.style.backgroundColor = 'rgba(20, 20, 20, 0.95)';
            header.style.height = '70px';
        } else {
            header.style.backgroundColor = 'rgba(20, 20, 20, 0.9)';
            header.style.height = '80px';
        }
    });

    // Auto-expand search bar on focus
    const searchInput = document.querySelector('.search-form input');
    if (searchInput) {
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.style.border = '1px solid var(--primary-color)';
        });
        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.style.border = 'none';
        });
    }

    console.log('CineMMI initialized with Netflix Red theme.');
});
