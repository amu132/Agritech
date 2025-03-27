<?php
include ('fsession.php');
ini_set('memory_limit', '-1');

if(!isset($_SESSION['farmer_login_user'])){
    header("location: ../index.php");
} 

$query4 = "SELECT * from farmerlogin where email='$user_check'";
$ses_sq4 = mysqli_query($conn, $query4);
$row4 = mysqli_fetch_assoc($ses_sq4);
$para1 = $row4['farmer_id'];
$para2 = $row4['farmer_name'];
?>

<!DOCTYPE html>
<html>
<?php require ('fheader.php'); ?>
<body class="bg-white" id="top">
<?php include ('fnav.php'); ?>

<section class="section section-shaped section-lg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card text-white bg-gradient-white mt--6">
                    <div class="card-header bg-gradient-success d-flex align-items-center">
                        <span class="text-white display-4">
                            <img src="../assets/img/farmer-bot.png" class="rounded-circle img-fluid" style="width: 50px; height: 50px; object-fit: cover;" alt="Farmer Bot"> 
                            Farmer's AI Assistant
                        </span>
                        <div class="ml-auto">
                            <button class="btn btn-light" onclick="window.print()">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button class="btn btn-danger mr-2" onclick="clearContent()">
                                <i class="fas fa-trash"></i> Clear Chat
                            </button>                    
                        </div>                                
                    </div>
                    <div class="card-body chat-box rounded p-4" id="chatbox" style="height: 60vh; overflow-y: auto; background: #f5f5f5;">
                        <div class="message left-side">
                            <div class="message-header">
                                <i class="fas fa-robot mr-2"></i> Farmer's Assistant
                            </div>
                            Hello! I'm your farming assistant. I can help you with:
                            <ul>
                                <li>Crop recommendations</li>
                                <li>Pest control advice</li>
                                <li>Weather-related farming tips</li>
                                <li>Soil management</li>
                                <li>Modern farming techniques</li>
                            </ul>
                            How can I assist you today?
                        </div>
                    </div>
                    <div class="card-footer bg-gradient-success">
                        <div class="form-group row mb-0">
                            <div class="col-md-10 mb-1">
                                <input id="userInput" type="text" class="form-control text-dark" 
                                    placeholder="Ask me anything about farming..." 
                                    onkeypress="if(event.keyCode==13) sendMessage()"/>
                            </div>
                            <div class="col-md-2">
                                <button id="sendButton" class="btn btn-light btn-block">
                                    <i class="fas fa-paper-plane"></i> Send
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require("footer.php"); ?>

<style>
.chat-box {
    background: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.message {
    margin: 15px;
    padding: 15px 20px;
    border-radius: 15px;
    max-width: 80%;
    clear: both;
    font-size: 16px;
    line-height: 1.6;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    color: #333;
}
.message-header {
    font-weight: bold;
    margin-bottom: 8px;
    color: #1a365d;
    font-size: 1.1em;
}
.left-side {
    background: #ffffff;
    float: left;
    border-left: 4px solid #38a169;
    color: #2d3748;
}
.right-side {
    background: #38a169;
    color: #ffffff !important;
    float: right;
    border-right: 4px solid #2f855a;
}
.right-side .message-header {
    color: #ffffff;
}
.message ul {
    margin-top: 10px;
    margin-bottom: 5px;
    padding-left: 20px;
    color: #2d3748;
}
.message li {
    margin-bottom: 8px;
    font-size: 15px;
}
#userInput {
    border: 2px solid #38a169;
    border-radius: 20px;
    padding: 12px 20px;
    font-size: 16px;
    color: #2d3748;
}
#userInput::placeholder {
    color: #718096;
}
#userInput:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(56, 161, 105, 0.2);
}
.btn-success {
    background-color: #38a169;
    border-color: #2f855a;
}
.typing-indicator {
    background: #ffffff;
    padding: 10px 20px;
    border-radius: 15px;
    display: none;
    float: left;
    margin: 15px;
    border-left: 4px solid #38a169;
    color: #2d3748;
}
.card-header {
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
}
.card-header .text-white {
    font-weight: 600;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}
.btn {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>

<script>
const GEMINI_API_KEY = 'AIzaSyDfg2KFVli7Cb106vaewL7Mwr4ZKi8gM-Q';
const chatbox = document.getElementById("chatbox");
const userInput = document.getElementById("userInput");
const sendButton = document.getElementById("sendButton");

function clearContent() {
    chatbox.innerHTML = `<div class="message left-side">
        <div class="message-header">
            <i class="fas fa-robot mr-2"></i> Farmer's Assistant
        </div>
        Hello! I'm your farming assistant. I can help you with:
        <ul>
            <li>Crop recommendations</li>
            <li>Pest control advice</li>
            <li>Weather-related farming tips</li>
            <li>Soil management</li>
            <li>Modern farming techniques</li>
        </ul>
        How can I assist you today?
    </div>`;
}

function addMessage(message, isUser = false) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${isUser ? 'right-side' : 'left-side'}`;
    
    if (!isUser) {
        messageDiv.innerHTML = `
            <div class="message-header">
                <i class="fas fa-robot mr-2"></i> Farmer's Assistant
            </div>
            ${message}
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="message-header">
                <i class="fas fa-user mr-2"></i> You
            </div>
            ${message}
        `;
    }
    
    chatbox.appendChild(messageDiv);
    chatbox.scrollTop = chatbox.scrollHeight;
}

async function sendMessage() {
    const message = userInput.value.trim();
    if (!message) return;
    
    addMessage(message, true);
    userInput.value = "";
    sendButton.disabled = true;
    sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    try {
        const response = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${GEMINI_API_KEY}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                contents: [{
                    parts: [{
                        text: message
                    }]
                }]
            })
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error("API Error:", errorData);
            throw new Error(errorData.error?.message || `HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log("API Response:", data);

        if (!data.candidates || data.candidates.length === 0) {
            throw new Error('No response from AI');
        }

        let botReply = data.candidates[0].content.parts[0].text;
        
        // Format the response for better readability
        botReply = botReply.replace(/\n/g, '<br>');
        addMessage(botReply);

    } catch (error) {
        console.error("Error details:", error);
        addMessage(`I apologize, but there was an error: ${error.message}`);
    } finally {
        sendButton.disabled = false;
        sendButton.innerHTML = '<i class="fas fa-paper-plane"></i> Send';
    }
}

async function testAPI() {
    try {
        const response = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${GEMINI_API_KEY}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                contents: [{
                    parts: [{
                        text: "Hello"
                    }]
                }]
            })
        });
        
        const data = await response.json();
        console.log("Test API Response:", data);
        return data;
    } catch (error) {
        console.error("Test API Error:", error);
        return error;
    }
}

// Event listeners
sendButton.addEventListener("click", sendMessage);
userInput.addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        sendMessage();
    }
});

// Test the API when the page loads
testAPI().then(result => {
    console.log("API Test Result:", result);
    if (result.error) {
        console.error("API Test Error:", result.error);
    }
});

// Auto-focus the input field
userInput.focus();
</script>

</body>
</html>

