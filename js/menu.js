// JavaScript for responsive menu toggle
const menuToggle = document.querySelector('.menu-toggle');
const menuNav = document.querySelector('.menu-nav');

menuToggle.addEventListener('click', () => {
  menuNav.classList.toggle('active');
});

// JavaScript for adding active state to menu items
const menuLinks = document.querySelectorAll('.menu-nav a');

menuLinks.forEach((link) => {
  link.addEventListener('click', () => {
    menuLinks.forEach((otherLink) => {
      otherLink.classList.remove('active');
    });
    link.classList.add('active');
  });
});
