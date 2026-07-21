// assets/js/main.js

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Toggler
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            const icon = mobileMenuBtn.querySelector('svg');
            if (icon) {
                // toggle icon states if needed
            }
        });
    }

    // 2. Change Header Background on Scroll
    const header = document.querySelector('header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('shadow-md');
                header.classList.remove('border-transparent');
            } else {
                header.classList.remove('shadow-md');
                header.classList.add('border-transparent');
            }
        });
    }

    // 3. Menu Category Filtering (For client-side UI if using unified list, or redirection)
    const filterButtons = document.querySelectorAll('.filter-btn');
    const menuItems = document.querySelectorAll('.menu-item');

    if (filterButtons.length > 0 && menuItems.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // remove active classes
                filterButtons.forEach(btn => {
                    btn.classList.remove('filter-active', 'bg-burgundy', 'text-white');
                    btn.classList.add('bg-white', 'text-burgundy');
                });

                // add active class to clicked button
                button.classList.add('filter-active', 'bg-burgundy', 'text-white');
                button.classList.remove('bg-white', 'text-burgundy');

                const category = button.getAttribute('data-category');

                // filter items
                menuItems.forEach(item => {
                    if (category === 'all' || item.getAttribute('data-category') === category) {
                        item.classList.remove('hidden');
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
                        }, 50);
                    } else {
                        item.style.opacity = '0';
                        item.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            item.classList.add('hidden');
                        }, 300);
                    }
                });
            });
        });
    }
});
