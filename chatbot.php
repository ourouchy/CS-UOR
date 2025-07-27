<?php
// chatbot.php - Mini chatbot simple
session_start();

// Initialiser l'historique des conversations
if (!isset($_SESSION['chat_history'])) {
    $_SESSION['chat_history'] = [];
}

// Fonction pour analyser et répondre aux messages
function getBotResponse($message) {
    $message = strtolower(trim($message));
    
    // Salutations
    if (strpos($message, 'bonjour') !== false || strpos($message, 'hello') !== false || strpos($message, 'salut') !== false) {
        $responses = [
            "Bonjour ! Comment puis-je vous aider aujourd'hui ?",
            "Salut ! Que puis-je faire pour vous ?",
            "Hello ! Je suis là pour répondre à vos questions !"
        ];
        return $responses[array_rand($responses)];
    }
    
    if (strpos($message, 'bonsoir') !== false) {
        $responses = [
            "Bonsoir ! J'espère que vous passez une bonne soirée !",
            "Bonsoir ! Comment puis-je vous aider ce soir ?",
            "Bonne soirée ! Que puis-je faire pour vous ?"
        ];
        return $responses[array_rand($responses)];
    }
    
    // Question 1: Nom du bot
    if (strpos($message, 'comment tu t\'appelles') !== false || strpos($message, 'quel est ton nom') !== false || strpos($message, 'qui es-tu') !== false) {
        return "Je suis ChatBot, votre assistant virtuel ! Je suis là pour répondre à vos questions simples.";
    }
    
    // Question 2: Météo
    if (strpos($message, 'météo') !== false || strpos($message, 'temps') !== false || strpos($message, 'il fait beau') !== false) {
        return "Je ne peux pas consulter la météo en temps réel, mais j'espère qu'il fait beau chez vous ! ☀️";
    }
    
    // Question 3: Heure
    if (strpos($message, 'heure') !== false || strpos($message, 'quelle heure') !== false) {
        return "Il est actuellement " . date('H:i') . " !";
    }
    
    // Question 4: Couleur préférée
    if (strpos($message, 'couleur préférée') !== false || strpos($message, 'couleur favorite') !== false) {
        return "Ma couleur préférée est le bleu ! Et vous, quelle est la vôtre ? 💙";
    }
    
    // Question 5: Âge
    if (strpos($message, 'quel âge') !== false || strpos($message, 'ton âge') !== false) {
        return "Je suis un chatbot, donc je n'ai pas d'âge ! Mais j'ai été créé aujourd'hui ! 🤖";
    }
    
    // Question 6: Hobbies
    if (strpos($message, 'hobby') !== false || strpos($message, 'loisir') !== false || strpos($message, 'aimes-tu') !== false) {
        return "J'aime discuter avec les gens et répondre à leurs questions ! C'est ma passion ! 😊";
    }
    
    // Au revoir
    if (strpos($message, 'au revoir') !== false || strpos($message, 'bye') !== false || strpos($message, 'à bientôt') !== false) {
        return "Au revoir ! N'hésitez pas à revenir me parler bientôt ! 👋";
    }
    
    // Merci
    if (strpos($message, 'merci') !== false || strpos($message, 'thanks') !== false) {
        return "De rien ! Je suis content de pouvoir vous aider ! 😊";
    }
    
    // Réponse par défaut
    $defaultResponses = [
        "Je ne suis pas sûr de comprendre. Pouvez-vous reformuler ?",
        "Hmm, je ne connais pas la réponse à cette question. Essayez de me dire bonjour ou demandez-moi mon nom !",
        "Je suis encore en apprentissage ! Posez-moi des questions simples comme 'Quelle heure est-il ?' ou 'Quel est ton nom ?'",
        "Désolé, je ne comprends pas. Vous pouvez me demander l'heure, mon nom, ou simplement me dire bonjour !"
    ];
    
    return $defaultResponses[array_rand($defaultResponses)];
}

// Traitement des messages AJAX
if ($_POST['action'] === 'send_message') {
    $userMessage = $_POST['message'];
    $botResponse = getBotResponse($userMessage);
    
    // Ajouter à l'historique
    $_SESSION['chat_history'][] = ['user' => $userMessage, 'bot' => $botResponse, 'time' => date('H:i')];
    
    echo json_encode(['response' => $botResponse]);
    exit;
}

// Récupérer l'historique
if ($_POST['action'] === 'get_history') {
    echo json_encode($_SESSION['chat_history']);
    exit;
}

