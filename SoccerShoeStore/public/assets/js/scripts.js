const listImg = document.querySelector('.list-img');
const imgs = document.querySelectorAll('.list-img img');
const btnPrev = document.querySelector('.btn-left');
const btnNext = document.querySelector('.btn-right');
const indexItems = document.querySelectorAll('.index-item'); // Chỉ mục
let currentIndex = 0;
let autoSlide;

// Clone ảnh đầu để tạo hiệu ứng lặp vô tận
const firstImgClone = imgs[0].cloneNode(true);
listImg.appendChild(firstImgClone);

listImg.style.transition = 'transform 0.5s ease-in-out';

// Hàm cập nhật chỉ mục
function updateIndex() {
    indexItems.forEach((item, index) => {
        item.classList.toggle('active', index === currentIndex);
    });
}

// Hàm chuyển slide
function changeSlide(next = true) {
    if (next) {
        currentIndex++;
    } else {
        currentIndex--;
    }

    let width = imgs[0].clientWidth;
    listImg.style.transform = `translateX(${-width * currentIndex}px)`;

    // Khi đến ảnh cuối, reset về đầu
    if (currentIndex >= imgs.length) {
        setTimeout(() => {
            listImg.style.transition = 'none';
            listImg.style.transform = `translateX(0px)`;
            currentIndex = 0;
            updateIndex();
            setTimeout(() => {
                listImg.style.transition = 'transform 0.5s ease-in-out';
            }, 50);
        }, 500);
    }

    // Khi về ảnh đầu tiên, reset về cuối (nếu có nhiều ảnh)
    if (currentIndex < 0) {
        currentIndex = imgs.length - 1;
        listImg.style.transition = 'none';
        listImg.style.transform = `translateX(${-width * currentIndex}px)`;
        setTimeout(() => {
            listImg.style.transition = 'transform 0.5s ease-in-out';
        }, 50);
    }

    updateIndex();
}

// Tự động trượt slide mỗi 4 giây
function startAutoSlide() {
    autoSlide = setInterval(() => {
        changeSlide(true);
    }, 4000);
}

// Khi nhấn nút next
btnNext.addEventListener('click', () => {
    clearInterval(autoSlide);
    changeSlide(true);
    startAutoSlide();
});

// Khi nhấn nút prev
btnPrev.addEventListener('click', () => {
    clearInterval(autoSlide);
    changeSlide(false);
    startAutoSlide();
});

// Khi nhấn vào chỉ mục, chuyển đến ảnh tương ứng
indexItems.forEach((item, index) => {
    item.addEventListener('click', () => {
        clearInterval(autoSlide);
        currentIndex = index;
        let width = imgs[0].clientWidth;
        listImg.style.transform = `translateX(${-width * currentIndex}px)`;
        updateIndex();
        startAutoSlide();
    });
});

// Bắt đầu tự động chạy slide
startAutoSlide();
