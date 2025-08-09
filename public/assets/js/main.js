// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navMenu = document.querySelector('.nav-menu');

    if (mobileMenuBtn && navMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }

    // Auto-submit filters
    const filtersForm = document.getElementById('filtersForm');
    if (filtersForm) {
        const filterInputs = filtersForm.querySelectorAll('input, select');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Debounce for text inputs
                if (this.type === 'text') {
                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => {
                        filtersForm.submit();
                    }, 500);
                } else {
                    filtersForm.submit();
                }
            });
        });
    }

    // Gallery lightbox (simple version)
    const galleryThumbs = document.querySelectorAll('.gallery-thumb');
    galleryThumbs.forEach(thumb => {
        thumb.addEventListener('click', function() {
            // Simple image enlargement - можно заменить на полноценный lightbox
            const fullImage = this.src.replace('/thumbs/', '/gallery/');
            window.open(fullImage, '_blank');
        });
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Loading animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Animate winery cards on scroll
    document.querySelectorAll('.winery-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});