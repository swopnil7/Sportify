// Simple, robust auto-sliding hero carousel
document.addEventListener('DOMContentLoaded', function() {
  const track = document.querySelector('.carousel-track');
  const slides = document.querySelectorAll('.carousel-slide');
  let idx = 0;
  const slideCount = slides.length;
  let interval = null;

  // Set up widths for sliding
  track.style.display = 'flex';
  track.style.transition = 'transform 0.7s cubic-bezier(.77,0,.18,1)';
  track.style.width = `${slideCount * 100}%`;
  slides.forEach(slide => {
    slide.style.width = `${100 / slideCount}%`;
    slide.style.flex = `0 0 ${100 / slideCount}%`;
  });

  function goToSlide(i) {
    idx = (i + slideCount) % slideCount;
    track.style.transform = `translateX(-${idx * (100 / slideCount)}%)`;
  }

  function nextSlide() {
    goToSlide(idx + 1);
  }

  function prevSlide() {
    goToSlide(idx - 1);
  }

  function startAuto() {
    if (interval) clearInterval(interval);
    interval = setInterval(nextSlide, 3500);
  }
  function stopAuto() {
    if (interval) clearInterval(interval);
  }

  // Pause on hover
  track.addEventListener('mouseenter', stopAuto);
  track.addEventListener('mouseleave', startAuto);

  // Touch swipe support
  let startX = null;
  track.addEventListener('touchstart', e => {
    startX = e.touches[0].clientX;
    stopAuto();
  });
  track.addEventListener('touchend', e => {
    if (startX !== null) {
      let endX = e.changedTouches[0].clientX;
      if (endX - startX > 50) {
        prevSlide();
      } else if (startX - endX > 50) {
        nextSlide();
      }
      startX = null;
      startAuto();
    }
  });

  goToSlide(0);
  startAuto();
});
