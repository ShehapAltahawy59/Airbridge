<?php
session_start();
require_once 'DB.php';  // Assume DB.php contains your database connection

// Fetch user data from the database
// Handle AJAX request to update user status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
  $newStatus = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

  // Validate input
  if (!$userId || !in_array($newStatus, ['active', 'banned'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
  }

  // Update user status in the database
  $sql = "UPDATE users SET status = ? WHERE UserID = ?";
  $stmt = $pdo->prepare($sql);
  if ($stmt->execute([$newStatus, $userId])) {
    echo json_encode(['success' => true, 'message' => 'User status updated']);
  } else {
    echo json_encode(['success' => false, 'message' => 'Failed to update user status']);
  }
  exit; // Stop further execution after handling the AJAX request
}
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ban a user
$stmt = $pdo->prepare("SELECT * FROM users WHERE UserID  = :user_id");

// Bind the parameter securely
$stmt->bindParam(':user_id', $_SESSION['UserID'], PDO::PARAM_INT);

// Execute the statement
$stmt->execute();

// Fetch the notifications
$user_ = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>How It Works</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    body {
      background-color: #8c8c8c;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
    }

    .notification-icon {
      position: relative;
      display: inline-block;
      cursor: pointer;
      font-size: 20px;
      margin-right: 20px;
      color: #ffc107;
      /* Icon color */
    }

    .notification-icon .badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: #dc3545;
      /* Red background for badge */
      color: white;
      font-size: 12px;
      padding: 3px 6px;
      border-radius: 50%;
    }

    .notification-icon:hover {
      color: #e0a800;
      /* Hover color for icon */
    }

    .navbar {
      background-color: #1d1e22;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      font-size: 24px;
      font-weight: bold;
      color: #ffc107 !important;
    }

    .nav-link {
      color: #fff !important;
      font-weight: 500;
    }

    .nav-link:hover {
      color: #ffc107 !important;
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255, 255, 255, 1)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
    }

    .wrapper-customized {
      margin-top: 100px;
      padding: 20px;
    }

    .iq-card {
      background-color: #1d1e22;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin: 20px 0;
    }

    .iq-card-header {
      background-color: #2a2b2f;
      padding: 15px;
      border-bottom: 1px solid #444;
      font-size: 18px;
      font-weight: 600;
      color: #ffc107;
      border-radius: 15px 15px 0 0;
    }

    .table {
      color: #fff !important;
      /* Ensure table text is white */
    }

    .table th,
    .table td {
      border-color: #ffffff !important;
      color: #fff !important;
      /* Explicitly set text color to white */
    }

    .table th {
      background-color: #2a2b2f;
    }

    .table tr:hover {
      background-color: #2a2b2f;
    }

    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }

    .btn-danger {
      background-color: #dc3545;
      border-color: #dc3545;
    }

    .btn-warning {
      background-color: #ffc107;
      border-color: #ffc107;
    }

    .btn-success {
      background-color: #28a745;
      border-color: #28a745;
    }

    .notification-icon {
      position: relative;
      display: inline-block;
      cursor: pointer;
      font-size: 20px;
      margin-right: 20px;
      color: #ffc107;
    }

    .notification-icon .badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: #dc3545;
      color: white;
      font-size: 12px;
      padding: 3px 6px;
      border-radius: 50%;
    }

    .chat-popup {
      display: none;
      position: absolute;
      top: 30px;
      right: 0;
      width: 300px;
      background: #1d1e22;
      border: 1px solid #444;
      border-radius: 5px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      z-index: 1000;
      padding: 15px;
    }

    .chat-popup h4 {
      margin: 0 0 10px;
      font-size: 16px;
      border-bottom: 1px solid #444;
      padding-bottom: 5px;
      color: #ffc107;
    }

    .chat-popup .message {
      margin-bottom: 10px;
      padding: 5px;
      background: #2a2b2f;
      border-radius: 5px;
      color: #fff;
    }

    .chat-popup input {
      width: 100%;
      padding: 10px;
      border: 1px solid #444;
      border-radius: 5px;
      margin-top: 10px;
      background-color: #2a2b2f;
      color: #fff;
    }

    .chat-popup input {
      width: 100%;
      padding: 10px;
      border: 1px solid #444;
      border-radius: 5px;
      margin-top: 10px;
      background-color: #2a2b2f;
      color: #fff;
    }

    .chat-popup .message.admin {
      text-align: left;
    }

    .chat-popup .message.user {
      text-align: right;
      color: #007bff;
    }

    .chat-popup input {
      width: 100%;
      padding: 10px;
      border: 1px solid #444;
      border-radius: 5px;
      margin-top: 10px;
      background-color: #2a2b2f;
      color: #fff;
    }

    /* Responsive Table Styles */
    @media (max-width: 768px) {
      .table-responsive {
        overflow-x: auto;
      }

      table {
        width: 100%;
      }

      thead {
        display: none;
        /* Hide the table headers on small screens */
      }

      tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #444;
        border-radius: 5px;
      }

      td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        border-bottom: 1px solid #444;
      }

      td::before {
        content: attr(data-label);
        /* Use data-label attribute for column names */
        font-weight: bold;
        margin-right: 1rem;
        flex: 1;
      }

      td:last-child {
        border-bottom: none;
      }

      .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
      }

      .btn-group .btn {
        flex: 1 1 auto;
      }

      .notification-icon {
        margin-right: 0;
      }
    }
  </style>
