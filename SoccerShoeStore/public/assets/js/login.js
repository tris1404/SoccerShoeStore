const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

// Xử lý đăng nhập
document.querySelector("form").addEventListener("submit", async function(event) {
    event.preventDefault(); // Ngăn chặn reload trang

    let email = document.querySelector('input[type="email"]').value.trim();
    let password = document.querySelector('input[type="password"]').value.trim();

    if (!email || !password) {
        alert("Vui lòng nhập email và mật khẩu!");
        return;
    }

    try {
        let response = await fetch("login.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email, password })
        });

        // Kiểm tra nếu response không thành công (lỗi 500, 403, ...)
        if (!response.ok) {
            throw new Error(`Lỗi HTTP: ${response.status}`);
        }

        let data = await response.json();
        console.log("Phản hồi từ server:", data); // 🛠 Kiểm tra phản hồi

        if (data.success) {
            let redirectURL = data.role === "admin" ? "admin.php" : "index.php";
            console.log("Chuyển hướng tới:", redirectURL);
            
            // Chuyển hướng an toàn
            window.location.replace(redirectURL);
        } else {
            alert("Sai thông tin đăng nhập!");
        }
    } catch (error) {
        console.error("Lỗi:", error);
        alert("Đã xảy ra lỗi, vui lòng thử lại!");
    }
});


// Xử lý đăng ký
document.getElementById("signup-btn").addEventListener("click", async function () {
    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let role = document.getElementById("role").value; 

    if (!name || !email || !password) {
        alert("Vui lòng nhập đầy đủ thông tin!");
        return;
    }

    let formData = new FormData();
    formData.append("name", name);
    formData.append("email", email);
    formData.append("password", password);
    formData.append("role", role);

    try {
        let response = await fetch("signup.php", {
            method: "POST",
            body: formData
        });

        let data = await response.text();
        alert(data); // Hiển thị phản hồi từ server
    } catch (error) {
        console.error("Lỗi:", error);
        alert("Đã xảy ra lỗi, vui lòng thử lại!");
    }
});
