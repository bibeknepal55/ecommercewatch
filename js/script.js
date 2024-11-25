let navbar = document.querySelector('.header .flex .navbar');
let profile = document.querySelector('.header .flex .profile');


document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   profile.classList.remove('active');
}

let mainImage = document.querySelector('.quick-view .box .row .image-container .main-image img');
let subImages = document.querySelectorAll('.quick-view .box .row .image-container .sub-image img');

subImages.forEach(images =>{
   images.onclick = () =>{
      src = images.getAttribute('src');
      mainImage.src = src;
   }
});



// Add this to your script.js file
document.addEventListener('DOMContentLoaded', function() {
   const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
   const navList = document.querySelector('.nav-list');

   mobileMenuBtn.addEventListener('click', function() {
       navList.classList.toggle('active');
   });

   // Close menu when clicking outside
   document.addEventListener('click', function(e) {
       if (!navList.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
           navList.classList.remove('active');
       }
   });
});
