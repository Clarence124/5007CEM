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


function validateForm() {
  var firstName = document.forms["booking"]["firstName"].value;
  var lastName = document.forms["booking"]["lastName"].value;
  var email = document.forms["booking"]["email"].value;
  var tableType = document.forms["booking"]["tableType"].value;
  var guestNumber = document.forms["booking"]["guestNumber"].value;
  var placement = document.forms["booking"]["placement"].value;
  var date = document.forms["booking"]["date"].value;
  var time = document.forms["booking"]["time"].value;

  if (firstName === "" || lastName === "" || email === "" || tableType === "" || guestNumber === "" || placement === "" || date === "" || time === "") {
    alert("Please fill in all required fields.");
    return false;
  }

  if (!validateEmail(email)) {
    alert("Please enter a valid email address.");
    return false;
  }

  if (!validateGuestNumber(guestNumber)) {
    alert("Please enter a valid guest number (1-10).");
    return false;
  }

  // Check availability for the selected date and time
  return checkAvailability(selectedDate, selectedTime)
    .then(available => {
      if (available) {
        // Date and time are available, continue with the form submission
        return true;
      } else {
        // Date and time are not available, show an error message or take appropriate action
        alert('Selected date and time are not available. Please choose a different time.');
        return false;
      }
    })
    .catch(error => {
      console.error('Error checking availability:', error);
      // Handle the error condition, prevent form submission, and display an error message
      return false;
    });
}

function validateEmail(email) {
  var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailPattern.test(email);
}

function validateGuestNumber(guestNumber) {
  return !isNaN(guestNumber) && guestNumber >= 1 && guestNumber <= 10;
}

// Function to check date and time availability
function checkAvailability(date, time) {
  // Perform an AJAX request to check availability on the server
  // You will need to replace the URL and customize the request according to your backend implementation
  return fetch('check_availability.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ date, time }),
  })
    .then(response => response.json())
    .then(data => {
      // Return the availability status from the response
      return data.available;
    })
    .catch(error => {
      console.error('Error checking availability:', error);
      // Handle the error condition
    });
}

// Function to handle dynamic table selection
function handleTableSelection(tableId) {
  // Remove the 'selected' class from all table elements
  const tables = document.querySelectorAll('.table');
  tables.forEach(table => {
    table.classList.remove('selected');
  });

  // Add the 'selected' class to the clicked table element
  const selectedTable = document.getElementById(tableId);
  selectedTable.classList.add('selected');

  // Update the hidden input field with the selected table information
  const selectedTableInput = document.getElementById('selectedTable');
  selectedTableInput.value = tableId;
}

// Add event listeners to table elements for dynamic table selection
const tables = document.querySelectorAll('.table');
tables.forEach(table => {
  table.addEventListener('click', function () {
    const tableId = this.id;
    handleTableSelection(tableId);
  });
});

function openReservationForm() {
  document.getElementById("reservationForm").style.display = "block";
  document.getElementById("seatingChart").style.display = "none";
}

function cancelSelection() {
  document.getElementById("reservationForm").style.display = "none";
  document.getElementById("seatingChart").style.display = "flex";
  document.getElementById("selectedTable").value = "";
}

function selectTable(tableId) {
  // Deselect all tables
  const tables = document.querySelectorAll('.table');
  tables.forEach(table => {
    table.classList.remove('selected');
  });

  // Select the clicked table
  const selectedTable = document.getElementById(tableId);
  selectedTable.classList.add('selected');

  // Set the selected table value in the hidden input field
  const selectedTableInput = document.getElementById('selectedTable');
  selectedTableInput.value = tableId;
}

// Get the table element
const table = document.querySelector('.table');

// Function to update the booking status
function updateBookingStatus(isFull) {
  const bookingStatus = table.querySelector('.booking-status');

  // Remove existing classes
  bookingStatus.classList.remove('full');

  // Add class based on availability
  if (isFull) {
    bookingStatus.classList.add('full');
  }
}

// Add an event listener to track mouse movement
table.addEventListener('mousemove', (event) => {
  const rect = table.getBoundingClientRect();
  const mouseX = event.clientX - rect.left;
  const mouseY = event.clientY - rect.top;

  // Log the current mouse position relative to the table
  console.log('Mouse X:', mouseX, 'Mouse Y:', mouseY);

  // Add a class to the table when the mouse is over it
  table.classList.add('hovered');
});

// Remove the class when the mouse leaves the table
table.addEventListener('mouseleave', () => {
  table.classList.remove('hovered');
});

// Usage example
// Assuming you have an array of tables and their booking status
const tablesData = [
  { id: 1, isFull: false },
  { id: 2, isFull: true },
  // Add more tables and their booking status here
];

// Loop through the tables data and update the booking status
tablesData.forEach(tableData => {
  if (tableData.isFull) {
    // Mark the table as fully booked
    updateBookingStatus(true);
  } else {
    // Mark the table as available
    updateBookingStatus(false);
  }
});

document.addEventListener('DOMContentLoaded', function() {
  const anchorLinks = document.querySelectorAll('.header-right a[href^="#"]');
  anchorLinks.forEach(function(link) {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const targetId = this.getAttribute('href').substring(1);
      const targetElement = document.getElementById(targetId);
      if (targetElement) {
        targetElement.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });
});




