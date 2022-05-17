const hamburger = document.querySelector('.hamburger');
const navLink = document.querySelector('.nav__link');

hamburger.addEventListener('click', () => {
  navLink.classList.toggle('hide');
});

function darkMode() {
  var element = document.body;
  element.classList.toggle("dark-mode");
}

function la(src) {
  window.location=src;
}


