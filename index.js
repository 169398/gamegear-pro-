const close = document.querySelector(".close");
const open = document.querySelector(".ham");
const menu = document.querySelector(".menu");
close.addEventListener("click", () => {
  menu.style.visibility = "hidden";
});
open.addEventListener("click", () => {
  menu.style.visibility = "visible";
});

window.addEventListener('scroll', function() {
  var button = document.querySelector('.back-to-top');
  if (window.pageYOffset > 300) {
    button.classList.add('show');
  } else {
    button.classList.remove('show');
  }
});

document.querySelector('.back-to-top').addEventListener('click', function(e) {
  e.preventDefault();
  window.scrollTo({ top: 0, behavior: 'smooth' });
});  

// Get the cart count element
var cartCountElement = document.getElementById('cart-count');

// Get the add to cart button
var addToCartButton = document.querySelector('.add-to-cart');

// Initialize the cart count
var cartCount = 0;

// Add event listener to the add to cart button
addToCartButton.addEventListener('click', function() {
  // Increment the cart count
  cartCount++;
  
  // Update the cart count element
  cartCountElement.textContent = cartCount;
});





