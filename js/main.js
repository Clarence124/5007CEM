// AOS
// AOS.refreshHard();
AOS.init({
  offset: 200, // offset (in px) from the original trigger point
  delay: 100, // values from 0 to 3000, with step 50ms
  duration: 400, // values from 0 to 3000, with step 50ms
  easing: 'ease', // default easing for AOS animations
  once: false, // whether animation should happen only once - while scrolling down
  mirror: false, // whether elements should animate out while scrolling past them
  anchorPlacement: 'top-bottom' // defines which position of the element regarding to window should trigger the animation
});

// const dishGridEl = Array.from(document.querySelectorAll('#dishGrid'));
// if (dishGridEl.length > 0){
//   // console.log(dishGridEl)
//   dishGridEl.forEach(item => {
//     item.setAttribute('data-aos', 'fade-up');
//   })
// }


document.addEventListener("DOMContentLoaded", function() {
    var viewDetailsButtons = document.getElementsByClassName("view-details");
    var closeButtons = document.getElementsByClassName("close");
    var modals = document.getElementsByClassName("modal");

    // Add click event listeners to view details buttons
    for (var i = 0; i < viewDetailsButtons.length; i++) {
        viewDetailsButtons[i].addEventListener("click", function() {
            var modalId = this.dataset.modalid;
            var modal = document.getElementById(modalId);
            modal.style.display = "block";
        });
    }

    // Add click event listeners to close buttons
    for (var i = 0; i < closeButtons.length; i++) {
        closeButtons[i].addEventListener("click", function() {
            var modal = this.closest(".modal");
            modal.style.display = "none";
        });
    }

    // Add click event listener to close modals when clicking outside the content area
    window.addEventListener("click", function(event) {
        for (var i = 0; i < modals.length; i++) {
            if (event.target === modals[i]) {
                modals[i].style.display = "none";
            }
        }
    });
});
  
  

