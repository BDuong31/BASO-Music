<?php declare(strict_types=1); 
list($year, $month, $day) = explode('-', $_SESSION['user']['birthday']);

?>
<link rel="stylesheet" href="/public/css/home.css">

<div class="screen-container">
    <div class="account-body">
        <div class="account">
            <div class="account-header">
                <div class="container-img">
                    <div class="account-img" data-testid="user-image" draggable="false">
                        <div class="img" draggable="false">
                            <img id="avatar" src="<?php echo $_SESSION['user']['img'] ?>" sizes="(min-width: 1280px) 232px, 192px">
                        </div>
                        <div class="container-btn">
                            <div class="btn-edit">
                            <input type="file" id="image-upload" accept="image/*" style="display:none" onchange="uploadImage(event)">
                            <button onclick="document.getElementById('image-upload').click()" class="btn-edit-image" type="button">
                                <div class="icon-edit">
                                        <svg data-encore-id="icon" role="img" aria-hidden="true" viewBox="0 0 24 24" class="svg-edit">
                                            <path d="M17.318 1.975a3.329 3.329 0 1 1 4.707 4.707L8.451 20.256c-.49.49-1.082.867-1.735 1.103L2.34 22.94a1 1 0 0 1-1.28-1.28l1.581-4.376a4.726 4.726 0 0 1 1.103-1.735L17.318 1.975zm3.293 1.414a1.329 1.329 0 0 0-1.88 0L5.159 16.963c-.283.283-.5.624-.636 1l-.857 2.372 2.371-.857a2.726 2.726 0 0 0 1.001-.636L20.611 5.268a1.329 1.329 0 0 0 0-1.879z"></path>
                                        </svg>
                                        <span class="text-edit" data-encore-id="text">Choose photo</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-name">
                    <span class="text-title-profile" data-encore-id="text">Profile</span>
                    <span dir="auto" class="text-profile-name" draggable="false" data-testid="entityTitle">
                        <button class="btn-profile-name" title="Edit details">
                            <span>
                                <h1 style="visibility: visible; width: 100%; font-size: 6rem; white-space: nowrap; text-wrap-style: balance; margin: 0"><?php echo $_SESSION['user']['fullname'] ?></h1>
                            </span>
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="account-info">
            <div class="profile-container">
                <article class="profile-article">
                    <section>
                        <form method="post" class="profile-form" onsubmit="updateProfile(event)">
                            <div class="profile">
                                <div class="profile-edit">
                                    <label for="profile-username">
                                        <span>Tên đăng nhập</span>
                                    </label>
                                </div>
                                <input class="profile-input" id="profile-username" placeholder="Tên Đăng nhập" type="text" value="<?php echo $_SESSION['user']['username'] ?>">
                            </div>
                            <div class="profile">
                                <div class="profile-edit">
                                    <label for="profile-mail">
                                        <span>Email</span>
                                    </label>
                                </div>
                                <input class="profile-input" id="profile-mail" placeholder="Email" type="text" value="<?php echo $_SESSION['user']['email'] ?>">
                            </div>
                            <div class="profile">
                                <div class="profile-edit">
                                    <label for="profile-name">
                                        <span>Họ tên</span>
                                    </label>
                                </div>
                                <input class="profile-input" id="profile-name" placeholder="Họ tên" type="text" value="<?php echo $_SESSION['user']['fullname'] ?>">
                            </div>
                            <div class="profile">
                                <div class="profile-edit">
                                    <label class="encore-text encore-text-body-small-bold" data-encore-id="text" for="profile-birtday">
                                            <span>Ngày sinh<span>
                                    </label>
                                </div>
                                <div style="padding-block-start: 8px; padding-left: 0; padding-right:0; padding-bottom:0;">
                                    <div style="gap: 8px; display: flex">
                                        <input style="inline-size: 30%" aria-invalid="false" class="profile-input" data-encore-id="formInput" id="day" name="day" inputmode="numeric" pattern="\d*" maxlength="2" autocomplete="bday-day" placeholder="dd" required="" type="numeric" data-testid="birthDateDay" value="<?php echo $day ?>" aria-errormessage="birthdate-error-invalid">                                
                                        <div style="box-sizing: border-box; -webkit-tap-highlight-color: transparent; position: relative; inline-size: 100%; display: flex; -webkit-box-align: center; align-items: center;">
                                            <select class="profile-input" aria-invalid="false" id="month" name="month" required="" autocomplete="bday-month" data-testid="birthDateMonth" aria-errormessage="birthdate-error-invalid"
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
                                                <?php for($i=1; $i <=12; $i+=1){ ?>
                                                    <option value="<?php echo $i ?>" <?php echo ($i == (int)$month) ? 'selected' : '' ?>>
                                                        Tháng <?php echo $i ?>
                                                    </option>
                                                <?php } ?>
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
                                        <input style="inline-size: 50%" aria-invalid="false" class="profile-input" data-encore-id="formInput" id="year" name="year" inputmode="numeric" maxlength="4" placeholder="năm" required="" type="numeric" autocomplete="bday-year" data-testid="birthDateYear" value="<?php echo $year ?>" aria-errormessage="birthdate-error-invalid">
                                    </div>
                                </div>
                            </div>
                            <div class="profile">
                                <div class="profile-edit">
                                    <label class="encore-text encore-text-body-small-bold" data-encore-id="text" for="resigter-gender">
                                            <span>Giới tính<span>
                                    </label>
                                </div>
                                <div style="padding-block-start: 8px; padding-left: 0; padding-right:0; padding-bottom:0;">
                                    <div style="gap: 8px; display: flex">
                                        <div style="box-sizing: border-box; -webkit-tap-highlight-color: transparent; position: relative; inline-size: 100%; display: flex; -webkit-box-align: center; align-items: center;">
                                            <select class="profile-input" aria-invalid="false" id="gender" name="gender" required="" autocomplete="bday-month" data-testid="birthDateMonth" aria-errormessage="birthdate-error-invalid"
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
                                                <option disabled value>Giới tính</option>
                                                <option <?php echo $_SESSION['user']['sex'] == '1' ? 'selected' : '' ?> value="1">Nam</option>
                                                <option <?php echo $_SESSION['user']['sex'] == '2' ? 'selected' : '' ?> value="2">Nữ</option>
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
                            <div class="button-profile">
                                <button type="submit">
                                    <span class="button_inner">
                                        <span>Lưu thông tin</span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </section>
                </article>
            </div>
        </div>
    </div>
