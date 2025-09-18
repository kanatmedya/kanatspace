<div class="main-container min-h-screen text-black dark:text-white-dark">
    <div x-data="auth">
        <div class="absolute inset-0">
            <img src="assets/media/system/auth/bg-gradient.png" alt="image" class="h-full w-full object-cover" />
        </div>
        <div class="relative flex min-h-screen items-center justify-center px-6 py-10 sm:px-16">
            <img src="assets/media/system/auth/coming-soon-object1.png" alt="image" class="absolute left-0 top-1/2 h-full max-h-[893px] -translate-y-1/2" />
            <img src="assets/media/system/auth/coming-soon-object2.png" alt="image" class="absolute left-24 top-0 h-40 md:left-[30%]" />
            <img src="assets/media/system/auth/coming-soon-object3.png" alt="image" class="absolute right-0 top-0 h-[300px]" />
            <img src="assets/media/system/auth/polygon-object.svg" alt="image" class="absolute bottom-0 end-[28%]" />
            <div class="relative flex w-full max-w-[1502px] flex-col justify-between overflow-hidden rounded-md bg-white/60 backdrop-blur-lg dark:bg-black/50 lg:min-h-[758px] lg:flex-row lg:gap-10 xl:gap-0">
                <div class="relative hidden w-full items-center justify-center p-5 lg:inline-flex lg:max-w-[835px] xl:-ms-32 ltr:xl:skew-x-[14deg] rtl:xl:skew-x-[-14deg]">
                    <div class="absolute inset-y-0 w-8 from-primary/10 via-transparent to-transparent ltr:-right-10 ltr:bg-gradient-to-r rtl:-left-10 rtl:bg-gradient-to-l xl:w-16 ltr:xl:-right-20 rtl:xl:-left-20"></div>
                    <div class="ltr:xl:-skew-x-[14deg] rtl:xl:skew-x-[14deg]">
                        <a href="index.html" class="block w-48 lg:w-72 ms-10">
                            <img src="uploads/logo/logo-white.svg" alt="Logo" class="w-full" />
                        </a>
                        <div class="mt-24 hidden w-full max-w-[430px] lg:block">
                            <img src="uploads/logo/login.svg" alt="Cover Image" class="w-full" />
                        </div>
                    </div>
                </div>
                <div class="relative flex w-full flex-col items-center justify-center gap-6 px-4 pb-16 pt-6 sm:px-6 lg:max-w-[667px]">
                    <div class="flex w-full max-w-[440px] items-center gap-2 lg:absolute lg:end-6 lg:top-6 lg:max-w-full">
                        <a href="index.html" class="block w-8 lg:hidden">
                            <img src="uploads/logo/logo.svg" alt="Logo" class="w-full" />
                        </a>
                    </div>
                    <div class="w-full max-w-[440px] lg:mt-16">
                        <div class="mb-10">
                            <h1 class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl">GİRİŞ YAP</h1>
                            <p class="text-base font-bold leading-normal text-white-dark">Mail adresinizi ve parolanızı giriniz.</p>
                        </div>
                        <form class="space-y-5 dark:text-white" id="loginForm" method="POST">
                            <div>
                                <label for="Email">e-Mail</label>
                                <div class="relative text-white-dark">
                                    <input id="Email" type="email" name="email" placeholder="e-Mail'inizi Girin" class="form-input ps-10 placeholder:text-white-dark" />
                                    <span class="absolute start-4 top-1/2 -translate-y-1/2">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label for="Password">Şifre</label>
                                <div class="relative text-white-dark">
                                    <input id="Password" type="password" name="password" placeholder="Şifrenizi Girin" class="form-input ps-10 placeholder:text-white-dark" />
                                    <span class="absolute start-4 top-1/2 -translate-y-1/2">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="flex cursor-pointer items-center">
                                    <input type="checkbox" name="remember_me" class="form-checkbox bg-white dark:bg-black" />
                                    <span class="text-white-dark">Beni Hatırla</span>
                                </label>
                            </div>
                            <button type="submit" name="login" class="btn btn-gradient !mt-6 w-full border-0 uppercase shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]">
                                GİRİŞ
                            </button>
                        </form>
                        <div class="relative my-7 text-center md:mb-9">
                            <span class="absolute inset-x-0 top-1/2 h-px w-full -translate-y-1/2 bg-white-light dark:bg-white-dark"></span>
                            <span class="relative bg-white px-2 font-bold uppercase text-white-dark dark:bg-dark dark:text-white-light">or</span>
                        </div>
                        <div class="mb-10 md:mb-[60px]">
                            <ul class="flex justify-center gap-3.5">
                                <li>
                                    <a href="javascript:" class="inline-flex h-8 w-8 items-center justify-center rounded-full p-0 transition hover:scale-110" style="background: linear-gradient(135deg, rgba(239, 18, 98, 1) 0%, rgba(67, 97, 238, 1) 100%)">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:" class="inline-flex h-8 w-8 items-center justify-center rounded-full p-0 transition hover:scale-110" style="background: linear-gradient(135deg, rgba(239, 18, 98, 1) 0%, rgba(67, 97, 238, 1) 100%)">
                                        <i class="fab fa-google"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:" class="inline-flex h-8 w-8 items-center justify-center rounded-full p-0 transition hover:scale-110" style="background: linear-gradient(135deg, rgba(239, 18, 98, 1) 0%, rgba(67, 97, 238, 1) 100%)">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="text-center dark:text-white">
                            Hesabınız Yok Mu?
                            <a href="/signup" class="uppercase text-primary underline transition hover:text-black dark:hover:text-white">KAYIT OL</a>
                        </div>
                    </div>
                    <p class="absolute bottom-6 w-full text-center dark:text-white">
                        © <span id="footer-year">2024</span>. CyberSide All Rights Reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('pages/public/login_ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())  // Yanıtı JSON olarak ayrıştırmadan önce düz metin olarak alın
        .then(text => {
            try {
                const data = JSON.parse(text);  // Yanıtı JSON olarak ayrıştırın
                if (data.success) {
                    console.log('Giriş başarılı.');
                    window.location.href = './';
                } else {
                    console.error('Hata:', data.message);
                    alert(data.message);
                }
            } catch (error) {
                console.error('JSON ayrıştırma hatası:', error);
                console.error('Yanıt metni:', text);  // Yanıt metnini konsola yazdır
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            }
        })
        .catch((error) => {
            console.error('Hata:', error);
            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
        });
    });
});
</script>

