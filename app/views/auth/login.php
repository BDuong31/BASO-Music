<?php declare(strict_types=1); ?>
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
                    <form class="login_form">
                        <div class="login">
                            <div class="text_login">
                                <label for="login-username">
                                    <span>Email hoặc tên người dùng</span>
                                </label>
                            </div>
                            <input class="login_input" id="login-username" placeholder="Email hoặc tên người dùng"
                                type="text">
                        </div>
                        <div class="login">
                            <div class="text_login">
                                <label for="login-password">
                                    <span>Mật khẩu</span>
                                </label>
                            </div>
                            <div class="container_password">
                                <input class="login_input" id="login-password" placeholder="Mật khẩu" type="password">
                                <div class="show_hide_container">
                                    <div class="show_hide_button">
                                        <span>
                                            <i id="toggler" class="hide_password_icon"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button_login">
                            <button>
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
                                <a href="signup.html">
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
    </script>
</body>

</html>