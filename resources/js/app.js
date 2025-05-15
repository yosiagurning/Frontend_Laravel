import './bootstrap';
// JavaScript for Market Section
document.addEventListener("DOMContentLoaded", () => {
    // Market Lightbox functionality
    const marketItems = document.querySelectorAll(".gallery-item")
    const marketLightbox = document.getElementById("market-lightbox")
    const closeLightboxBtn = document.querySelector("#market-lightbox .close")
  
    if (marketItems && marketLightbox && closeLightboxBtn) {
      // Open lightbox when market item is clicked
      marketItems.forEach((item) => {
        item.addEventListener("click", function () {
          const marketId = this.getAttribute("data-market-id")
          // You would typically fetch market details here or use pre-loaded data
          marketLightbox.classList.add("active")
        })
      })
  
      // Close lightbox when close button is clicked
      closeLightboxBtn.addEventListener("click", () => {
        marketLightbox.classList.remove("active")
      })
  
      // Close lightbox when clicking outside the content
      marketLightbox.addEventListener("click", (e) => {
        if (e.target === marketLightbox) {
          marketLightbox.classList.remove("active")
        }
      })
    }
  })
  