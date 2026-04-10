let navbar = document.querySelector('.header .navbar');
let accountBox = document.querySelector('.header .account-box');
let menuBtn = document.querySelector('#menu-btn');

if(menuBtn){
   menuBtn.onclick = () =>{
      navbar.classList.toggle('active');
      accountBox.classList.remove('active');
   }
}

document.querySelector('#user-btn').onclick = () =>{
   accountBox.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   accountBox.classList.remove('active');
}

let closeUpdateBtn = document.querySelector('#close-update');
if(closeUpdateBtn){
   closeUpdateBtn.onclick = () =>{
      document.querySelector('.edit-product-form').style.display = 'none';
      window.location.href = window.location.pathname;
   }
}