</div>
<script>
    function updateProfile(e) {
        // Ngăn chặn form gửi yêu cầu mặc định
        e.preventDefault();

        // Lấy giá trị từ các input
        const username = document.getElementById('profile-username').value.trim();
        const email = document.getElementById('profile-mail').value.trim();
        const day = document.getElementById('day').value.trim();
        const month = document.getElementById('month').value.trim();
        const year = document.getElementById('year').value.trim();
        const fullname = document.getElementById('profile-name').value.trim();
        const gender = document.getElementById('gender').value;

        // Định dạng ngày sinh
        const birthday = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;

        // Kiểm tra nếu thiếu thông tin
        if (!username || !email || !fullname || !day || !month || !year || !gender) {
            showResult('Vui lòng điền đầy đủ thông tin!', 'red');
            return;
        }

        // Dữ liệu gửi đi
        const data = {
            id: <?php echo $_SESSION['user']['id']?>,
            username: username,
            email: email,
            birthday: birthday,
            fullname: fullname,
            sex: gender
        };

        // Gửi yêu cầu tới API
        fetch('http://basomusic.local//api/update-profile', {
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
                    window.location.href = '/home/account';
            } else {
                showResult(result.message || 'Cập nhật thất bại', 'red');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showResult('Lỗi kết nối. Vui lòng thử lại sau!', 'red');
        });
    }
    
    function uploadImage(event) {
        const file = event.target.files[0];
        if (!file) {
            showResult('Vui lòng chọn một hình ảnh!', 'red');
            return;
        }

        // Xem trước hình ảnh trước khi tải lên
        const reader = new FileReader();
        reader.onload = function () {
            const imgElement = document.getElementById('profile-image');
            imgElement.src = reader.result;
        };
        reader.readAsDataURL(file);

        // Tạo FormData để gửi file lên server
        const formData = new FormData();
        formData.append('avatar', file);
        formData.append('id', '<?php echo $_SESSION['user']['id']; ?>');

        // Gửi yêu cầu tải lên ngay lập tức
        fetch('http://basomusic.local/api/update-avatar', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === '00') {
                window.location.href = '/home/account'
            } else {
                showResult(result.message || 'Cập nhật hình ảnh thất bại', 'red');
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