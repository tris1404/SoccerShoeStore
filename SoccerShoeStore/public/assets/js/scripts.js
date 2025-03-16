const listImg = document.querySelector('.list-img');
const imgs = document.querySelectorAll('.list-img img');
let currentIndex = 0;

setInterval(() => {
    currentIndex = (currentIndex + 1) % imgs.length;
    let width = imgs[0].clientWidth;
    listImg.style.transform = `translateX(${-width * currentIndex}px)`;
}, 4000);