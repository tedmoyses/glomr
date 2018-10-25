document.getElementById('menu-icon').addEventListener('click', function () {
  const nav = document.getElementsByTagName('nav')[0]
  console.log('clicked')
  nav.classList.toggle('show')
})
