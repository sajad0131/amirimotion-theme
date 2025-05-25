let player;
    let currentVideoId = '';
    let currentVideoSource = ''; // To track whether it's YouTube or Google Drive



// Testimonial Slider


document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.testimonial-slide');
    const dotsContainer = document.querySelector('.slider-dots');
    let currentSlide = 0;

    // Create dots
    slides.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if(index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });

    function goToSlide(n) {
        slides[currentSlide].classList.remove('active');
        dotsContainer.children[currentSlide].classList.remove('active');
        
        currentSlide = (n + slides.length) % slides.length;
        
        slides[currentSlide].classList.add('active');
        dotsContainer.children[currentSlide].classList.add('active');
    }

    document.querySelector('.slider-prev').addEventListener('click', () => {
        goToSlide(currentSlide - 1);
    });

    document.querySelector('.slider-next').addEventListener('click', () => {
        goToSlide(currentSlide + 1);
    });

    // Auto-rotate every 5 seconds
    setInterval(() => {
        goToSlide(currentSlide + 1);
    }, 5000);

    // Show first slide initially
    slides[0].classList.add('active');
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});





document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger-menu');
    const nav = document.querySelector('.main-nav');
    
    hamburger.addEventListener('click', function() {
        nav.classList.toggle('active');
    });
});


// video controll

const youtubePlayerContainer = document.getElementById('youtube-player');
    const googleDrivePlayerContainer = document.getElementById('google-drive-player');
    const videoDescription = document.querySelector('.video-description');
    const modalOverlay = document.querySelector('.video-modal-overlay');
function initYouTubePlayer() {
    const tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    const firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
}

// Initialize Google Drive Player (using an iframe for simplicity)
function initGoogleDrivePlayer(videoId) {
googleDrivePlayerContainer.innerHTML = '';
const iframe = document.createElement('iframe');
iframe.src = `https://drive.google.com/file/d/${videoId}/preview?embedded=true`; // Added embedded=true
iframe.width = '100%';
iframe.height = '100%';
iframe.frameBorder = '0';
iframe.className = 'drive-frame';

// Keep fullscreen capabilities
iframe.allowFullScreen = true;
iframe.allow = 'fullscreen';

googleDrivePlayerContainer.appendChild(iframe);
}

function onPlayerReady(event) {
    checkUrlForVideo(); // Check for video parameter after player is ready
}

function onPlayerStateChange(event) {
    if (event.data === YT.PlayerState.ENDED) {
        closeModal();
    }
}

// Add this missing function
function checkUrlForVideo() {
    const urlParams = new URLSearchParams(window.location.search);
    const youtubeVideoId = urlParams.get('video');
    const googleDriveVideoId = urlParams.get('drive_video');

    if (youtubeVideoId) {
        openModal(youtubeVideoId, '', 'Video Description');
    } else if (googleDriveVideoId) {
        openModal('', googleDriveVideoId, 'Video Description');
    }
}

// Open Modal
// Updated Open Modal function
function openModal(youtubeId, googleDriveId, description) {
    



    // Reset both containers
youtubePlayerContainer.style.display = 'none';
googleDrivePlayerContainer.style.display = 'none';
googleDrivePlayerContainer.innerHTML = ''; // Clear Google Drive iframe
youtubePlayerContainer.innerHTML = '';

if (youtubeId && youtubeId.trim() !== '') {
    currentVideoSource = 'youtube';
    currentVideoId = youtubeId;
    youtubePlayerContainer.style.display = 'block';
document.querySelector("#youtube-player").style.display = 'block';
document.querySelector("#youtube-player").style = 'justify-self: center; display: block;';


    player.loadVideoById(youtubeId);
    videoDescription.textContent = description;
    modalOverlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
} else if (googleDriveId && googleDriveId.trim() !== '') {
    currentVideoSource = 'google_drive';
    currentVideoId = googleDriveId;
    youtubePlayerContainer.style.display = 'none';
    googleDrivePlayerContainer.style.display = 'block';
    document.querySelector("#youtube-player").style.display = 'none';
    initGoogleDrivePlayer(googleDriveId);
    videoDescription.textContent = description;
    modalOverlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
} else {
    console.error('No valid video source:', { youtubeId, googleDriveId });
    return;
}
}

