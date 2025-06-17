window.addEventListener('load', () => {
  setTimeout(() => {
      const preloader = document.getElementById('preloader');
      const loginBox = document.getElementById('loginBox');

      if (preloader) {
          preloader.style.display = 'none';
      }

      if (loginBox) {
          loginBox.style.opacity = '1';
      }
  }, 3000);
});
