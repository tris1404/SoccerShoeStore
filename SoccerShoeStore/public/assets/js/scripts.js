const listImg = document.querySelector('.list-img');
const imgs = document.querySelectorAll('.list-img img');
let currentIndex = 0;

// Clone the first image and append it to the end of the list
const firstImgClone = imgs[0].cloneNode(true);
listImg.appendChild(firstImgClone);

listImg.style.transition = 'transform 0.5s ease-in-out';

setInterval(() => {
    currentIndex++;
    let width = imgs[0].clientWidth;
    listImg.style.transform = `translateX(${-width * currentIndex}px)`;

    if (currentIndex >= imgs.length) {
        setTimeout(() => {
            listImg.style.transition = 'none';
            listImg.style.transform = `translateX(0px)`;
            currentIndex = 0;
            setTimeout(() => {
                listImg.style.transition = 'transform 0.5s ease-in-out';
            }, 50);
        }, 500); // Match the transition duration
    }
}, 4000);