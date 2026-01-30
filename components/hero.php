<section class="hero-section">
    <div class="hero-slider">
        <div class="slider-wrapper" id="sliderWrapper">
            <!-- Slide 1 -->
            <div class="slide">
                <picture>
                    <source media="(max-width: 768px)" srcset="assets/banners/bio-metric-banners.png">
                    <img src="assets/banners/biometric-security.webp" alt="Bio Metric Solutions">
                </picture>
            </div>
            <!-- Slide 2 -->
            <div class="slide">
                <picture>
                    <source media="(max-width: 768px)" srcset="assets/banners/hk-vision-cctv-banners.jpg">
                    <img src="assets/banners/hk-vision-cctv-banners.jpg" alt="Hikvision CCTV Camera">
                </picture>
            </div>
        </div>
        
        <!-- Navigation Buttons -->
        <button class="slider-btn prev-btn" onclick="moveSlide(-1)"><i class="fas fa-chevron-left"></i></button>
        <button class="slider-btn next-btn" onclick="moveSlide(1)"><i class="fas fa-chevron-right"></i></button>

        <!-- Dots -->
        <div class="slider-dots" id="sliderDots"></div>
    </div>
</section>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const wrapper = document.getElementById('sliderWrapper');
    const dotsContainer = document.getElementById('sliderDots');
    const totalSlides = slides.length;
    let slideInterval;

    // Create Dots
    slides.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (index === 0) dot.classList.add('active');
        dot.onclick = () => goToSlide(index);
        dotsContainer.appendChild(dot);
    });

    const dots = document.querySelectorAll('.dot');

    function updateSlider() {
        wrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
        
        dots.forEach(dot => dot.classList.remove('active'));
        dots[currentSlide].classList.add('active');
    }

    function moveSlide(step) {
        currentSlide = (currentSlide + step + totalSlides) % totalSlides;
        updateSlider();
        resetTimer();
    }

    function goToSlide(index) {
        currentSlide = index;
        updateSlider();
        resetTimer();
    }

    function startTimer() {
        slideInterval = setInterval(() => {
            moveSlide(1);
        }, 5000); // 5 seconds auto slide
    }

    function resetTimer() {
        clearInterval(slideInterval);
        startTimer();
    }

    // Initialize
    startTimer();
</script>
