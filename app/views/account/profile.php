<?php declare(strict_types=1); ?>
<link rel="stylesheet" href="/public/css/home.css">

<div class="screen-container">
    <div class="account-body">
        <div class="account">
            <div class="account-header">
                <div class="container-img">
                    <div class="account-img" data-testid="user-image" draggable="false">
                        <div class="img" draggable="false">
                            <img aria-hidden="false" draggable="false" loading="eager" src="https://platform-lookaside.fbsbx.com/platform/profilepic/?asid=747093749386664&amp;height=300&amp;width=300&amp;ext=1733885219&amp;hash=AbYCHG_gZO52jPDHN7b4FDlN" alt="Vũ Thái Bình Dương" class="mMx2LUixlnN_Fu45JpFB CmkY1Ag0tJDfnFXbGgju _EShSNaBK1wUIaZQFJJQ ta4ePOlmGXjBYPTd90lh Yn2Ei5QZn19gria6LjZj" sizes="(min-width: 1280px) 232px, 192px">
                        </div>
                        <div class="container-btn">
                            <div class="btn-edit">
                                <button data-testid="edit-image-button" class="btn-edit-image" aria-haspopup="true" type="button">
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
                                <h1 data-encore-id="text" dir="auto" style="visibility: visible; width: 100%; font-size: 6rem; white-space: nowrap; text-wrap-style: balance; margin: 0">Vũ Thái Bình Dương</h1>
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
                        <form class="profile-form">
                            <div class="profile">
                                <div class="profile-edit">
                                    <label for="profile-username">
                                        <span>Tên đăng nhập</span>
                                    </label>
                                </div>
                                <input class="profile-input" id="profile-username" placeholder="Tên Đăng nhập" type="text" value="BDuong">
                            </div>
                            <div class="profile">
                                <div class="profile-edit">
                                    <label for="profile-mail">
                                        <span>Email</span>
                                    </label>
                                </div>
                                <input class="profile-input" id="profile-mail" placeholder="Email" type="text" value="2331540042@gmail.com">
                            </div>
                            <div class="profile">
                                <div class="profile-edit">
                                    <label for="profile-name">
                                        <span>Họ tên</span>
                                    </label>
                                </div>
                                <input class="profile-input" id="profile-name" placeholder="Họ tên" type="text" value="Vũ Thái Bình Dương">
                            </div>
                            <div class="profile">
                                <div class="profile-edit">
                                    <label class="encore-text encore-text-body-small-bold" data-encore-id="text" for="profile-birtday">
                                            <span>Ngày sinh<span>
                                    </label>
                                </div>
                                <div style="padding-block-start: 8px; padding-left: 0; padding-right:0; padding-bottom:0;">
                                    <div style="gap: 8px; display: flex">
                                        <input style="inline-size: 30%" aria-invalid="false" class="profile-input" data-encore-id="formInput" id="day" name="day" inputmode="numeric" pattern="\d*" maxlength="2" autocomplete="bday-day" placeholder="dd" required="" type="numeric" data-testid="birthDateDay" value="31" aria-errormessage="birthdate-error-invalid">                                
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
                                                <option disabled value>Tháng</option>
                                                <option value="1">Tháng 1</option>
                                                <option value="2">Tháng 2</option>
                                                <option value="3">Tháng 3</option>
                                                <option value="4">Tháng 4</option>
                                                <option value="5">Tháng 5</option>
                                                <option value="6">Tháng 6</option>
                                                <option value="7" selected="selected">Tháng 7</option>
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
                                        <input style="inline-size: 50%" aria-invalid="false" class="profile-input" data-encore-id="formInput" id="year" name="year" inputmode="numeric" maxlength="4" placeholder="năm" required="" type="numeric" autocomplete="bday-year" data-testid="birthDateYear" value="2005" aria-errormessage="birthdate-error-invalid">
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
                            <div class="button-profile">
                                <button>
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