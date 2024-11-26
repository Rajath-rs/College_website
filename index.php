<?php
include 'includes/connect.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if session variables are set
if (!isset($_SESSION['branch']) || !isset($_SESSION['semester'])) {
    echo "<p>Branch or Semester not set in session.</p>";
    exit();
}

$branch = $_SESSION['branch'];  // Assuming branch is stored in session
$semester = $_SESSION['semester'];  // Assuming semester is stored in session

// Fetch Resources based on User's Branch and Semester with Teacher Information
$query = "SELECT r.title, r.category, r.file_path, t.name AS uploaded_by 
          FROM resources r 
          LEFT JOIN teachers t ON r.uploaded_by = t.id 
          WHERE r.branch = ? AND r.semester = ? 
          ORDER BY r.id DESC LIMIT 5";

// Prepare and bind the query to prevent SQL injection
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Error preparing the query: ' . $conn->error);
}

$stmt->bind_param("si", $branch, $semester); // "s" for string, "i" for integer
$stmt->execute();
$result = $stmt->get_result(); // Execute and fetch result at the same time

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCE Student Resource Hub</title>
    <link rel="stylesheet" href="style.css">
    <style>
/* Style for chatbot container */

/* Floating Chatbot Icon */
#chatbot-icon {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: #007bff;
  border-radius: 50%;
  cursor: pointer;
  padding: 20px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  z-index: 999;
  transition: transform 0.3s ease-in-out;
  display: flex;
  align-items: center;
  justify-content: center;
}

#chatbot-icon:hover {
  transform: scale(1.1);
  background-color: #0056b3;
}

/* Chatbot Icon Design */
.chat-icon {
  position: relative;
  width: 40px;
  height: 40px;
  background-color: white;
  border-radius: 50%;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  color: #007bff;
}

.chat-icon:before {
  content: '💬'; /* Chat bubble icon */
  font-size: 20px;
}

/* Chatbot Section */
#chatbot {
  position: fixed;
  bottom: 90px;
  right: 20px;
  width: 350px;
  max-height: 450px;
  background-color: #1f1f1f;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 20px rgba(0, 0, 0, 0.4);
  display: none;
  z-index: 1000;
  overflow: hidden;
  animation: fadeIn 0.3s ease-in-out;
}

/* Smooth transition for showing/hiding chatbot */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Popup message styling (Greeting) */
#popup-message {
  background-color: #007bff;
  color: white;
  padding: 10px;
  border-radius: 8px;
  text-align: center;
  font-size: 16px;
  margin-bottom: 15px;
}

/* Chatbox where chat messages appear */
#chat-box {
  max-height: 300px;
  overflow-y: auto;
  margin-bottom: 15px;
  border: 1px solid #444;
  padding: 15px;
  background-color: #2a2a2a;
  border-radius: 8px;
  box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.4);
  color: #ddd;
}

/* User and AI message styles */
.user-message, .ai-message {
  margin: 5px 0;
  padding: 10px;
  border-radius: 8px;
  max-width: 80%;
  display: inline-block;
  word-wrap: break-word;
}

.user-message {
  background-color: #007bff;
  color: #fff;
  text-align: right;
  margin-left: 10%;
}

.ai-message {
  background-color: #333;
  color: #fff;
  text-align: left;
  margin-right: 10%;
}

/* Input field and Send button */
#user-message {
  width: calc(100% - 50px);
  padding: 12px;
  margin-top: 15px;
  border-radius: 10px;
  border: 1px solid #444;
  background-color: #2a2a2a;
  color: #ddd;
}

button {
  padding: 12px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s;
}

button:hover {
  background-color: #0056b3;
}

/* Input and button container */
#send-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 10px;
}

/* Styling the scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-thumb {
  background-color: #444;
  border-radius: 10px;
}

::-webkit-scrollbar-track {
  background-color: #222;
}

/* Focus effects for input fields */
#user-message:focus {
  outline: none;
  border: 1px solid #007bff;
}

