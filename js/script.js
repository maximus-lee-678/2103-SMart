let searchForm = document.querySelector('.search-form');

let searchBtn = document.querySelector('#search-btn');
if (searchBtn)
    searchBtn.onclick = () => {
        searchForm.classList.toggle('active');
        cart.classList.remove('active');
        loginForm.classList.remove('active');
        navbar.classList.remove('active');
    };



let cart = document.querySelector('.shopping-cart');
let cartBtn = document.querySelector('#cart-btn');
if (cartBtn)
    cartBtn.onclick = () => {
        cart.classList.toggle('active');
        searchForm.classList.remove('active');
        loginForm.classList.remove('active');
        navbar.classList.remove('active');
    };


let loginForm = document.querySelector('.login-form');

document.querySelector('#login-btn').onclick = () => {
    loginForm.classList.toggle('active');
    searchForm.classList.remove('active');
    cart.classList.remove('active');
    navbar.classList.remove('active');
}

let navbar = document.querySelector('.navbar');

window.onscroll = () => {
    searchForm.classList.remove('active');
    cart.classList.remove('active');
    loginForm.classList.remove('active');
    navbar.classList.remove('active');
}

let slides = document.querySelectorAll('.home .slides-container .slide');
let index = 0;

function next() {
    slides[index].classList.remove('active');
    index = (index + 1) % slides.length;
    slides[index].classList.add('active');
}

function prev() {
    slides[index].classList.remove('active');
    index = (index - 1 + slides.length) % slides.length;
    slides[index].classList.add('active');
}

function openCity(evt, cityName) {
    var i, tabcontent, tabcontent2, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tabcontent2 = document.getElementsByClassName("tabcontent2");
    for (i = 0; i < tabcontent2.length; i++) {
        tabcontent2[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

function populateFoodListNumber(){
        $.ajax({
        type: 'POST',
        url: 'expiry-list-process.php',
        data: {type: "expire_number"},
        success: function (data) {
            $('span[name="nav-expire-count"]').html(data);
        }
    });
}

$(document).ready(function () {
    if ((window.location.href).includes("profile.php") || (window.location.href).includes("staff-home.php") 
            || (window.location.href).includes("order-history.php") || (window.location.href).includes("expiry-list.php")) {
        document.getElementById("defaultOpen").click();
    }
    
    populateFoodListNumber();
});

