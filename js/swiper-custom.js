// JavaScript to handle the video overlay
document.addEventListener('DOMContentLoaded', function() {
	var overlay = document.getElementById('video-overlay');
	var iframe = document.getElementById('video-iframe');
	var closeBtn = document.getElementById('close-video');

	// Open overlay when the link is clicked
	var links = document.querySelectorAll('.open-video-overlay');
	links.forEach(function(link) {
		link.addEventListener('click', function(e) {
			e.preventDefault();
			var videoUrl = this.href;
			var videoId = videoUrl.split('v=')[1].split('&')[0]; // Get the video ID from the URL
			iframe.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1'; // Embed the video
			overlay.style.display = 'flex'; // Show the overlay
		});
	});

	// Close the overlay
	closeBtn.addEventListener('click', function() {
		overlay.style.display = 'none';
		iframe.src = ''; // Stop the video
	});
});

document.querySelector('.overlay').classList.add('fade-in');



// Accordion
const headers = document.querySelectorAll('.accordion-header');

// Add click event listener to each header
headers.forEach(header => {
  header.addEventListener('click', function() {
    const content = this.nextElementSibling; // The next sibling is the content
    const icon = this.querySelector('.toggle-icon'); // Get the plus/minus icon

    // Close all other accordion sections
    headers.forEach(otherHeader => {
      if (otherHeader !== this) {
        const otherContent = otherHeader.nextElementSibling;
        const otherIcon = otherHeader.querySelector('.toggle-icon');
        otherContent.style.display = 'none';
        otherContent.style.opacity = '0';
        otherContent.style.maxHeight = '0';
        otherIcon.textContent = '+'; // Reset icon to plus
        otherHeader.classList.remove('open'); // Remove 'open' class
      }
    });

    // Toggle the display of the clicked content and icon
    if (content.style.display === 'block') {
      content.style.display = 'none';
      content.style.opacity = '0';
      content.style.maxHeight = '0';
      icon.textContent = '+'; // Change the icon to plus
      this.classList.remove('open');
    } else {
      content.style.display = 'block';
      content.style.opacity = '1';
      content.style.maxHeight = '500px'; // Set a maximum height for the transition
      icon.textContent = '-'; // Change the icon to minus
      this.classList.add('open');
      content.scrollIntoView({
        behavior: 'smooth',
        block: 'start',
      });
    }
  });
});



