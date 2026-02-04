<div class="box-header with-border text-center bg-blue">
    <h2 class="box-title text-bold">Media Terkait</h2>
</div>

<div id="media-terkait-widget">
    <div class="marquee-wrapper">
        <div class="marquee-content">
            <ul class="marquee-track">
                <li>
                    <span>Loading media terkait...</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
.marquee-wrapper {
    overflow: hidden;
    position: relative;
    width: 100%;
    background-color: #f0f8ff;
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
}

.marquee-content {
    display: flex;
    /* animation applied only when .animate class is added */
}

.marquee-content.animate {
    animation: marquee-seamless 30s linear infinite;
}

.marquee-track {
    display: flex;
    list-style: none;
    padding: 10px 0;
    margin: 0;
}

.marquee-track li {
    margin: 0 20px;
    flex-shrink: 0;
}

.marquee-track img {
    height: 50px;
    object-fit: contain;
}

@keyframes marquee-seamless {
    0% {
        transform: translateX(0%);
    }
    100% {
        transform: translateX(-50%);
    }
}
</style>
