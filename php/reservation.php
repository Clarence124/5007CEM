<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve and sanitize form data using filtering functions
  $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
  $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $tableType = filter_input(INPUT_POST, 'tableType', FILTER_SANITIZE_STRING);
  $guestNumber = filter_input(INPUT_POST, 'guestNumber', FILTER_VALIDATE_INT);
  $placement = filter_input(INPUT_POST, 'placement', FILTER_SANITIZE_STRING);
  $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
  $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_STRING);
  $note = filter_input(INPUT_POST, 'note', FILTER_SANITIZE_STRING);

  // Perform necessary validation checks
  if (
    $firstName && $lastName && $email && $tableType && $guestNumber &&
    is_numeric($guestNumber) && $placement && $date && $time
  ) {
    // Connect to the MySQL database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "restauranttable.db";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the database query with prepared statements
    $stmt = $conn->prepare("INSERT INTO reservations (fname, lname, email, table_type, Guest_Number, placement, date, time, Note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssissss", $firstName, $lastName, $email, $tableType, $guestNumber, $placement, $date, $time, $note);

    if ($stmt->execute()) {
      echo "<script>alert('Reservation successfully saved.');</script>";
    } else {
      echo "Error: " . $stmt->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
  }
}
?>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reservation.css" type="text/css">
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
  <title>Reservation</title>
  <script src="reservation.js"></script>
</head>

<body>
  <div class="homepage">
    <div class="header">
      <div class="header-left"><img src="pictures/logo.png" width="300" height="150" alt="Logo"></div>
      <div class="header-right">
        <ul>
          <li><a href="homepage.php">Homepage</a></li>
          <li><a href="reservation.php">Reservation</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="about.php">About Us</a></li>
          <li><a href="profile.php">Profile</a></li>
          <li><a href="Login.php">Log out</a></li>
        </ul>
      </div>
      <div class="header-bottom">
        <div class="header-font">Eat healthy food &amp; Enjoy your life.</div>
        <div class="p50_0" align="center">
          <a href="reservation.php" class="header-btns">BOOK A TABLE</a>&nbsp; &nbsp; &nbsp; &nbsp;</a>
        </div>
      </div>
    </div>


    <section class="reservation" data-aos="fade-up">
      <div class="container">
        <div class="form__group">
          <label for="table">Table</label>
          <div class="seating-chart">
            <div class="table" id="table1" onclick="selectTable('table1')">
              <div class="table-overlay">
                <img class="table-image" src="pictures/reservationtable.png" alt="Table 1">
                <div class="table-name">Table 1</div>
                <div class="booking-status"></div>
              </div>
            </div>
            <div class="table" id="table2" onclick="selectTable('table2')">
              <div class="table-overlay">
                <img class="table-image" src="pictures/reservationtable.png" alt="Table 1">
                <div class="table-name">Table 2</div>
              </div>
            </div>
            <div class="table" id="table3" onclick="selectTable('table3')">
              <div class="table-overlay">
                <img class="table-image" src="pictures/reservationtable.png" alt="Table 1">
                <div class="table-name">Table 3</div>
              </div>
            </div>
            <div class="table" id="table4" onclick="selectTable('table4')">
              <div class="table-overlay">
                <img class="table-image" src="pictures/reservationtable.png" alt="Table 1">
                <div class="table-name">Table 4</div>
              </div>
            </div>
            <input type="hidden" id="selectedTable" name="selectedTable">
          </div>

          <div class="form__wrapper" id="reservationForm" style="display: none;">
            <h3 class="form__title">Book Table</h3>
            <form name="booking" onsubmit="return validateForm()">
              <div class="form__group">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" required>
              </div>
              <div class="form__group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" required>
              </div>
              <div class="form__group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
              </div>
              <div class="form__group">
                <label for="tableType">Table Type</label>
                <select name="tableType" id="tableType" required>
                  <option selected disabled>Choose</option>
                  <option value="small">Small (2 persons)</option>
                  <option value="medium">Medium (4 persons)</option>
                  <option value="large">Large (6 persons)</option>
                </select>
              </div>
              <div class="form__group">
                <label for="guestNumber">Guest Number</label>
                <input type="number" id="guestNumber" name="guestNumber" min="1" max="10" required>
              </div>
              <div class="form__group">
                <label for="placement">Placement</label>
                <select name="placement" id="placement">
                  <option selected disabled>Choose</option>
                  <option value="outdoor">Outdoor</option>
                  <option value="indoor">Indoor</option>
                  <option value="rooftop">Rooftop</option>
                </select>
              </div>
              <div class="form__group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" required>
              </div>
              <div class="form__group">
                <label for="time">Time</label>
                <input type="time" id="time" name="time" required>
              </div>
              <div class="form__group form__group__full">
                <label for="note">Note</label>
                <textarea name="note" id="note" rows="4"></textarea>
              </div>
              <button type="submit" class="btn primary-btn">Book Table</button>
            </form>
          </div>
        </div>
        <div class="table-button">
          <button onclick="openReservationForm()">Choose Table</button>
          <button type="button" onclick="cancelSelection()">Cancel</button>
        </div>
    </section>

    <footer>
      <p>&copy; 2023 Restaurant Name. All rights reserved.</p>
    </footer>
  </div>

  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script src="reservation.js"></script>
  <script>
    AOS.init()

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

    document.addEventListener('DOMContentLoaded', function () {
      const anchorLinks = document.querySelectorAll('.header-right a[href^="#"]');
      anchorLinks.forEach(function (link) {
        link.addEventListener('click', function (e) {
          e.preventDefault();
          const targetId = this.getAttribute('href').substring(1);
          const targetElement = document.getElementById(targetId);
          if (targetElement) {
            targetElement.scrollIntoView({ behavior: 'smooth' });
          }
        });
      });
    });
  </script>
</body>

</html>