// Updated Close Modal function
function closeModal() {
if (currentVideoSource === 'youtube' && player && typeof player.stopVideo === 'function') {
    player.stopVideo();
}

// Reset both containers
youtubePlayerContainer.style.display = 'none';
googleDrivePlayerContainer.style.display = 'none';
googleDrivePlayerContainer.innerHTML = ''; // Clear Google Drive iframe

modalOverlay.style.display = 'none';
document.body.style.overflow = 'auto';
}

function handleExampleVideos() {
    document.querySelectorAll('.example-video-button').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const container = e.target.closest('.pricing-card');
            if (container) {
                const youtubeId = container.dataset.youtubeExampleId;
                const googleDriveId = container.dataset.driveExampleId;
                const description = container.dataset.description || 'Example Video';
                
                // Prioritize YouTube if both IDs exist
                if (youtubeId && youtubeId.trim() !== '') {
                    openModal(youtubeId, '', description);
                } else if (googleDriveId && googleDriveId.trim() !== '') {
                    openModal('', googleDriveId, description);
                }
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const modalOverlay = document.querySelector('.video-modal-overlay');
    const youtubePlayerContainer = document.getElementById('youtube-player');
    const googleDrivePlayerContainer = document.getElementById('google-drive-player');
    const videoDescription = document.querySelector('.video-description');
    const copyLinkButton = document.querySelector('.copy-link-button');
    
    document.querySelectorAll('.pricing-card').forEach(card => {
        const durations = JSON.parse(card.dataset.durations || '[]');
        const durationList = card.querySelector('.price-duration-list');
        
        if (durationList && durations.length > 0) {
            durationList.innerHTML = durations.map(pd => `
                <div class="price-duration-item">
                    <span class="duration">${pd.duration}</span>
                    <span class="price">${pd.price}</span>
                </div>
            `).join('');
        }
    });

    // Initialize example video buttons
    handleExampleVideos();
    // Initialize YouTube Player
    

    // YouTube API ready callback
    window.onYouTubeIframeAPIReady = () => {
        player = new YT.Player('youtube-player', {
            host: 'https://www.youtube-nocookie.com',
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            },
            playerVars: {
                'rel': 0,
                'modestbranding': 1,
                'fs': 1,
                'hl': 'en',
                'cc_load_policy': 0,
                'iv_load_policy': 3
            }
        });
    };

    

    // Copy Link functionality
if (copyLinkButton) {
    copyLinkButton.addEventListener('click', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const currentParams = new URLSearchParams(window.location.search);

        let videoUrl = `${window.location.origin}${window.location.pathname}?`;
        
        // Include existing parameters
        for (const [key, value] of currentParams.entries()) {
            if (key !== 'video' && key !== 'drive_video') {
                videoUrl += `${key}=${value}&`;
            }
        }

        if (currentVideoSource === 'youtube') {
            videoUrl += `&video=${currentVideoId}`;
        } else if (currentVideoSource === 'google_drive') {
            videoUrl += `&drive_video=${currentVideoId}`;
        }

        if (videoUrl) {
            navigator.clipboard.writeText(videoUrl)
                .then(() => {
                    alert('Link copied to clipboard!');
                })
                .catch(() => {
                    alert('Failed to copy link. Please try again.');
                });
        } else {
            console.error('No video source found for copying the link.');
        }
    });
}


    // Play buttons click handlers
    document.querySelectorAll('.play-pause').forEach(button => {
        button.addEventListener('click', (e) => {
            const container = e.target.closest('.video-container, .portfolio-card, .category-showreel, .portfolio-item');
            if (container) {
                const youtubeId = container.dataset.youtubeId;
                const googleDriveId = container.dataset.googleDriveId;
                const description = container.dataset.description;
                openModal(youtubeId, googleDriveId, description);
            }
        });
    });




