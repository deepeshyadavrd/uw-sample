// Custom JavaScript for WoodCraft Furniture Store

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functionality
    initSmoothScrolling();
    initContactForm();
    initWishlistButtons();
    initScrollAnimations();
    initNavbarCollapse();
});

// Smooth scrolling for navigation links
function initSmoothScrolling() {
    const navLinks = document.querySelectorAll('a[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                const navbarHeight = document.querySelector('.navbar').offsetHeight;
                const targetPosition = targetSection.offsetTop - navbarHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Contact form functionality
function initContactForm() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                name: document.getElementById('name').value.trim(),
                email: document.getElementById('email').value.trim(),
                phone: document.getElementById('phone').value.trim(),
                category: document.getElementById('category').value,
                message: document.getElementById('message').value.trim()
            };
            
            // Validate required fields
            if (!formData.name || !formData.email || !formData.message) {
                showToast('Missing Information', 'Please fill in all required fields.', 'error');
                return;
            }
            
            // Validate email format
            if (!isValidEmail(formData.email)) {
                showToast('Invalid Email', 'Please enter a valid email address.', 'error');
                return;
            }
            
            // Show loading state
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
            submitBtn.disabled = true;
            
            // Simulate form submission (replace with actual API call)
            setTimeout(() => {
                // Reset form
                contactForm.reset();
                
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                // Show success message
                showToast('Message Sent!', "We'll get back to you within 24 hours.", 'success');
                
                // You can add actual form submission logic here
                console.log('Form submitted:', formData);
            }, 2000);
        });
    }
}

// Email validation helper
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Toast notification system
function showToast(title, message, type = 'success') {
    const toastEl = document.getElementById('liveToast');
    const toastTitle = document.getElementById('toastTitle');
    const toastMessage = document.getElementById('toastMessage');
    
    // Set content
    toastTitle.textContent = title;
    toastMessage.textContent = message;
    
    // Set style based on type
    const toastHeader = toastEl.querySelector('.toast-header');
    toastHeader.className = 'toast-header';
    
    if (type === 'error') {
        toastHeader.classList.add('bg-danger', 'text-white');
    } else {
        toastHeader.classList.add('bg-success', 'text-white');
    }
    
    // Show toast
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}

// Wishlist functionality
function initWishlistButtons() {
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    
    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const icon = this.querySelector('i');
            const isLiked = icon.classList.contains('fas');
            
            if (isLiked) {
                icon.classList.remove('fas', 'fa-heart');
                icon.classList.add('far', 'fa-heart');
                showToast('Removed', 'Item removed from wishlist', 'success');
            } else {
                icon.classList.remove('far', 'fa-heart');
                icon.classList.add('fas', 'fa-heart');
                showToast('Added', 'Item added to wishlist', 'success');
            }
        });
    });
}

// Scroll animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe cards and sections
    const animateElements = document.querySelectorAll('.card, .hero-section h1, .hero-section p');
    animateElements.forEach(el => observer.observe(el));
}

// Navbar collapse on mobile
function initNavbarCollapse() {
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (navbarCollapse.classList.contains('show')) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                bsCollapse.hide();
            }
        });
    });
}

// Add to cart functionality
document.addEventListener('click', function(e) {
    if (e.target.matches('.btn:contains("Add to Cart"), .btn:contains("Add to Cart") *')) {
        e.preventDefault();
        const productCard = e.target.closest('.product-card');
        const productName = productCard.querySelector('.card-title').textContent;
        
        showToast('Added to Cart', `${productName} has been added to your cart.`, 'success');
        
        // Add loading effect
        const btn = e.target.closest('.btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
        btn.disabled = true;
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1000);
    }
});

// Store update and visit scheduling
document.addEventListener('click', function(e) {
    if (e.target.matches('.btn:contains("Get Store Updates"), .btn:contains("Get Store Updates") *')) {
        showToast('Subscribed!', 'You will receive updates about our store opening.', 'success');
    }
    
    if (e.target.matches('.btn:contains("Schedule Visit"), .btn:contains("Schedule Visit") *')) {
        showToast('Visit Scheduled', 'We will contact you to confirm your visit.', 'success');
    }
});

// Navbar background on scroll
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
    } else {
        navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
    }
});

// Category and product card click handlers
document.addEventListener('click', function(e) {
    const categoryCard = e.target.closest('.category-card');
    const productCard = e.target.closest('.product-card');
    
    if (categoryCard && !e.target.closest('.btn')) {
        const categoryName = categoryCard.querySelector('.card-title').textContent;
        showToast('Category Selected', `Viewing ${categoryName} collection`, 'success');
    }
});

// Utility function to check if element contains text
Element.prototype.matches = Element.prototype.matches || function(selector) {
    if (selector.includes(':contains(')) {
        const text = selector.match(/:contains\(([^)]+)\)/)[1].replace(/['"]/g, '');
        return this.textContent.includes(text);
    }
    return Element.prototype.matches.call(this, selector);
};