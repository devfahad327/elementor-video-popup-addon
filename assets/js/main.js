document.addEventListener('DOMContentLoaded', function () {
    // Open video popup when clicking the video container
    document.querySelectorAll('.evpa-video-popup-container').forEach(container => {
        container.addEventListener('click', function () {
            const videoUrl = this.getAttribute('data-video-url');
            const iframe = document.getElementById('evpa-video-frame');
            iframe.src = videoUrl;
            document.getElementById('evpa-video-popup').classList.add('active');
        });
    });

    // Close popup when clicking the close button
    document.querySelector('.evpa-close-popup').addEventListener('click', function () {
        const popup = document.getElementById('evpa-video-popup');
        popup.classList.remove('active');
        document.getElementById('evpa-video-frame').src = ''; // Stop the video when closing
    });

    // Close popup when clicking anywhere outside the popup content
    document.getElementById('evpa-video-popup').addEventListener('click', function (e) {
        if (e.target === this) { // Only close when clicking outside the popup content area
            this.classList.remove('active');
            document.getElementById('evpa-video-frame').src = ''; // Stop the video when closing
        }
    });

    // Prevent closing when clicking inside the popup content area
    document.querySelector('.evpa-popup-content').addEventListener('click', function (e) {
        e.stopPropagation();
    });
});