// Close handlers
    document.querySelector('.close-modal')?.addEventListener('click', closeModal);
    modalOverlay?.addEventListener('click', (e) => {
        if (e.target === modalOverlay) closeModal();
    });

    


    // Initialize
    initYouTubePlayer();
});

document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const youtubeVideoId = urlParams.get('video');
    const googleDriveVideoId = urlParams.get('drive_video');

    if (youtubeVideoId || googleDriveVideoId) {
        // If a video parameter is present, open the modal for the respective video.
        const description = "Description of the video"; // Optional, replace with actual description logic
        openModal(youtubeVideoId, googleDriveVideoId, description);
    }





    





});



// Pricing Form Handling
// Pricing Form Handling
document.addEventListener('DOMContentLoaded', function() {
    const formModal = document.querySelector('.form-modal-overlay');
    const durationSelect = document.querySelector('#duration-select');
    const selectedPriceInput = document.querySelector('#selected-price');



    // Open modal and populate durations
    document.querySelectorAll('.open-form').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const pricingCard = e.target.closest('.pricing-card');
            let planName, planType;
            
            let durations;

            
           

            if (button.closest('.pricing-type-group')) {
                const planTypeGroup = button.closest('.pricing-type-group');
                planType = planTypeGroup.dataset.planType;
                planName = pricingCard.querySelector('.open-form').dataset.plan;
                durations = JSON.parse(pricingCard.dataset.durations || '[]');
            }
            // Single Plan page version
            else if (button.dataset.planType) {
                planType = button.dataset.planType;
                planName = button.dataset.plan || document.querySelector('.plan-title').textContent;
                durations = JSON.parse(button.dataset.durations || '[]');
            }

            // Set the selected plan type
            document.querySelector('#selected-plan-type').value = planType;
            
            // Set the selected plan
            document.querySelector('#selected-plan').value = planName;
            
            // Populate duration dropdown
            durationSelect.innerHTML = '<option value="">Select Duration</option>';
            durations.forEach(pd => {
                const option = document.createElement('option');
                option.value = pd.duration;
                option.textContent = `${pd.duration} - ${pd.price}`;
                option.dataset.price = pd.price;
                durationSelect.appendChild(option);
            });
            // Add "I don't know" option
            const unknownOption = document.createElement('option');
            unknownOption.value = 'unknown';
            unknownOption.textContent = "I don't know the duration";
            unknownOption.dataset.price = 'N/A';
            durationSelect.appendChild(unknownOption);
            
            // Reset price input
            selectedPriceInput.value = '';
            
            formModal.style.display = 'block';
        });
    });

    // Update price when duration is selected
    durationSelect.addEventListener('change', () => {
        const selectedOption = durationSelect.options[durationSelect.selectedIndex];
        const price = selectedOption.dataset.price || '';
        selectedPriceInput.value = price;
    });

    // Close modal
    document.querySelector('.modal-close').addEventListener('click', () => {
        formModal.style.display = 'none';
    });

    // Handle form submission
    document.getElementById('contact-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const spinner = form.querySelector('.loading-spinner');
        
        // Show loading spinner
        spinner.style.display = 'block';
        
        fetch(sinaAmiri.ajaxurl, {
            method: 'POST',
            body: new URLSearchParams(new FormData(form)),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.data);
                formModal.style.display = 'none';
                form.reset();
            } else {
                alert(data.data);
            }
        })
        .catch(error => {
            console.error('Form submission error:', error);
            alert('An error occurred. Please try again.');
        })
        .finally(() => {
            spinner.style.display = 'none'; // Hide spinner always
        });
    });
});

