
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("collapsed");
            document.getElementById("content").classList.toggle("collapsed");
        }

        function sair() {
            alert("Logout realizado!");
        }

        function sendMessage() {
            const input = document.getElementById("messageInput");
            const container = document.getElementById("messagesContainer");
            const text = input.value.trim();

            if (text === "") return;

            const messageElement = document.createElement("div");
            messageElement.classList.add("message", "user");
            messageElement.innerText = text;

            container.appendChild(messageElement);
            input.value = "";
            container.scrollTop = container.scrollHeight;
        }