</head>

<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Fastest International Shipment</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['UserID'])): ?>
                <?php if ($user_[0]['user_type'] == 'requester'): ?>
                    <a class="nav-link" href="start_page.php">Dashboard</a>
                <?php elseif($user_[0]['user_type'] == 'traveler'): ?>
                        <a class="nav-link" href="start_page.php">Dashboard</a>
                <?php elseif($user_[0]['user_type'] == 'admin'): ?>
                        <a class="nav-link" href="Admin.php">Dashboard</a>
                <?php endif; ?>
            <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="AboutUs.php">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="HowToWork.php">How It Works</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://www.flightaware.com/">Track Your Request</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="ClientsContact.php?Type=User">Help</a>
          </li>
          <li class="nav-item">
            <?php if (isset($_SESSION['UserID'])): ?>
                <a class="nav-link" href="logout.php">Logout</a>
            <?php else: ?>
                <a class="nav-link" href="login.php">Login</a>
            <?php endif; ?>
        </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- User Details Table -->

  <section class="wrapper-customized">
    <div class="iq-card">
      <div class="iq-card-header">
        <h4 class="card-title">Users Details</h4>
      </div>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>UserID</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>User Type</th>
              <th>Created At</th>
              <th>Actions</th>
              <th>Notifications</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user): ?>
              <tr>
                <td data-label="UserID"><?php echo htmlspecialchars($user['UserID']); ?></td>
                <td data-label="Full Name"><?php echo htmlspecialchars($user['full_name']); ?></td>
                <td data-label="Email"><?php echo htmlspecialchars($user['email']); ?></td>
                <td data-label="User Type"><?php echo htmlspecialchars($user['user_type']); ?></td>
                <td data-label="Created At"><?php echo htmlspecialchars($user['CreatedAt']); ?></td>
                <td data-label="Actions">
                  <div class="btn-group" role="group">
                    <button class="btn btn-primary btn-sm">Edit</button>
                    <button class="btn btn-danger btn-sm">Delete</button>
                    <!-- Ban/Unban Button -->
                    <button class="btn btn-warning btn-sm ban-unban-btn"
                      data-user-id="<?php echo htmlspecialchars($user['UserID']); ?>"
                      data-status="<?php echo htmlspecialchars($user['status']); ?>">
                      <?php echo $user['status'] === 'banned' ? 'Unban' : 'Ban'; ?>
                    </button>
                  </div>
                </td>
                <td data-label="Notifications">
                  <div class="notification-icon" onclick="toggleChatPopup(event)">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                    <!-- Chat Popup -->
                    <div id="chatPopup" class="chat-popup">
                      <h4>Notifications</h4>
                      <div class="message">You have 3 new messages.</div>
                      <input type="text" placeholder="Type a message...">
                    </div>
                  </div>
                </td>

              </tr>
            <?php endforeach; ?>

          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this user?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Edit User Modal -->
  <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editUserForm">
            <input type="hidden" id="editUserId" name="user_id">
            <div class="mb-3">
              <label for="editFullName" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="editFullName" name="full_name" required>
            </div>
            <div class="mb-3">
              <label for="editEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="editEmail" name="email" required>
            </div>
            <div class="mb-3">
              <label for="editUserType" class="form-label">User Type</label>
              <select class="form-control" id="editUserType" name="user_type" required>
                <option value="requester">Sender</option>
                <option value="traveler">Traveler</option>
                <option value="admin">Admin</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="saveChangesBtn">Save changes</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Edit User Modal -->



  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const banUnbanButtons = document.querySelectorAll('.ban-unban-btn');

      banUnbanButtons.forEach(button => {
        button.addEventListener('click', function() {
          const userId = this.getAttribute('data-user-id');
          const currentStatus = this.getAttribute('data-status');
          const newStatus = currentStatus === 'active' ? 'banned' : 'active';

          // Send an AJAX request to update the user's status
          fetch(window.location.href, { // Send request to the same file
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
              },
              body: `action=update_status&user_id=${userId}&status=${newStatus}`,
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                // Update the button text and style
                if (newStatus === 'banned') {
                  this.textContent = 'Unban';
                  this.classList.remove('btn-warning');
                  this.classList.add('btn-success');
                } else {
                  this.textContent = 'Ban';
                  this.classList.remove('btn-success');
                  this.classList.add('btn-warning');
                }

                // Update the data-status attribute
                this.setAttribute('data-status', newStatus);
                window.location.reload();
              } else {
                alert('Failed to update user status: ' + data.message);
              }
            })
            .catch(error => {
              window.location.reload();

              console.error('Error:', error);
            });
        });
      });
    });
    $(document).ready(function() {
      // Open modal with user data when "Edit" button is clicked
      $('.btn-primary.btn-sm').on('click', function() {
        const row = $(this).closest('tr');
        const userId = row.find('td[data-label="UserID"]').text();
        const fullName = row.find('td[data-label="Full Name"]').text();
        const email = row.find('td[data-label="Email"]').text();
        const userType = row.find('td[data-label="User Type"]').text();

        $('#editUserId').val(userId);
        $('#editFullName').val(fullName);
        $('#editEmail').val(email);
        $('#editUserType').val(userType);

        $('#editUserModal').modal('show');
      });

      // Save changes when "Save changes" button is clicked
      $('#saveChangesBtn').on('click', function() {
        const formData = {
          user_id: $('#editUserId').val(),
          full_name: $('#editFullName').val(),
          email: $('#editEmail').val(),
          user_type: $('#editUserType').val(),
        };

        $.ajax({
          url: 'update_user.php',
          method: 'POST',
          data: formData,
          success: function(response) {
            alert('User updated successfully!');
            $('#editUserModal').modal('hide');
            location.reload(); // Reload the page to reflect changes
          },
          error: function(xhr, status, error) {
            alert('Error updating user: ' + error);
          },
        });
      });
    });
    $(document).ready(function() {
      let userIdToDelete = null; // Store the user ID to delete

      // Open confirmation modal when "Delete" button is clicked
      $('.btn-danger.btn-sm').on('click', function() {
        const row = $(this).closest('tr');
        userIdToDelete = row.find('td[data-label="UserID"]').text(); // Get the user ID
        $('#deleteUserModal').modal('show'); // Show the confirmation modal
      });

      // Handle the delete action when "Delete" button in modal is clicked
      $('#confirmDeleteBtn').on('click', function() {
        if (userIdToDelete) {
          $.ajax({
            url: 'delete_user.php',
            method: 'POST',
            data: {
              user_id: userIdToDelete
            },
            dataType: 'json', // Expect JSON response
            success: function(response) {
              console.log(response); // Log the response to the console
              if (response.status === 'success') {
                alert(response.message); // Show success message
                $('#deleteUserModal').modal('hide');

                // Remove the row from the table dynamically
                $(`tr:contains('${userIdToDelete}')`).remove();
              } else {
                alert(response.message); // Show error message
              }
            },
            error: function(xhr, status, error) {
              console.log(xhr.responseText); // Log the raw response for debugging
              $('#deleteUserModal').modal('hide');

              // Remove the row from the table dynamically
              $(`tr:contains('${userIdToDelete}')`).remove();
              // alert('AJAX error: ' + error); // Show AJAX error
            },
          });
        }
      });
    });

    function toggleChatPopup(event) {
      const icon = event.currentTarget; // Get the clicked notification icon
      const popup = icon.querySelector('#chatPopup'); // Find the popup inside the icon

      if (popup) {
        // Toggle the popup visibility
        popup.style.display = popup.style.display === 'block' ? 'none' : 'block';

        // Stop event propagation when clicking inside the popup
        popup.addEventListener('click', function(e) {
          e.stopPropagation(); // Prevent the click event from bubbling up
        });
      } else {
        console.error('chatPopup element not found');
      }
    }
  </script>
</body>

</html>
