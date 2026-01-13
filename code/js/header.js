const burgerBtn = document.getElementById('burgerBtn');
const burgerMenu = document.getElementById('sideMenu');
const userBtn = document.getElementById('userBtn');
const userMenu = document.getElementById('userMenu');
const overlay = document.getElementById('overlay'); 

burgerBtn.addEventListener('click', () => {
    burgerMenu.classList.toggle('open');
    overlay.classList.toggle('visible');
});

userBtn.addEventListener('click', () => {
    userMenu.classList.toggle('open');
    overlay.classList.toggle('visible');
});

overlay.addEventListener('click', () => {
    burgerMenu.classList.remove('open');
    userMenu.classList.remove('open');
    overlay.classList.remove('visible');
});