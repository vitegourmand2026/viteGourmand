const timeBtn = document.getElementById('timeBtn');
const timeMenu = document.getElementById('timeMenu');
const overlay = document.getElementById('overlay');


timeBtn.addEventListener('click', () => {
    timeMenu.classList.toggle('open');
    overlay.classList.toggle('visible');
});

overlay.addEventListener('click', () => {
timeMenu.classList.remove('open');
overlay.classList.remove('visible');

})