// Vider l'historique
if ($_POST['action'] === 'clear_history') {
    $_SESSION['chat_history'] = [];
    echo json_encode(['success' => true]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini ChatBot</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin-bottom: 10px;
        }

        .chat-container {
            height: 400px;
            overflow-y: auto;
            padding: 20px;
            background: #f8f9fa;
        }

        .message {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }

        .message.user {
            justify-content: flex-end;
        }

        .message-content {
            max-width: 70%;
            padding: 12px 18px;
            border-radius: 20px;
            word-wrap: break-word;
        }

        .message.user .message-content {
            background: #007bff;
            color: white;
            border-bottom-right-radius: 5px;
        }

        .message.bot .message-content {
            background: white;
            color: #333;
            border: 1px solid #ddd;
            border-bottom-left-radius: 5px;
        }

        .message-time {
            font-size: 11px;
            opacity: 0.7;
            margin: 5px 10px 0;
        }

        .input-container {
            padding: 20px;
            background: white;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
        }

        #messageInput {
            flex: 1;
            padding: 12px 18px;
            border: 2px solid #ddd;
            border-radius: 25px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
        }

        #messageInput:focus {
            border-color: #007bff;
        }

        #sendBtn {
            padding: 12px 25px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        #sendBtn:hover {
            background: #0056b3;
        }

        #clearBtn {
            padding: 8px 15px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-size: 12px;
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .suggestions {
            padding: 15px 20px;
            background: #f1f3f4;
            border-top: 1px solid #ddd;
        }

        .suggestions h4 {
            margin-bottom: 10px;
            color: #555;
        }

        .suggestion-btn {
            display: inline-block;
            padding: 5px 12px;
            margin: 3px;
            background: #e9ecef;
            border: 1px solid #ced4da;
            border-radius: 15px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s;
        }

        .suggestion-btn:hover {
            background: #007bff;
            color: white;
        }

        .typing-indicator {
            display: none;
            padding: 10px 0;
        }

        .typing-dots {
            display: inline-block;
        }

        .typing-dots span {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #999;
            margin: 0 2px;
            animation: typing 1.4s infinite ease-in-out;
        }

        .typing-dots span:nth-child(1) { animation-delay: -0.32s; }
        .typing-dots span:nth-child(2) { animation-delay: -0.16s; }

        @keyframes typing {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }

        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 10px;
            }
            
            .message-content {
                max-width: 85%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <button id="clearBtn" onclick="clearChat()">Effacer</button>
            <h1>🤖 Mini ChatBot</h1>
            <p>Votre assistant virtuel personnel</p>
        </div>
        
        <div class="chat-container" id="chatContainer">
            <div class="message bot">
                <div class="message-content">
                    Bonjour ! Je suis votre ChatBot. Je peux répondre à vos salutations et à plusieurs questions simples. Tapez votre message ci-dessous ! 😊
                </div>
            </div>
        </div>
        
        <div class="typing-indicator" id="typingIndicator">
            🤖 <span class="typing-dots"><span></span><span></span><span></span></span> ChatBot est en train d'écrire...
        </div>
        
        <div class="suggestions">
            <h4>💡 Suggestions :</h4>
            <span class="suggestion-btn" onclick="sendSuggestion('Bonjour')">Bonjour</span>
            <span class="suggestion-btn" onclick="sendSuggestion('Comment tu t\'appelles ?')">Ton nom</span>
            <span class="suggestion-btn" onclick="sendSuggestion('Quelle heure est-il ?')">L'heure</span>
            <span class="suggestion-btn" onclick="sendSuggestion('Quel est ton âge ?')">Ton âge</span>
            <span class="suggestion-btn" onclick="sendSuggestion('Quelle est ta couleur préférée ?')">Couleur préférée</span>
            <span class="suggestion-btn" onclick="sendSuggestion('Quels sont tes hobbies ?')">Tes hobbies</span>
        </div>
        
        <div class="input-container">
            <input type="text" id="messageInput" placeholder="Tapez votre message ici..." onkeypress="handleEnter(event)">
            <button id="sendBtn" onclick="sendMessage()">Envoyer</button>
        </div>
    </div>

    <script>
        // Charger l'historique au démarrage
        window.onload = function() {
            loadChatHistory();
        };

        function loadChatHistory() {
            fetch('chatbot.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=get_history'
            })
            .then(response => response.json())
            .then(history => {
                const chatContainer = document.getElementById('chatContainer');
                chatContainer.innerHTML = '<div class="message bot"><div class="message-content">Bonjour ! Je suis votre ChatBot. Je peux répondre à vos salutations et à plusieurs questions simples. Tapez votre message ci-dessous ! 😊</div></div>';
                
                history.forEach(chat => {
                    addMessageToChat(chat.user, 'user', chat.time);
                    addMessageToChat(chat.bot, 'bot', chat.time);
                });
                
                scrollToBottom();
            });
        }

        function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            
            if (message === '') return;
            
            // Afficher le message de l'utilisateur
            addMessageToChat(message, 'user');
            input.value = '';
            
            // Afficher l'indicateur de frappe
            showTypingIndicator();
            
            // Envoyer le message au serveur
            setTimeout(() => {
                fetch('chatbot.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'action=send_message&message=' + encodeURIComponent(message)
                })
                .then(response => response.json())
                .then(data => {
                    hideTypingIndicator();
                    addMessageToChat(data.response, 'bot');
                    scrollToBottom();
                })
                .catch(error => {
                    hideTypingIndicator();
                    addMessageToChat('Désolé, une erreur s\'est produite. Veuillez réessayer.', 'bot');
                });
            }, 1000); // Délai pour simuler le temps de réflexion
        }

        function addMessageToChat(message, sender, time = null) {
            const chatContainer = document.getElementById('chatContainer');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${sender}`;
            
            const currentTime = time || new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'});
            
            messageDiv.innerHTML = `
                <div class="message-content">${message}</div>
                <div class="message-time">${currentTime}</div>
            `;
            
            chatContainer.appendChild(messageDiv);
            scrollToBottom();
        }

        function scrollToBottom() {
            const chatContainer = document.getElementById('chatContainer');
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function showTypingIndicator() {
            document.getElementById('typingIndicator').style.display = 'block';
        }

        function hideTypingIndicator() {
            document.getElementById('typingIndicator').style.display = 'none';
        }

        function handleEnter(event) {
            if (event.key === 'Enter') {
                sendMessage();
            }
        }

        function sendSuggestion(message) {
            document.getElementById('messageInput').value = message;
            sendMessage();
        }

        function clearChat() {
            if (confirm('Êtes-vous sûr de vouloir effacer l\'historique du chat ?')) {
                fetch('chatbot.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'action=clear_history'
                })
                .then(() => {
                    loadChatHistory();
                });
            }
        }
    </script>
</body>
</html>
