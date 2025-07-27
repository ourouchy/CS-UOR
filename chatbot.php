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
            "Hello ! Je suis la pour repondre a vos questions !"
        ];
        return $responses[array_rand($responses)];
    }
    
    if (strpos($message, 'bonsoir') !== false) {
        $responses = [
            "Bonsoir ! J'espere que vous passez une bonne soiree !",
            "Bonsoir ! Comment puis-je vous aider ce soir ?",
            "Bonne soiree ! Que puis-je faire pour vous ?"
        ];
        return $responses[array_rand($responses)];
    }
    
    // Question 1: Nom du bot
    if (strpos($message, 'comment tu t\'appelles') !== false || strpos($message, 'quel est ton nom') !== false || strpos($message, 'qui es-tu') !== false) {
        return "Je suis ChatBot, votre assistant virtuel ! Je suis la pour repondre a vos questions simples.";
    }
    
    // Question 2: Météo
    if (strpos($message, 'météo') !== false || strpos($message, 'temps') !== false || strpos($message, 'il fait beau') !== false) {
        return "Je ne peux pas consulter la meteo en temps reel, mais j'espere qu'il fait beau chez vous !";
    }
    
    // Question 3: Heure
    if (strpos($message, 'heure') !== false || strpos($message, 'quelle heure') !== false) {
        return "Il est actuellement " . date('H:i') . " !";
    }
    
    // Question 4: Couleur préférée
    if (strpos($message, 'couleur préférée') !== false || strpos($message, 'couleur favorite') !== false) {
        return "Je n'ai pas vraiment de couleur preferee. Et vous, quelle est la votre ?";
    }
    
    // Question 5: Âge
    if (strpos($message, 'quel âge') !== false || strpos($message, 'ton âge') !== false) {
        return "Je suis un chatbot, donc je n'ai pas d'age ! Mais j'ai ete cree aujourd'hui !";
    }
    
    // Question 6: Hobbies
    if (strpos($message, 'hobby') !== false || strpos($message, 'hobbies') !== false || strpos($message, 'loisir') !== false || strpos($message, 'aimes-tu') !== false) {
        return "J'aime discuter avec les gens et repondre a leurs questions ! C'est ma passion !";
    }
    
    // Au revoir
    if (strpos($message, 'au revoir') !== false || strpos($message, 'bye') !== false || strpos($message, 'à bientôt') !== false) {
        return "Au revoir ! N'hesitez pas a revenir me parler bientot !";
    }
    
    // Merci
    if (strpos($message, 'merci') !== false || strpos($message, 'thanks') !== false) {
        return "De rien ! Je suis content de pouvoir vous aider !";
    }
    
    // Réponses aux couleurs
    if (preg_match('/^(rouge|bleu|vert|jaune|noir|blanc|rose|violet|orange|gris|marron)$/i', trim($message))) {
        return "Ah, " . ucfirst(trim($message)) . " ! C'est une belle couleur ! Merci de me l'avoir dit.";
    }
    
    // Réponse par défaut
    $defaultResponses = [
        "Je ne suis pas sur de comprendre. Pouvez-vous reformuler ?",
        "Hmm, je ne connais pas la reponse a cette question. Essayez de me dire bonjour ou demandez-moi mon nom !",
        "Je suis encore en apprentissage ! Posez-moi des questions simples comme 'Quelle heure est-il ?' ou 'Quel est ton nom ?'",
        "Desole, je ne comprends pas. Vous pouvez me demander l'heure, mon nom, ou simplement me dire bonjour !"
    ];
    
    return $defaultResponses[array_rand($defaultResponses)];
}

// Traitement des messages AJAX
if (isset($_POST['action']) && $_POST['action'] === 'send_message') {
    $userMessage = $_POST['message'];
    $botResponse = getBotResponse($userMessage);
    
    // Ajouter à l'historique
    $_SESSION['chat_history'][] = ['user' => $userMessage, 'bot' => $botResponse, 'time' => date('H:i')];
    
    echo json_encode(['response' => $botResponse]);
    exit;
}

// Récupérer l'historique
if (isset($_POST['action']) && $_POST['action'] === 'get_history') {
    echo json_encode($_SESSION['chat_history']);
    exit;
}

// Vider l'historique
if (isset($_POST['action']) && $_POST['action'] === 'clear_history') {
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
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 1px solid #ccc;
            overflow: hidden;
        }

        .header {
            background: #333;
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
            background: #333;
            color: white;
        }

        .message.bot .message-content {
            background: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
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
            border-color: #333;
        }

        #sendBtn {
            padding: 12px 25px;
            background: #333;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        #sendBtn:hover {
            background: #555;
        }

        #clearBtn {
            padding: 8px 15px;
            background: #666;
            color: white;
            border: none;
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
            background: #333;
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
        /* Style pour la barre de navigation */
#site-nav {
  background: #333;
  padding: 10px 0;
  margin-bottom: 20px;
}

#site-nav ul {
  list-style: none;
  display: flex;
  justify-content: center; /* centre les éléments */
  gap: 12px; /* espace entre les liens */
  padding: 0;
  margin: 0;
}

#site-nav li {
  display: inline;
}

#site-nav a {
  color: white;
  text-decoration: none;
  font-weight: 600; /* un peu moins gras */
  font-size: 14px; /* texte plus petit */
  padding: 6px 10px; /* réduit l’espace */
  border-radius: 4px;
  transition: background 0.3s;
  display: flex;
  align-items: center; /* centre verticalement le texte */
  height: 30px; /* fixe la hauteur */
}
</style>
</head>
<body>
    <div class="container">
<nav id="site-nav">
    <ul>
      <li><a href="index.html">Page HTML (chap. 2)</a></li>
      <li><a href="cv-css.html" aria-current="page">Page stylée (chap. 3)</a></li>
      <li><a href="cv-tailwind.html">Page responsive (bonus)</a></li>
      <li><a href="formulaire.php">Formulaire</a></li>
      <li><a href="formulaire_nosql.php">Formulaire Nosql (bonus)</a></li>
      <li><a href="cv-js.html">Page interactive</a></li>
      <li><a href="chatbot.php">Chatbot</a></li>

    </ul>
  </nav>

        <div class="header">
            <button id="clearBtn" onclick="clearChat()">Effacer</button>
            <h1>Mini ChatBot</h1>
            <p>Votre assistant virtuel personnel</p>
        </div>
        
        <div class="chat-container" id="chatContainer">
            <div class="message bot">
                <div class="message-content">
                    Bonjour ! Je suis votre ChatBot. Je peux repondre a vos salutations et a plusieurs questions simples. Tapez votre message ci-dessous !
                </div>
            </div>
        </div>
        
        <div class="typing-indicator" id="typingIndicator">
            ChatBot est en train d'ecrire...
        </div>
        
        <div class="suggestions">
            <h4>Suggestions :</h4>
            <span class="suggestion-btn" onclick="sendSuggestion('Bonjour')">Bonjour</span>
            <span class="suggestion-btn" onclick="sendSuggestion('Comment tu t\'appelles ?')">Ton nom</span>
            <span class="suggestion-btn" onclick="sendSuggestion('Quelle heure est-il ?')">L'heure</span>
            <span class="suggestion-btn" onclick="sendSuggestion('Quel est ton âge ?')">Ton âge</span>
            <span class="suggestion-btn" onclick="sendSuggestion('Quelle est ta couleur préférée ?')">Couleur preferee</span>
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
