<?php if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); } ?>
</div>
<script src="<?php echo URLADM; ?>app/adms/assets/js/custom_adms.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() { $('.input-emp').select2({}); });

    var totalGlobalAnterior = 0;
    const somNotificacaoGlobal = new Audio('<?php echo URLADM; ?>app/adms/assets/audios/notify.mp3');

    function verificarNotificacoesGlobais() {
        fetch('<?php echo URLADM; ?>chat/verificarNovas/index')
            .then(res => res.json())
            .then(dados => {
                let totalGeral = 0;
                const inputDestinatario = document.getElementById('destinatario_id');
                const idChatAberto = inputDestinatario ? inputDestinatario.value : null;

                // 1. Processar Mensagens
                if (Array.isArray(dados.notificacoes)) {
                    dados.notificacoes.forEach(notif => {
                        const totalNotif = parseInt(notif.total);
                        totalGeral += totalNotif;
                        const bIndividual = document.getElementById('badge-u-' + notif.remetente_id);
                        if(bIndividual && notif.remetente_id != idChatAberto) {
                            bIndividual.innerText = totalNotif;
                            bIndividual.style.display = 'block';
                        }
                    });
                }

                // 2. Processar Status Online
                // Localiza todas as bolinhas de status
                const todasBolinhas = document.querySelectorAll('.status-indicator');

                // Primeiro, marcamos TODOS como offline (cinza)
                todasBolinhas.forEach(el => {
                    el.classList.remove('status-online');
                    el.classList.add('status-offline');
                });

                // Agora, apenas os IDs que vieram no JSON ficam verdes
                if (Array.isArray(dados.online_ids)) {
                    dados.online_ids.forEach(id => {
                        const el = document.getElementById('status-u-' + id);
                        if (el) {
                            el.classList.remove('status-offline');
                            el.classList.add('status-online');
                        }
                    });
                }

                // 3. Badges Menu
                const bMenu = document.getElementById('badge-chat-global');
                const bTitulo = document.getElementById('badge-chat-titulo');
                [bMenu, bTitulo].forEach(el => {
                    if(el) { el.innerText = totalGeral; el.style.display = totalGeral > 0 ? 'inline-block' : 'none'; }
                });

                if (totalGeral > totalGlobalAnterior) { somNotificacaoGlobal.play().catch(e => {}); }
                totalGlobalAnterior = totalGeral;
            });
    }
    setInterval(verificarNotificacoesGlobais, 15000);
    verificarNotificacoesGlobais();
</script>
</body></html>