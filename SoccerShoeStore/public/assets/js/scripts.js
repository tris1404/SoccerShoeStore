const listImg = document.querySelector('.list-img');
const imgs = document.querySelectorAll('.list-img a img');
const btnPrev = document.querySelector('.btn-left');
const btnNext = document.querySelector('.btn-right');
const indexItems = document.querySelectorAll('.index-item');
let currentIndex = 0;
let autoSlide;

const firstImgClone = document.querySelector('.list-img a').cloneNode(true);
listImg.appendChild(firstImgClone);

listImg.style.transition = 'transform 0.5s ease-in-out';

function updateIndex() {
    indexItems.forEach((item, index) => {
        item.classList.toggle('active', index === currentIndex);
    });
}

function changeSlide(next = true) {
    if (next) {
        currentIndex++;
    } else {
        currentIndex--;
    }

    let width = document.querySelector('.list-img a').clientWidth;
    listImg.style.transform = `translateX(${-width * currentIndex}px)`;

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

function startAutoSlide() {
    autoSlide = setInterval(() => {
        changeSlide(true);
    }, 4000);
}

btnNext.addEventListener('click', () => {
    clearInterval(autoSlide);
    changeSlide(true);
    startAutoSlide();
});

btnPrev.addEventListener('click', () => {
    clearInterval(autoSlide);
    changeSlide(false);
    startAutoSlide();
});

indexItems.forEach((item, index) => {
    item.addEventListener('click', () => {
        clearInterval(autoSlide);
        currentIndex = index;
        let width = document.querySelector('.list-img a').clientWidth;
        listImg.style.transform = `translateX(${-width * currentIndex}px)`;
        updateIndex();
        startAutoSlide();
    });
});

startAutoSlide();

// ?Nút quay lại trang đầu
document.addEventListener("DOMContentLoaded", function () {
    let backToTopButton = document.getElementById("backToTop");

    // Ẩn/hiện nút khi cuộn trang
    window.addEventListener("scroll", function () {
        if (window.scrollY > 300) {
            backToTopButton.style.display = "flex";
        } else {
            backToTopButton.style.display = "none";
        }
    });

    // Xử lý sự kiện khi bấm vào nút
    backToTopButton.addEventListener("click", function () {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
});


// icon trái tim yêu thích
document.querySelectorAll(".favorite-btn").forEach(button => {
    button.addEventListener("click", function(event) {
        event.preventDefault();
        this.classList.toggle("active");
        this.innerHTML = this.classList.contains("active") 
            ? '<i class="fa-solid fa-heart"></i>' 
            : '<i class="fa-regular fa-heart"></i>';
    });
});

// Chuyển hướng đến giay_san_tu_nhien.php khi nhấn nút "Xem thêm" trong phần "Giày sân cỏ tự nhiên"
document.getElementById('btn-xem-them-tu-nhien').addEventListener('click', function() {
    window.location.href = 'giay_san_tu_nhien.php';
});

// Chuyển hướng đến giay_hot.php khi nhấn nút "Xem thêm" trong phần "Sản Phẩm Hot"
document.getElementById('btn-xem-them-hot').addEventListener('click', function() {
    window.location.href = 'giay_hot.php';
});

