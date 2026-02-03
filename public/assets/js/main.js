// Main JavaScript for GlobalGrub
document.addEventListener("DOMContentLoaded", () => {
  // Hero Section Animation
  initHeroSlider();

  // Initialize tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  const tooltipList = tooltipTriggerList.map((tooltipTriggerEl) => new window.bootstrap.Tooltip(tooltipTriggerEl));

  // Initialize popovers
  const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
  const popoverList = popoverTriggerList.map((popoverTriggerEl) => new window.bootstrap.Popover(popoverTriggerEl));

  // Search functionality
  initSearch();

  // Cart functionality
  initCart();

  // Form validation
  initFormValidation();

  // Initialize cart UI
  updateCartUI();

  // Initialize admin sidebar toggle
  initSidebarToggle();
});

// Hero Slider
function initHeroSlider() {
  const backgrounds = document.querySelectorAll(".hero-bg");
  const dots = document.querySelectorAll(".hero-dot");
  let currentSlide = 0;

  if (backgrounds.length === 0) return;

  // Auto-advance slides
  setInterval(() => {
    nextSlide();
  }, 5000);

  // Dot navigation
  dots.forEach((dot, index) => {
    dot.addEventListener("click", () => {
      goToSlide(index);
    });
  });

  function nextSlide() {
    currentSlide = (currentSlide + 1) % backgrounds.length;
    goToSlide(currentSlide);
  }

  function goToSlide(index) {
    // Remove active class from all backgrounds and dots
    backgrounds.forEach((bg) => bg.classList.remove("active"));
    dots.forEach((dot) => dot.classList.remove("active"));

    // Add active class to current slide
    if (backgrounds[index]) backgrounds[index].classList.add("active");
    if (dots[index]) dots[index].classList.add("active");

    currentSlide = index;
  }

  // Initialize first slide
  goToSlide(0);
}

// Global function for hero navigation
function goToSlide(index) {
  const backgrounds = document.querySelectorAll(".hero-bg");
  const dots = document.querySelectorAll(".hero-dot");

  backgrounds.forEach((bg) => bg.classList.remove("active"));
  dots.forEach((dot) => dot.classList.remove("active"));

  if (backgrounds[index]) backgrounds[index].classList.add("active");
  if (dots[index]) dots[index].classList.add("active");
}

// Search functionality
function initSearch() {
  const searchInputs = document.querySelectorAll('input[type="text"][placeholder*="Search"]');

  searchInputs.forEach((input) => {
    input.addEventListener(
      "input",
      debounce((e) => {
        const query = e.target.value.trim();
        if (query.length > 2) {
          performSearch(query);
        }
      }, 300),
    );
  });
}

function performSearch(query) {
  console.log("Searching for:", query);
  // Implement search logic here
}

// Cart functionality
function initCart() {
  // Add to cart buttons
  document.addEventListener("click", (e) => {
    if (e.target.classList.contains("add-to-cart")) {
      e.preventDefault();
      const productId = e.target.dataset.productId;
      const productName = e.target.dataset.productName;
      const productPrice = e.target.dataset.productPrice;

      addToCart(productId, productName, productPrice);
    }

    // Remove from cart
    if (e.target.classList.contains("remove-from-cart")) {
      e.preventDefault();
      const productId = e.target.dataset.productId;
      removeFromCart(productId);
    }

    // Update quantity
    if (e.target.classList.contains("quantity-btn")) {
      e.preventDefault();
      const productId = e.target.dataset.productId;
      const action = e.target.dataset.action;
      updateQuantity(productId, action);
    }
  });
}

function addToCart(productId, productName, productPrice) {
  const cart = getCart();

  const existingItem = cart.find((item) => item.id === productId);

  if (existingItem) {
    existingItem.quantity += 1;
  } else {
    cart.push({
      id: productId,
      name: productName,
      price: Number.parseFloat(productPrice),
      quantity: 1,
    });
  }

  saveCart(cart);
  updateCartUI();
  showNotification("Product added to cart!", "success");
}

function removeFromCart(productId) {
  let cart = getCart();
  cart = cart.filter((item) => item.id !== productId);
  saveCart(cart);
  updateCartUI();
  showNotification("Product removed from cart!", "info");
}

function updateQuantity(productId, action) {
  const cart = getCart();
  const item = cart.find((item) => item.id === productId);

  if (item) {
    if (action === "increase") {
      item.quantity += 1;
    } else if (action === "decrease" && item.quantity > 1) {
      item.quantity -= 1;
    } else if (action === "decrease" && item.quantity === 1) {
      removeFromCart(productId);
      return;
    }
  }

  saveCart(cart);
  updateCartUI();
}

