<?php declare(strict_types=1); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - BASO Music</title>
    <link rel="icon" href="/public/images/logo.jpg">
    <link rel="stylesheet" href="/public/css/resigter.css">
</head>

<body>
    <div class="container">
        <div class="theme_resigter">
            <div class="container_resgiter">
                <div class="box">
                    <header>
                        <img class="logo" src="/public/images/logo.jpg" alt="logo">
                        <h1 class="resigter_text">Đăng ký để bắt đầu nghe</h1>
                    </header>
                    <form method="post" class="resigter_form" onsubmit="register(event)">
                        <div class="resigter">
                            <div class="resigter_login">
                                <label for="resigter-username">
                                    <span>Tên đăng nhập</span>
                                </label>
                            </div>
                            <input name="username" class="resigter_input" id="resigter-username" placeholder="Tên Đăng nhập"
                                type="text">
                        </div>
                        <div class="resigter">
                            <div class="resigter_login">
                                <label for="resigter-mail">
                                    <span>Email</span>
                                </label>
                            </div>
                            <input name="email" class="resigter_input" id="resigter-mail" placeholder="Email"
                                type="text">
                        </div>
                        <div class="resigter">
                            <div class="text_resigter">
                                <label for="resigter-password">
                                    <span>Mật khẩu</span>
                                </label>
                            </div>
                            <div class="container_password">
                                <input name="password" class="resigter_input" id="resigter-password" placeholder="Mật khẩu" type="password">
                                <div class="show_hide_container">
                                    <div class="show_hide_button">
                                        <span>
                                            <i id="toggler" class="hide_password_icon"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="resigter">
                            <div class="resigter_login">
                                <label for="resigter-name">
                                    <span>Họ tên</span>
                                </label>
                            </div>
                            <input name="fullname" class="resigter_input" id="resigter-name" placeholder="Họ tên"
                                type="text">
                        </div>
                        <div class="resigter">
                            <div class="resigter_login">
                                <label class="encore-text encore-text-body-small-bold" data-encore-id="text" for="resigter-birtday">
                                        <span>Ngày sinh<span>
                                </label>
                            </div>
                            <div style="padding-block-start: 8px; padding-left: 0; padding-right:0; padding-bottom:0;">
                                <div style="gap: 8px; display: flex">
                                    <input style="inline-size: 30%" aria-invalid="false" class="resigter_input" data-encore-id="formInput" id="day" name="day" inputmode="numeric" pattern="\d*" maxlength="2" autocomplete="bday-day" placeholder="dd" required="" type="numeric" data-testid="birthDateDay" value="" aria-errormessage="birthdate-error-invalid">                                
                                    <div style="box-sizing: border-box; -webkit-tap-highlight-color: transparent; position: relative; inline-size: 100%; display: flex; -webkit-box-align: center; align-items: center;">
                                        <select class="resigter_input" aria-invalid="false" id="month" name="month" required="" autocomplete="bday-month" data-testid="birthDateMonth" aria-errormessage="birthdate-error-invalid"
                                            style="    
                                                    appearance: none;
                                                    margin-block: 0px;
                                                    text-indent: 0.01px;
                                                    block-size: var(--encore-control-size-base, 48px);
                                                    min-block-size: unset;
                                                    text-align: start;
                                                    overflow-wrap: break-word;
                                                    min-block-size: 48px"    
                                        >
                                            <option selected disabled value>Tháng</options>
                                            <option value="1">Tháng 1</option>
                                            <option value="2">Tháng 2</option>
                                            <option value="3">Tháng 3</option>
                                            <option value="4">Tháng 4</option>
                                            <option value="5">Tháng 5</option>
                                            <option value="6">Tháng 6</option>
                                            <option value="7">Tháng 7</option>
                                            <option value="8">Tháng 8</option>
                                            <option value="9">Tháng 9</option>
                                            <option value="10">Tháng 10</option>
                                            <option value="11">Tháng 11</option>
                                            <option value="12">Tháng 12</option>
                                        </select>
                                        <svg data-encore-id="icon" role="img" aria-hidden="true" class="Svg-sc-ytk21e-0 ZujEm Arrow-sc-1slzr1b-0 eOFMoh" viewBox="0 0 24 24"
                                            style="    
                                                    fill: currentcolor;
                                                    width: var(--encore-graphic-size-decorative-base, 24px);
                                                    height: var(--encore-graphic-size-decorative-base, 24px);
                                                    color: var(--essential-subdued, #818181);
                                                    pointer-events: none;
                                                    position: absolute;
                                                    right: var(--encore-spacing-tighter, 12px);"
                                        >
                                            <path d="M2.793 8.043a1 1 0 0 1 1.414 0L12 15.836l7.793-7.793a1 1 0 1 1 1.414 1.414L12 18.664 2.793 9.457a1 1 0 0 1 0-1.414z"></path>
                                        </svg>
                                    </div>
                                    <input style="inline-size: 50%" aria-invalid="false" class="resigter_input" data-encore-id="formInput" id="year" name="year" inputmode="numeric" maxlength="4" placeholder="năm" required="" type="numeric" autocomplete="bday-year" data-testid="birthDateYear" value="" aria-errormessage="birthdate-error-invalid">
                                </div>
                            </div>
                        </div>
                        <div class="resigter">
                            <div class="resigter_login">
                                <label class="encore-text encore-text-body-small-bold" data-encore-id="text" for="resigter-gender">
                                        <span>Giới tính<span>
                                </label>
                            </div>
                            <div style="padding-block-start: 8px; padding-left: 0; padding-right:0; padding-bottom:0;">
                                <div style="gap: 8px; display: flex">
                                    <div style="box-sizing: border-box; -webkit-tap-highlight-color: transparent; position: relative; inline-size: 100%; display: flex; -webkit-box-align: center; align-items: center;">
                                        <select class="resigter_input" aria-invalid="false" id="gender" name="gender" required="" autocomplete="bday-month" data-testid="birthDateMonth" aria-errormessage="birthdate-error-invalid"
                                            style="    
                                                    appearance: none;
                                                    margin-block: 0px;
                                                    text-indent: 0.01px;
                                                    block-size: var(--encore-control-size-base, 48px);
                                                    min-block-size: unset;
                                                    text-align: start;
                                                    overflow-wrap: break-word;
                                                    min-block-size: 48px"    
                                        >
                                            <option selected disabled value>Giới tính</option>
                                            <option value="1">Nam</option>
                                            <option value="2">Nữ</option>
                                        </select>
                                        <svg data-encore-id="icon" role="img" aria-hidden="true" class="Svg-sc-ytk21e-0 ZujEm Arrow-sc-1slzr1b-0 eOFMoh" viewBox="0 0 24 24"
                                            style="    
                                                    fill: currentcolor;
                                                    width: var(--encore-graphic-size-decorative-base, 24px);
                                                    height: var(--encore-graphic-size-decorative-base, 24px);
                                                    color: var(--essential-subdued, #818181);
                                                    pointer-events: none;
                                                    position: absolute;
                                                    right: var(--encore-spacing-tighter, 12px);"
                                        >
                                            <path d="M2.793 8.043a1 1 0 0 1 1.414 0L12 15.836l7.793-7.793a1 1 0 1 1 1.414 1.414L12 18.664 2.793 9.457a1 1 0 0 1 0-1.414z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p id="result" style="color: red;"></p>
                        <div class="button_resigter">
                            <button type="submit">
                                <span class="button_inner">
                                    <span>Đăng ký</span>
                                </span>
                            </button>
                        </div>
                    </form>
                    <div>
                        <div class="sign_up_section">
                            <h2>
                                <span>Bạn đã có tài khoản?</span>
                                <a href="login">
                                    <span>Đăng nhập BASO Music</span>
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
    var passwordInput = document.getElementById('resigter-password');

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
    function register(e) {
        // Ngăn chặn form gửi yêu cầu mặc định
        e.preventDefault();

        // Lấy giá trị từ các input
        const username = document.getElementById('resigter-username').value.trim();
        const password = document.getElementById('resigter-password').value.trim();
        const email = document.getElementById('resigter-mail').value.trim();
        const day = document.getElementById('day').value.trim();
        const month = document.getElementById('month').value.trim();
        const year = document.getElementById('year').value.trim();
        const fullname = document.getElementById('resigter-name').value.trim();
        const gender = document.getElementById('gender').value;

        // Định dạng ngày sinh
        const birthday = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;

        // Kiểm tra nếu thiếu thông tin
        if (!username || !password || !email || !fullname || !day || !month || !year || !gender) {
            showResult('Vui lòng điền đầy đủ thông tin!', 'red');
            return;
        }

        // Dữ liệu gửi đi
        const data = {
            username: username,
            password: password,
            email: email,
            birthday: birthday,
            fullname: fullname,
            gender: gender
        };

        // Gửi yêu cầu tới API
        fetch('api/register', {
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
                    window.location.href = 'login';
            } else {
                showResult(result.message || 'Đăng ký thất bại', 'red');
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