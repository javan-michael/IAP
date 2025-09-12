<?php

global $conn;
require_once 'plugins/database.php';
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Registered Users</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
            }
            ol {
                width: 80%;
                margin: 0 auto;
            }
            li {
                padding: 10px;
                margin: 5px 0;
                background-color: #f5f5f5;
                border-radius: 5px;
            }
        </style>
    </head>
    <body>
    <h1>Registered Users</h1>
    <form action="/mail.php" method="post">
  <input type="text" name="username" placeholder="Username" required />
  <input type="email" name="email" placeholder="Email" required />
  <button type="submit">Submit</button>
</form>
    <?php
    if (!$conn) {
        die("Connection failed");
    }

    // Query to select all users in ascending order by ID
    $sql = "SELECT id, username, email, verified FROM users ORDER BY id ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<ol>"; // Creates an ordered (numbered) list
        while($row = $result->fetch_assoc()) {
            $verificationStatus = $row["verified"] ? "Verified" : "Not Verified";
            echo "<li>Username: " . $row["username"] .
                " | Email: " . $row["email"] .
                " | Status: " . $verificationStatus . "</li>";
        }
        echo "</ol>";
    } else {
        echo "No users found";
    }

    ?>
    </body>
    </html>
<?php
$conn->close();
?>