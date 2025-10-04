
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                } else {
                    // Remove animate class when element goes out of view (for re-animation)
                    entry.target.classList.remove('animate');
                }
            });
        }, observerOptions);

        // Observe all scroll-animate elements
        document.addEventListener('DOMContentLoaded', () => {
            const scrollElements = document.querySelectorAll('.scroll-animate');
            scrollElements.forEach(el => observer.observe(el));
        });

        // Special handling for testimonials with different delays
        const testimonialObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    // Add delay for staggered animation
                    setTimeout(() => {
                        entry.target.classList.add('animate');
                    }, index * 300); // 300ms delay between each testimonial
                } else {
                    entry.target.classList.remove('animate');
                }
            });
        }, observerOptions);

        // Observe testimonial containers separately
        document.addEventListener('DOMContentLoaded', () => {
            const testimonialContainers = document.querySelectorAll('.testimonial-container');
            testimonialContainers.forEach(el => testimonialObserver.observe(el));
        });

        // Handle MSME logo animations with proper sequencing
        const msmeObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const msmeText = entry.target.querySelector('.msme-text');
                    const globalText = entry.target.querySelector('.global-text');

                    // Animate MSME first
                    if (msmeText) {
                        msmeText.classList.add('animate');
                    }

                    // Animate GLOBAL after MSME with delay
                    if (globalText) {
                        setTimeout(() => {
                            globalText.classList.add('animate');
                        }, 500);
                    }
                } else {
                    // Remove animations when out of view
                    const msmeText = entry.target.querySelector('.msme-text');
                    const globalText = entry.target.querySelector('.global-text');

                    if (msmeText) msmeText.classList.remove('animate');
                    if (globalText) globalText.classList.remove('animate');
                }
            });
        }, observerOptions);

        // Observe MSME logo section
        document.addEventListener('DOMContentLoaded', () => {
            const msmeLogoSection = document.querySelector('.msme-logo');
            if (msmeLogoSection) {
                msmeObserver.observe(msmeLogoSection);
            }
        });

        // Enhanced observer for info boxes with staggered animations
        const infoBoxObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                } else {
                    entry.target.classList.remove('animate');
                }
            });
        }, {
            threshold: 0.2,
            rootMargin: '0px 0px -100px 0px'
        });

        // Observe info boxes
        document.addEventListener('DOMContentLoaded', () => {
            const infoBoxes = document.querySelectorAll('.info-box');
            infoBoxes.forEach((box, index) => {
                // Add staggered delay for each box
                box.style.transitionDelay = `${index * 0.3}s`;
                infoBoxObserver.observe(box);
            });
        });


    let slideIndex = 0;
    showSlides();

    function showSlides() {
      let slides = document.querySelectorAll(".banner-slider .slide");
      slides.forEach(slide => slide.classList.remove("active"));
      slideIndex++;
      if (slideIndex > slides.length) { slideIndex = 1; }
      slides[slideIndex - 1].classList.add("active");
      setTimeout(showSlides, 4000); // Change every 4s
    }
document.addEventListener('DOMContentLoaded', function() {
  const toggleBtn = document.getElementById('toggleBtn');
  const influencerGrid = document.querySelector('.influencer-grid');
  let isExpanded = false;

  toggleBtn.addEventListener('click', function() {
    if (!isExpanded) {
      // Show more influencers
      influencerGrid.classList.add('show-more');

      // Change button to "See Less"
      toggleBtn.textContent = 'See Less';
      toggleBtn.className = 'toggle-btn see-less';

      // Smooth scroll to the expanded section
      setTimeout(() => {
        toggleBtn.scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });
      }, 100);

      isExpanded = true;
    } else {
      // Hide extra influencers
      influencerGrid.classList.remove('show-more');

      // Change button back to "See More"
      toggleBtn.textContent = 'See More';
      toggleBtn.className = 'toggle-btn see-more';

      // Scroll back to the top of influencers section
      setTimeout(() => {
        influencerGrid.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }, 100);

      isExpanded = false;
    }
  });
});
  function toggleDropdown(element) {
      // Close all other dropdowns
      const allDropdowns = document.querySelectorAll('.mentor-dropdown');
      allDropdowns.forEach(dropdown => {
          if (dropdown !== element) {
              dropdown.classList.remove('active');
          }
      });

      // Toggle current dropdown
      element.classList.toggle('active');
  }

  // Optional: Close dropdown when clicking outside
  document.addEventListener('click', function(event) {
      if (!event.target.closest('.mentor-dropdown')) {
          const allDropdowns = document.querySelectorAll('.mentor-dropdown');
          allDropdowns.forEach(dropdown => {
              dropdown.classList.remove('active');
          });
      }
  });

  // Auto-play video when scrolling into view
  function isElementInViewport(el) {
      const rect = el.getBoundingClientRect();
      return (
          rect.top >= 0 &&
          rect.left >= 0 &&
          rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
          rect.right <= (window.innerWidth || document.documentElement.clientWidth)
      );
  }

  function handleScroll() {
      const videoContainer = document.getElementById('videoContainer');
      const iframe = document.getElementById('pillarsVideo');

      if (isElementInViewport(videoContainer)) {
          // Video is in viewport, play it
          iframe.src = iframe.src.replace('mute=0', 'mute=0&autoplay=1');
      }
  }

  // Listen for scroll events
  let scrollTimeout;
  window.addEventListener('scroll', function() {
      clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(handleScroll, 100);
  });

  // Check on page load
  document.addEventListener('DOMContentLoaded', handleScroll);
