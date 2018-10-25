// grab active links and set them
document
  .querySelectorAll('a[href="' + location.pathname + '"]')
  .forEach(item => item.classList.add('active'))