/* Focus effect for buttons */
button:focus {
  outline: none;
  box-shadow: 0 0 5px 3px rgba(0, 123, 255, 0.5);
}
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>MCE HASSAN Student Resource Hub</h1>
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="cgpa.html">CGPA Calculator</a></li>
                    <li><a href="carrer.html">Carrier Development </a></li>
                    <li><a href="#resources">Resources</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="hero">
        <div class="hero-text">
            <h2>Your One-Stop Solution for Academic Resources</h2>
            <p>Access lecture notes, study materials, and more.</p>
            <a href="#resources" class="btn">Explore Resources</a>
        </div>
    </section>

    <section id="resources">
        <div class="container">
            <h2>Recent Resources</h2>
            <ul>
                <?php
                // Display the resources fetched from the database
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <li>
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p>Category: <?php echo htmlspecialchars($row['category']); ?></p>
                            <p>Uploaded By: <?php echo htmlspecialchars($row['uploaded_by'] ?? 'Admin'); ?></p>
                            <a href="<?php echo htmlspecialchars($row['file_path']); ?>" download>Download</a>
                        </li>
                        <?php
                    }
                } else {
                    echo "<p>No resources found for your branch and semester.</p>";
                }
                ?>
            </ul>
        </div>
    </section>
    <div id="chatbot-icon" onclick="toggleChatbot()">
    <div class="chat-icon"></div>
    </div>

    <!-- Chatbot Section (Initially hidden) -->
    <section id="chatbot" style="display:none;">
        <div class="container">
            <h2>Ask a Question</h2>
            <div id="chat-box" class="chat-box">
                <!-- Chat messages will appear here -->
            </div>
            <input type="text" id="user-message" placeholder="Ask a question..." autocomplete="off">
            <button onclick="sendMessage()">Send</button>
        </div>
    </section>


    <footer>
        <p>© 2024 MCE Student Resource Hub. All Rights Reserved.</p>
    </footer>

    <script>
// Event listener for the button click (if needed)
document.querySelector('.btn').addEventListener('click', function() {
    alert('Let’s explore the resources!');
});

// Toggle the chatbot visibility
function toggleChatbot() {
    const chatbotSection = document.getElementById('chatbot');
    const popupMessage = document.getElementById('popup-message');

    // Toggle chatbot and popup message visibility
    if (chatbotSection.style.display === 'none' || chatbotSection.style.display === '') {
        // Show the chatbot and hide the popup message
        chatbotSection.style.display = 'flex';
        popupMessage.style.display = 'none';
    } else {
        // Hide the chatbot and show the popup message again
        chatbotSection.style.display = 'none';
        popupMessage.style.display = 'block';
    }
}

// Ensure proper initial display state on page load
window.onload = function() {
    const chatbotSection = document.getElementById('chatbot');
    const popupMessage = document.getElementById('popup-message');

    // Chatbot is hidden, and popup message is visible initially
    chatbotSection.style.display = 'none';
    popupMessage.style.display = 'block';
};

// Function to send the message to the Flask API and display responses
function sendMessage() {
    const message = document.getElementById('user-message').value;
    if (message.trim() === '') return; // Don't send empty messages

    // Append the user's message to the chat box
    const chatBox = document.getElementById('chat-box');
    const userMessageElement = document.createElement('div');
    userMessageElement.classList.add('user-message');
    userMessageElement.textContent = message;
    chatBox.appendChild(userMessageElement);

    // Clear the input field
    document.getElementById('user-message').value = '';

    // Scroll to the bottom of the chat box
    chatBox.scrollTop = chatBox.scrollHeight;

    // Call Flask API to get AI response
    fetch('http://192.168.151.80:5000/chat', { // Replace with your Flask API URL
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ question: message })
    })
        .then(response => response.json())
        .then(data => {
            // Append AI's response to the chat box
            const aiMessageElement = document.createElement('div');
            aiMessageElement.classList.add('ai-message');
            aiMessageElement.textContent = data.response;
            chatBox.appendChild(aiMessageElement);

            // Scroll to the bottom of the chat box after AI message
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Event listener for Enter key to send message
document.getElementById('user-message').addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        sendMessage(); // Call sendMessage on pressing Enter
    }
});
</script>


    
   

</body>
</html>
