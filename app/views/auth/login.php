<?php declare(strict_types=1); 
ini_set('session.save_path', '/home/weyyhewu/tmp');
session_start();
// include_once '../../controllers/AuthController.php';
// $authController = new AuthController();
// $data = json_decode(file_get_contents("php://input"), true) ?? $_POST;

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $response  = $authController->login($data);
//     if ($response['status'] === '00') {
//         $_SESSION['user_id'] = $response['data']['id'];
//         header('location: home');
//         exit();
//     }
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - BASO Music</title>
    <link rel="icon" href="/public/images/logo.jpg">
    <link rel="stylesheet" href="/public/css/login.css">
</head>

<body>
    <div class="container">
        <div class="theme_login">
            <div class="container_login">
                <div class="box">
                    <header>
                        <img class="logo" src="/public/images/logo.jpg" alt="logo">
                        <h1 class="login_text">Đăng nhập vào BASO Music</h1>
                    </header>
                    <form method="post" class="login_form" onsubmit="login(event)">
                        <div class="login">
                            <div class="text_login">
                                <label for="login-identifier">
                                    <span>Email hoặc tên người dùng</span>
                                </label>
                            </div>
                            <input name="identifier" class="login_input" id="login-identifier" placeholder="Email hoặc tên người dùng"
                                type="text">
                        </div>
                        <div class="login">
                            <div class="text_login">
                                <label for="login-password">
                                    <span>Mật khẩu</span>
                                </label>
                            </div>
                            <div class="container_password">
                                <input name="password" class="login_input" id="login-password" placeholder="Mật khẩu" type="password">
                                <div class="show_hide_container">
                                    <div class="show_hide_button">
                                        <span>
                                            <i id="toggler" class="hide_password_icon"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p id="result" style="color: red;"></p>
                        <div class="button_login">
                            <button type="submit">
                                <span class="button_inner">
                                    <span>Đăng nhập</span>
                                </span>
                            </button>
                        </div>
                    </form>
                    <div>
                        <div class="forgot_password">
                            <a href="#">
                                <p>Quên mật khẩu?</p>
                            </a>
                        </div>
                        <div class="sign_up_section">
                            <h2>
                                <span>Bạn chưa có tài khoản?</span>
                                <a href="register">
                                    <span>Đăng ký BASO Music</span>
                                </a>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    //show and hide password
    var togglerPassword = document.getElementById('toggler');
    console.log(togglerPassword);
    var passwordInput = document.getElementById('login-password');

    togglerPassword.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglerPassword.classList.add("show_password_icon");
            togglerPassword.classList.remove("hide_password_icon");
        } else if (passwordInput.type === 'text') {
            passwordInput.type = 'password';
            togglerPassword.classList.add("hide_password_icon");
            togglerPassword.classList.remove("show_password_icon");
        }
    })
    function login(e) {
        // Ngăn chặn form gửi yêu cầu mặc định
        e.preventDefault();

        // Lấy giá trị từ các input
        const identifier = document.getElementById('login-identifier').value.trim();
        const password = document.getElementById('login-password').value.trim();

        // Kiểm tra nếu thiếu thông tin
        if (!identifier || !password) {
            showResult('Vui lòng điền đầy đủ thông tin!', 'red');
            return;
        }

        // Dữ liệu gửi đi
        const data = {
            identifier: identifier,
            password: password,
        };

        // Gửi yêu cầu tới API
        fetch('api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            // Kiểm tra phản hồi từ API
            if (result.status === '00') {
                if(result.user.role_id === 0){
                    window.location.href = 'config/spotify.php';
                }
                if (result.user.role_id === 1){
                    window.location.href = 'config/spotify.php';
                }
            } else {
                showResult(result.message || 'Đăng nhập thất bại', 'red');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showResult('Lỗi kết nối. Vui lòng thử lại sau!', 'red');
        });
    }

        // Hàm hiển thị thông báo trong phần tử <p id="result">
    function showResult(message, color) {
        const resultElement = document.getElementById('result');
        resultElement.textContent = message;
        resultElement.style.color = color;
        resultElement.style.display = 'block';
    }

    </script>
</body>

</html>