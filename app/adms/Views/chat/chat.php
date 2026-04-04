<?php
if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }
?>
<style>
    /* 1. Layout Base e Responsividade */
    .chat-container {
        display: flex;
        flex-direction: row;
        height: 75vh;
        min-height: 500px;
        overflow: hidden;
        border: 1px solid #dee2e6;
        background: #fff;
        position: relative;
    }

    .chat-sidebar {
        width: 30%;
        border-right: 1px solid #ddd;
        display: flex;
        flex-direction: column;
        background: #fff;
        z-index: 10;
        transition: transform 0.3s ease;
    }

    .chat-main {
        width: 70%;
        display: flex; 
        flex-direction: column;
        background: #e5ddd5;
        z-index: 5;
        transition: transform 0.3s ease;
    }

    /* 2. Chat Body e Footer */
    #chat-body {
        flex: 1 1 auto;
        overflow-y: auto !important;
        display: none;
        flex-direction: column !important;
        padding: 15px;
        background: #e5ddd5;
    }

    #chat-footer {
        flex-shrink: 0;
        background: #fff;
        padding: 10px 15px;
        border-top: 1px solid #dee2e6;
    }

    /* 3. Estilo das Mensagens e Elementos */
    .msg-item { 
        display: block;
        clear: both;
        max-width: 80%; 
        margin-bottom: 15px; 
        padding: 10px; 
        border-radius: 10px; 
        font-size: 0.9rem; 
        position: relative;
        word-wrap: break-word;
    }
    .msg-enviada { align-self: flex-end; background: #dcf8c6; margin-left: auto; }
    .msg-recebida { align-self: flex-start; background: #ffffff; margin-right: auto; border: 1px solid #ddd; }
    .msg-time { font-size: 0.7rem; color: #999; display: block; text-align: right; margin-top: 5px; }
    
    .user-item.active { background-color: #f0f2f5 !important; border-left: 4px solid #007bff; }
    .img-profile-chat { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid #dee2e6; }
    .img-profile-header { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; }

    .btn-delete-chat {
        width: 35px; height: 35px; padding: 0 !important;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50% !important; flex-shrink: 0; border: 1px solid #dc3545;
    }

    /* Indicador de Status Online/Offline */
    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-left: 5px;
        border: 1px solid #fff;
    }
    .status-online { background-color: #28a745; box-shadow: 0 0 5px #28a745; }
    .status-offline { background-color: #ccc; }

    /* 4. Ajustes Mobile */
    @media (max-width: 768px) {
        .chat-container { height: calc(100vh - 180px); }
        .chat-sidebar { width: 100%; position: absolute; height: 100%; }
        .chat-main { 
            width: 100%; position: absolute; height: 100%; left: 0; top: 0; 
            transform: translateX(100%); z-index: 20; 
        }
        .chat-main.mobile-active { transform: translateX(0); }
        #btn-voltar { display: block !important; }
    }
</style>

<div class="dash-wrapper">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="title-content">Bate-papo Interno DocNet</h2>
        </div>
    </div>

    <div class="card shadow-sm p-0 chat-container">
        <div class="chat-sidebar" id="chat-sidebar">
            <div class="p-3 border-bottom bg-light">
                <input type="text" id="input-busca" class="form-control form-control-sm" placeholder="Buscar técnico..." onkeyup="filtrarContatos()">
            </div>
            <div class="list-group list-group-flush" id="lista-usuarios" style="overflow-y: auto;">
                <?php foreach ($this->data['usuarios'] as $user): ?>
                    <button type="button" id="btn-user-<?= $user['id'] ?>" onclick="abrirConversa(<?= $user['id'] ?>, '<?= addslashes($user['name']) ?>')" class="list-group-item list-group-item-action d-flex align-items-center p-3 user-item">
                        <div class="me-3">
                            <?php 
                            $img_user = "app/adms/assets/image/users/" . $user['id'] . "/" . $user['image'];
                            if (!empty($user['image']) && file_exists($img_user)): ?>
                                <img src="<?= URLADM . $img_user ?>" class="img-profile-chat">
                            <?php else: ?>
                                <div style="width: 40px; height: 40px; background: #007bff; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                    <?= substr($user['name'], 0, 1) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div style="flex-grow: 1;">
                            <div class="fw-bold nome-tecnico" style="font-size: 0.85rem;">
                                <?= $user['name'] ?>
                                <span id="status-u-<?= $user['id'] ?>" class="status-indicator status-offline"></span>
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">Clique para conversar</small>
                        </div>
                        <span class="badge bg-danger rounded-pill" id="badge-u-<?= $user['id'] ?>" style="display: none;">0</span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="chat-main" id="chat-main">
            <div id="chat-header" class="p-3 bg-white border-bottom shadow-sm" style="display: none;">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm me-2 d-none" id="btn-voltar" onclick="voltarLista()">
                            <i class="fas fa-arrow-left fa-lg text-primary"></i>
                        </button>
                        <div id="header-avatar-wrapper" class="me-2"></div>
                        <strong id="header-nome"></strong>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-delete-chat" onclick="limparHistorico()" title="Limpar conversa">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>

            <div id="chat-body"></div>

            <div id="instrucao-inicial" class="text-center text-muted mt-5">
                <i class="fas fa-comments fa-3x mb-3"></i>
                <p>Selecione um usuário para iniciar a conversa.</p>
            </div>

            <div id="chat-footer" style="display: none;">
                <form id="form-chat" class="d-flex" onsubmit="event.preventDefault(); enviarMensagem();">
                    <input type="hidden" id="destinatario_id">
                    <input type="hidden" id="edit_msg_id"> 
                    <input type="text" id="input-msg" class="form-control me-2" placeholder="Digite sua mensagem..." autocomplete="off" required oninput="toggleBotaoEnvio()">
                    <button type="submit" id="btn-enviar" class="btn btn-secondary" disabled>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
var chatInterval = null;
var ultimaQuantidadeMensagens = -1;
const meuId = <?= $_SESSION['user_id'] ?>;

function filtrarContatos() {
    let filtro = document.getElementById('input-busca').value.toLowerCase();
    document.querySelectorAll('.user-item').forEach(item => {
        let nome = item.querySelector('.nome-tecnico').innerText.toLowerCase();
        item.style.setProperty('display', nome.includes(filtro) ? 'flex' : 'none', 'important');
    });
}

function toggleBotaoEnvio() {
    const input = document.getElementById('input-msg');
    const btn = document.getElementById('btn-enviar');
    const idEdicao = document.getElementById('edit_msg_id').value;
    if (input.value.trim() !== "") {
        btn.disabled = false;
        btn.className = idEdicao ? 'btn btn-success' : 'btn btn-primary';
    } else {
        btn.disabled = true;
        btn.className = 'btn btn-secondary';
    }
}

function abrirConversa(id, nome) {
    if (window.innerWidth <= 768) {
        document.getElementById('chat-main').classList.add('mobile-active');
    }

    if (chatInterval) clearInterval(chatInterval);
    ultimaQuantidadeMensagens = -1; 
    document.querySelectorAll('.user-item').forEach(el => el.classList.remove('active'));
    
    const btnAtivo = document.getElementById('btn-user-' + id);
    if(btnAtivo) btnAtivo.classList.add('active');

    document.getElementById('instrucao-inicial').style.display = 'none';
    document.getElementById('chat-header').style.display = 'block';
    document.getElementById('chat-footer').style.display = 'block';
    document.getElementById('chat-body').style.display = 'flex';

    document.getElementById('input-msg').value = '';
    document.getElementById('edit_msg_id').value = '';
    toggleBotaoEnvio();

    const imgUser = btnAtivo.querySelector('img');
    const wrapper = document.getElementById('header-avatar-wrapper');
    wrapper.innerHTML = imgUser ? `<img src="${imgUser.src}" class="img-profile-header">` : 
        `<div style="width:35px; height:35px; background:#007bff; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold">${nome.charAt(0).toUpperCase()}</div>`;

    document.getElementById('destinatario_id').value = id;
    document.getElementById('header-nome').innerText = nome;
    
    carregarMensagens(id);
    chatInterval = setInterval(() => carregarMensagens(id), 3000);

    // Limpa badge do usuário aberto imediatamente
    const b = document.getElementById('badge-u-' + id);
    if(b) { b.style.display = 'none'; b.innerText = '0'; }
}

function voltarLista() {
    document.getElementById('chat-main').classList.remove('mobile-active');
    if (chatInterval) clearInterval(chatInterval);
}

function carregarMensagens(id) {
    if(!id) return;
    fetch('<?= URLADM ?>chat/carregarMensagens/index/' + id)
        .then(res => res.json())
        .then(dados => {
            const body = document.getElementById('chat-body');
            let novoHtml = '';
            dados.forEach(msg => {
                const souEu = (msg.remetente_id == meuId);
                const classe = souEu ? 'msg-enviada' : 'msg-recebida';
                const btnAcoes = souEu ? `<span class="chat-actions ms-2"><i class="fas fa-edit text-primary me-1" onclick="prepararEdicao(${msg.id}, '${msg.mensagem.replace(/'/g, "\\'")}')"></i><i class="fas fa-trash-alt text-danger" onclick="apagarMensagem(${msg.id})"></i></span>` : '';
                novoHtml += `<div class="msg-item ${classe}"><span>${msg.mensagem}</span>${btnAcoes}<span class="msg-time">${msg.hora}</span></div>`;
            });

            if (body.innerHTML !== novoHtml) {
                body.innerHTML = novoHtml;
                body.scrollTop = body.scrollHeight;
            }
        });
}

function enviarMensagem() {
    const input = document.getElementById('input-msg');
    const destId = document.getElementById('destinatario_id').value;
    const idEdicao = document.getElementById('edit_msg_id').value;
    if (!input.value.trim()) return;

    const params = new URLSearchParams();
    if(idEdicao) params.append('id', idEdicao);
    params.append('destinatario_id', destId);
    params.append('mensagem', input.value);

    fetch('<?= URLADM ?>chat/enviar/index', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: params.toString()
    })
    .then(res => res.json())
    .then(() => {
        input.value = '';
        document.getElementById('edit_msg_id').value = '';
        toggleBotaoEnvio();
        carregarMensagens(destId);
    });
}

function prepararEdicao(id, texto) {
    document.getElementById('edit_msg_id').value = id;
    document.getElementById('input-msg').value = texto;
    document.getElementById('input-msg').focus();
    toggleBotaoEnvio();
}

function apagarMensagem(id) {
    if (confirm("Apagar mensagem?")) {
        fetch('<?= URLADM ?>chat/apagarMensagem/index/' + id).then(() => carregarMensagens(document.getElementById('destinatario_id').value));
    }
}

function limparHistorico() {
    const destId = document.getElementById('destinatario_id').value;
    if (confirm("Apagar histórico desta conversa?")) {
        fetch('<?= URLADM ?>chat/limparHistorico/' + destId).then(() => carregarMensagens(destId));
    }
}
</script>