function getCart() {
  return JSON.parse(localStorage.getItem("globalgrub_cart") || "[]");
}

function saveCart(cart) {
  localStorage.setItem("globalgrub_cart", JSON.stringify(cart));
}

function updateCartUI() {
  const cart = getCart();
  const cartCount = cart.reduce((total, item) => total + item.quantity, 0);

  // Update cart badge
  const cartBadges = document.querySelectorAll(".cart-badge");
  cartBadges.forEach((badge) => {
    badge.textContent = cartCount;
    badge.style.display = cartCount > 0 ? "inline" : "none";
  });

  // Update cart item count
  const cartItemCount = document.getElementById("cart-item-count");
  if (cartItemCount) {
    cartItemCount.textContent = cartCount;
  }

  // Update cart page if we're on it
  if (window.location.pathname.includes("cart.html")) {
    renderCartPage();
  }
}

function renderCartPage() {
  const cart = getCart();
  const cartContainer = document.getElementById("cart-items");
  const emptyCart = document.getElementById("empty-cart");

  if (!cartContainer) return;

  if (cart.length === 0) {
    if (cartContainer) cartContainer.style.display = "none";
    if (emptyCart) emptyCart.style.display = "block";
    return;
  }

  if (emptyCart) emptyCart.style.display = "none";
  if (cartContainer) cartContainer.style.display = "block";

  // Calculate totals
  let subtotal = 0;
  cart.forEach((item) => {
    subtotal += item.price * item.quantity;
  });

  const deliveryFee = subtotal > 25 ? 0 : 3.99;
  const total = subtotal + deliveryFee;

  // Update totals in UI
  const subtotalElement = document.getElementById("subtotal");
  const deliveryFeeElement = document.getElementById("delivery-fee");
  const totalElement = document.getElementById("cart-total");

  if (subtotalElement) subtotalElement.textContent = `£${subtotal.toFixed(2)}`;
  if (deliveryFeeElement) deliveryFeeElement.textContent = deliveryFee === 0 ? "FREE" : `£${deliveryFee.toFixed(2)}`;
  if (totalElement) totalElement.textContent = `£${total.toFixed(2)}`;
}

// Form validation
function initFormValidation() {
  const forms = document.querySelectorAll(".needs-validation");

  Array.from(forms).forEach((form) => {
    form.addEventListener("submit", (event) => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }

      form.classList.add("was-validated");
    });
  });
}

// Find food functionality
function findFood() {
  const postcode = document.getElementById("postcodeInput")?.value;
  if (postcode) {
    window.location.href = `browse.html?postcode=${encodeURIComponent(postcode)}`;
  } else {
    window.location.href = "browse.html";
  }
}

// Promo code functionality
function applyPromoCode() {
  const promoInput = document.getElementById("promo-code");
  const promoMessage = document.getElementById("promo-message");

  if (!promoInput || !promoMessage) return;

  const code = promoInput.value.trim().toLowerCase();

  if (code === "welcome10") {
    promoMessage.textContent = "Promo code WELCOME10 applied! 10% discount";
    promoMessage.style.display = "block";
    promoInput.value = "";
    showNotification("Promo code applied successfully!", "success");
  } else if (code === "") {
    showNotification("Please enter a promo code", "warning");
  } else {
    showNotification("Invalid promo code", "danger");
  }
}

// Utility functions
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

function showNotification(message, type = "info") {
  // Create notification element
  const notification = document.createElement("div");
  notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
  notification.style.cssText = "top: 20px; right: 20px; z-index: 9999; min-width: 300px;";
  notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

  document.body.appendChild(notification);

  // Auto-remove after 3 seconds
  setTimeout(() => {
    if (notification.parentNode) {
      notification.remove();
    }
  }, 3000);
}

// Mobile menu toggle for admin
function toggleAdminSidebar() {
  const sidebar = document.querySelector(".admin-sidebar");
  const mainContent = document.querySelector(".main-content");
  if (sidebar && mainContent) {
    sidebar.classList.toggle("show");
    mainContent.classList.toggle("sidebar-visible");
  }
}

// Initialize admin sidebar on mobile
function initSidebarToggle() {
  const sidebarToggle = document.querySelector(".sidebar-toggle");
  if (sidebarToggle) {
    sidebarToggle.addEventListener("click", toggleAdminSidebar);
  }
}