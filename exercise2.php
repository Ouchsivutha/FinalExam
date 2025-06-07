<?php

function saveContactForm($name, $email, $message) {
   
    $name = htmlspecialchars(strip_tags($name), ENT_QUOTES, 'UTF-8');
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(strip_tags($message), ENT_QUOTES, 'UTF-8');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email address.";
    }

    
    $host = "localhost";
    $user = "root";
    $pass = "Year@2024";
    $dbname = "messages"; 

    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        return "Connection failed: " . $conn->connect_error;
    }

    $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    if (!$stmt) {
        return "Prepare failed: " . $conn->error;
    }

    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "Message successfully saved.";
    } else {
        $stmt->close();
        $conn->close();
        return "Error saving message: " . $stmt->error;
    }
}
?>


<form method="post" action="">
  <input type="text" name="name" placeholder="Your Name" required><br>
  <input type="email" name="email" placeholder="Your Email" required><br>
  <textarea name="message" placeholder="Your Message" required></textarea><br>
  <input type="submit" value="Send">
</form>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $result = saveContactForm($_POST['name'], $_POST['email'], $_POST['message']);
    echo "<p>$result</p>";
}
?>
