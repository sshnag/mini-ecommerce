// Page loader animation
window.addEventListener('load', function() {
    const loader = document.querySelector('.page-loader');
    setTimeout(() => {
        loader.classList.add('fade-out');

        // Initialize GSAP animations
        gsap.from('nav', {
            duration: 1,
            y: -50,
            opacity: 0,
            ease: "power3.out"
        });

        gsap.from('.hero-content', {
            duration: 1.5,
            y: 50,
            opacity: 0,
            delay: 0.5,
            ease: "expo.out"
        });

        // Product card stagger animation
        gsap.from('.product-card', {
            duration: 1,
            y: 50,
            opacity: 0,
            stagger: 0.1,
            delay: 0.8,
            ease: "back.out(1.7)"
        });
    }, 1000);
});

// Scroll animations
window.addEventListener('scroll', function() {
    const elements = document.querySelectorAll('.animate-on-scroll');

    elements.forEach(element => {
        const elementPosition = element.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;

        if (elementPosition < windowHeight - 100) {
            element.classList.add('animated');
        }
    });
});
