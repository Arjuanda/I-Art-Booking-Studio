<div {{ $attributes }}>
    <div id="bg1" class="absolute bg-cover bg-center bg-no-repeat inset-0  transition-opacity duration-1000"></div>
    <div id="bg2" class="absolute bg-cover bg-center bg-no-repeat inset-0  transition-opacity duration-1000 opacity-0"></div>

</div>
<script>
    const images = [
        "/storage/images/bg1.jpg",
        "/storage/images/bg2.jpg",
        "/storage/images/bg3.jpg",
        "/storage/images/bg4.jpg",
        "/storage/images/post.jpg",
        "/storage/images/top.jpg",
    ];

    let index = 0;
    let current = 0;
    
    const bg1 = document.getElementById("bg1");
    const bg2 = document.getElementById("bg2");

    bg1.style.backgroundImage = `url(${images[0]})`;
    
    function changeBackground() {
        index = (index + 1) % images.length;
        
        if (current === 0) {
            bg2.style.backgroundImage = `url(${images[index]})`;
            bg2.style.opacity = 1;
            bg1.style.opacity = 0;
            current = 1;
        } else {
            bg1.style.backgroundImage = `url(${images[index]})`;
            bg1.style.opacity = 1;
            bg2.style.opacity = 0;
            current = 0;
        }
    }
    setInterval(changeBackground, 5000);
</script>