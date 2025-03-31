const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

function validateForm(event) {
    let isValid = true;

    // Xác định form đang submit (đăng nhập hay đăng ký)
    let form = event.target;
    let isSignUp = form.getAttribute("action") === "register.php";

    let role = form.querySelector("#role")?.value || "";
    let email = form.querySelector("input[name='email']").value.trim();
    let password = form.querySelector("input[name='password']").value.trim();
    let name = isSignUp ? form.querySelector("input[name='name']").value.trim() : "";

    let roleError = form.querySelector("#role-error");
    let emailError = form.querySelector("#email-error");
    let passwordError = form.querySelector("#password-error");
    let nameError = isSignUp ? form.querySelector("#name-error") : null;

    // Xóa thông báo lỗi cũ
    if (roleError) roleError.innerText = "";
    if (emailError) emailError.innerText = "";
    if (passwordError) passwordError.innerText = "";
    if (nameError) nameError.innerText = "";

    // Kiểm tra tên (chỉ khi đăng ký)
    if (isSignUp && nameError && name === "") {
        nameError.innerText = "Vui lòng nhập name!";
        isValid = false;
    }

    // Kiểm tra vai trò (nếu có)
    if (roleError && role === "") {
        roleError.innerText = "Vui lòng chọn vai trò!";
        isValid = false;
    }

    // Kiểm tra email
    if (email === "") {
        emailError.innerText = "Vui lòng nhập email!";
        isValid = false;
    }

    // Kiểm tra mật khẩu
    if (password === "") {
        passwordError.innerText = "Vui lòng nhập mật khẩu!";
        isValid = false;
    } else if (isSignUp) {
        const specialChars = /[!@#$%^&*(),.?":{}|<>]/;
        if (password.length < 8 || !/[A-Z]/.test(password) || !/[0-9]/.test(password) || !specialChars.test(password)) {
            passwordError.innerText = "Mật khẩu phải có ít nhất 8 ký tự, 1 chữ hoa, 1 số, 1 ký tự đặc biệt!";
            isValid = false;
        }
    }

    // Ngăn form gửi nếu có lỗi
    if (!isValid) {
        event.preventDefault();
    }

    return isValid;
}



function togglePassword() {
    let passwordInput = document.getElementById("password");
    let toggleIcon = document.getElementById("togglePassword");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash"); // Đổi thành mắt đóng
    } else {
        passwordInput.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye"); // Đổi thành mắt mở
    }
}