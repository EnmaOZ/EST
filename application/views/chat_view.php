<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat en Tiempo Real</title>
    <style>
        /* Paleta de colores inspirada en el diseño */
        :root {
            --primary-color: #4B5563; /* Color gris oscuro */
            --secondary-color: #3B82F6; /* Azul para destacar */
            --background-color: #F3F4F6; /* Fondo gris claro */
            --message-bg-color: #D1D5DB; /* Gris claro para los mensajes */
            --user-message-bg: #E5E7EB; /* Gris más claro para el usuario */
            --button-color: #3B82F6; /* Azul */
            --button-text-color: #FFFFFF; /* Texto blanco */
            --text-color: #111827; /* Color oscuro para el texto */
            --header-bg-color: #111827; /* Encabezado negro */
            --header-text-color: #FFFFFF; /* Texto blanco del encabezado */
        }

        /* Estilos generales */
        body {
            background-color: var(--background-color);
            font-family: 'Comic Sans MS', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Contenedor del chat */
        .chat-container {
            background-color: var(--message-bg-color);
            width: 100%;
            max-width: 600px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 2px solid var(--primary-color);
        }

        /* Encabezado del chat */
        .chat-header {
            background-color: var(--header-bg-color);
            color: var(--header-text-color);
            text-align: center;
            padding: 10px;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Área de mensajes */
        #chat {
            height: 400px;
            overflow-y: auto;
            padding: 20px;
            background-color: var(--background-color);
            display: flex;
            flex-direction: column;
        }

        /* Mensajes */
        .message {
            background-color: var(--message-bg-color);
            border-radius: 15px;
            padding: 12px;
            margin-bottom: 15px;
            max-width: 80%;
            font-size: 16px;
            display: flex;
            align-items: center;
            clear: both;
        }

        /* Mensajes del sistema o de otros usuarios alineados a la izquierda */
        .message.system {
            background-color: var(--message-bg-color);
            text-align: left;
            align-self: flex-start;
        }

        /* Mensajes del usuario alineados a la derecha */
        .message.user {
            background-color: var(--user-message-bg);
            align-self: flex-end;
            text-align: right;
        }

        /* Contenedor del input */
        .input-container {
            display: flex;
            padding: 15px;
            background-color: var(--background-color);
            border-top: 2px solid var(--primary-color);
        }

        /* Campo de texto */
        #message {
            flex: 1;
            padding: 12px;
            font-size: 16px;
            border: 2px solid var(--primary-color);
            border-radius: 10px;
            outline: none;
            margin-right: 10px;
            background-color: #FFF5F8;
        }

        /* Botón de enviar */
        #send {
            background-color: var(--button-color);
            color: var(--button-text-color);
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }

        #send:hover {
            background-color: #2563EB;
        }

        /* Íconos de encabezado */
        .header-icons {
            display: flex;
            gap: 10px;
        }

        .icon {
            color: var(--header-text-color);
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <span>Chateando...</span>
            <div class="header-icons">
                <span class="icon">	&#128125;;</span> <!-- Icono de móvil -->
                <span class="icon">&#x2026;</span> <!-- Icono de menú -->
            </div>
        </div>
        <div id="chat"></div>
        <div class="input-container">
            <input type="text" id="message" placeholder="Escribe tu mensaje">
            <button id="send">Enviar</button>
        </div>
    </div>

    <script>
        var conn = new WebSocket('ws://localhost:8080');
        var chat = document.getElementById('chat');
        var sendButton = document.getElementById('send');
        var messageInput = document.getElementById('message');

        conn.onopen = function(e) {
            var msgElement = document.createElement('div');
            msgElement.className = 'message system';
            msgElement.textContent = 'Conexión establecida';
            chat.appendChild(msgElement);
        };

        conn.onmessage = function(e) {
            var msgElement = document.createElement('div');
            msgElement.className = 'message system';
            msgElement.textContent = e.data;
            chat.appendChild(msgElement);
            chat.scrollTop = chat.scrollHeight;
        };

        sendButton.onclick = function() {
            var msg = messageInput.value;
            if (msg.trim() !== '') {
                var userMessage = document.createElement('div');
                userMessage.className = 'message user';
                userMessage.textContent = msg;
                chat.appendChild(userMessage);
                chat.scrollTop = chat.scrollHeight;
                conn.send(msg);
                messageInput.value = '';
            }
        };

        messageInput.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                sendButton.click();
            }
        });
    </script>
</body>
</html>
