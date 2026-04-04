/* ============================================================
   1. CONFIGURAÇÕES GERAIS E UTILITÁRIOS
   ============================================================ */

// Permitir retorno no navegador no formulario apos o erro sem reenvio de dados
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

// Lógica do botão de Ações nas tabelas (Listar)
function actionDropdown(id) {
    closeDropdownAction();
    var dropdown = document.getElementById("actionDropdown" + id);
    if(dropdown){
        dropdown.classList.toggle("show-dropdown-action");
    }
}

window.onclick = function (event) {
    if (!event.target.matches(".dropdown-btn-action")) {
        closeDropdownAction();
    }
}

function closeDropdownAction() {
    var dropdowns = document.getElementsByClassName("dropdown-action-item");
    for (var i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains("show-dropdown-action")) {
            openDropdown.classList.remove("show-dropdown-action");
        }
    }
}

// Calcular a forca da senha
function passwordStrength() {
    var password = document.getElementById("password").value;
    var strength = 0;

    if ((password.length >= 6) && (password.length <= 7)) strength += 10;
    else if (password.length > 7) strength += 25;

    if ((password.length >= 6) && (password.match(/[a-z]+/))) strength += 10;
    if ((password.length >= 7) && (password.match(/[A-Z]+/))) strength += 20;
    if ((password.length >= 8) && (password.match(/[@#$%;*]+/))) strength += 25;
    if (password.match(/([1-9]+)\1{1,}/)) strength -= 25;

    viewStrength(strength);
}

function viewStrength(strength) {
    var msgView = document.getElementById("msgViewStrength");
    if(!msgView) return;

    if (strength < 30) msgView.innerHTML = "<p class='alert-danger'>Senha Fraca</p>";
    else if (strength >= 30 && strength < 50) msgView.innerHTML = "<p class='alert-warning'>Senha Média</p>";
    else if (strength >= 50 && strength < 69) msgView.innerHTML = "<p class='alert-primary'>Senha Boa</p>";
    else if (strength >= 70) msgView.innerHTML = "<p class='alert-success'>Senha Forte</p>";
    else msgView.innerHTML = "";
}

/* ============================================================
   2. VALIDAÇÕES DE FORMULÁRIOS (CADASTROS E EDIÇÕES)
   ============================================================ */

// Função auxiliar para exibir erro
function showError(msg) {
    var msgDiv = document.getElementById("msg");
    if(msgDiv) msgDiv.innerHTML = "<p class='alert-danger'>" + msg + "</p>";
}

// Formulário Adicionar Usuário
const formAddUser = document.getElementById("form-add-user");
if (formAddUser) {
    formAddUser.addEventListener("submit", async (e) => {
        var name = document.querySelector("#name").value;
        if (name === "") { e.preventDefault(); showError("Erro: Necessário preencher o campo nome!"); return; }
        
        var email = document.querySelector("#email").value;
        if (email === "") { e.preventDefault(); showError("Erro: Necessário preencher o campo e-mail!"); return; }

        var user = document.querySelector("#user").value;
        if (user === "") { e.preventDefault(); showError("Erro: Necessário preencher o campo usuário!"); return; }

        var password = document.querySelector("#password").value;
        if (password === "") { e.preventDefault(); showError("Erro: Necessário preencher o campo senha!"); return; }
        
        if (password.length < 6) { e.preventDefault(); showError("Erro: A senha deve ter no mínimo 6 caracteres!"); return; }
        if (password.match(/([1-9]+)\1{1,}/)) { e.preventDefault(); showError("Erro: A senha não deve ter número repetido!"); return; }
        if (!password.match(/[A-Za-z]/)) { e.preventDefault(); showError("Erro: A senha deve ter pelo menos uma letra!"); return; }
    });
}

// Formulário Editar Usuário
const formEditUser = document.getElementById("form-edit-user");
if (formEditUser) {
    formEditUser.addEventListener("submit", async (e) => {
        var name = document.querySelector("#name").value;
        if (name === "") { e.preventDefault(); showError("Erro: Necessário preencher o campo nome!"); return; }
        var email = document.querySelector("#email").value;
        if (email === "") { e.preventDefault(); showError("Erro: Necessário preencher o campo e-mail!"); return; }
        var user = document.querySelector("#user").value;
        if (user === "") { e.preventDefault(); showError("Erro: Necessário preencher o campo usuário!"); return; }
    });
}

// Formulário Editar Senha Usuário
const formEditUserPass = document.getElementById("form-edit-user-pass");
if (formEditUserPass) {
    formEditUserPass.addEventListener("submit", async (e) => {
        var password = document.querySelector("#password").value;
        if (password === "") { e.preventDefault(); showError("Erro: Necessário preencher o campo senha!"); return; }
        if (password.length < 6) { e.preventDefault(); showError("Erro: A senha deve ter no mínimo 6 caracteres!"); return; }
        if (password.match(/([1-9]+)\1{1,}/)) { e.preventDefault(); showError("Erro: A senha não deve ter número repetido!"); return; }
        if (!password.match(/[A-Za-z]/)) { e.preventDefault(); showError("Erro: A senha deve ter pelo menos uma letra!"); return; }
    });
}

// Formulário Editar Perfil
const formEditProfile = document.getElementById("form-edit-profile");
if (formEditProfile) {
    formEditProfile.addEventListener("submit", async (e) => {
        var name = document.querySelector("#name").value;
        if (name === "") { e.preventDefault(); showError("Erro: Necessário preencher o campo nome!"); return; }
        var email = document.querySelector("#email").value;
        if (email === "") { e.preventDefault(); showError("Erro: Necessário preencher o campo e-mail!"); return; }
    });
}

// Formulário Editar Senha Perfil
const formEditProfPass = document.getElementById("form-edit-prof-pass");
if (formEditProfPass) {
    formEditProfPass.addEventListener("submit", async (e) => {
        var password = document.querySelector("#password").value;
        if (password === "") { e.preventDefault(); showError("Erro: Necessário preencher o campo senha!"); return; }
        if (password.length < 6) { e.preventDefault(); showError("Erro: A senha deve ter no mínimo 6 caracteres!"); return; }
        if (password.match(/([1-9]+)\1{1,}/)) { e.preventDefault(); showError("Erro: A senha não deve ter número repetido!"); return; }
        if (!password.match(/[A-Za-z]/)) { e.preventDefault(); showError("Erro: A senha deve ter pelo menos uma letra!"); return; }
    });
}

// --- Validação de Imagens (Usuários, Perfis, Clientes) ---

function validateImageForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.addEventListener("submit", async (e) => {
            // Tenta encontrar inputs com nomes comuns de imagem
            var imgInput = form.querySelector('input[type="file"]');
            if (imgInput && imgInput.value === "") {
                e.preventDefault();
                showError("Erro: Necessário selecionar uma imagem!");
            }
        });
    }
}

// Aplica validação genérica para forms de imagem
validateImageForm("form-edit-user-img");
validateImageForm("form-edit-prof-img");
validateImageForm("form-edit-prof-img-cham");
validateImageForm("form-edit-user-img-clie-fin");

// Funções de Preview e Validação de Extensão
function validateAndPreview(input, previewId, type) {
    var filePath = input.value;
    var allowedExtensions = type === 'pdf' ? /(\.pdf)$/i : /(\.jpg|\.jpeg|\.png)$/i;
    var errorMsg = type === 'pdf' ? "Erro: Necessário arquivo PDF!" : "Erro: Necessário imagem JPG ou PNG!";

    if (!allowedExtensions.exec(filePath)) {
        input.value = '';
        showError(errorMsg);
        return;
    } else {
        if (document.getElementById("msg")) document.getElementById("msg").innerHTML = "";
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var previewDiv = document.getElementById(previewId);
                if(previewDiv) {
                    if(type === 'pdf') {
                        previewDiv.innerHTML = "<iframe src='" + e.target.result + "' style='width: 100%; height: 500px; border: 1px solid #ccc;'></iframe>";
                    } else {
                        // Ajusta tamanho conforme o tipo
                        var width = previewId.includes('cham') ? '500px' : '100px'; 
                        previewDiv.innerHTML = "<img src='" + e.target.result + "' alt='Imagem' style='width: "+width+";'>";
                    }
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
}

// Wrappers para chamadas do HTML (mantendo compatibilidade)
function inputFileValImg() { validateAndPreview(document.querySelector("#new_image"), 'preview-img-avatar', 'img'); }
function inputFileValImgClieFin() { validateAndPreview(document.querySelector("#new_image_clie_fin"), 'preview-img-avatar-clie-fin', 'img'); }
function inputFileValLogo() { validateAndPreview(document.querySelector("#new_image"), 'preview-img', 'img'); }
function inputFileValImgCham() { validateAndPreview(document.querySelector("#new_image_cham"), 'preview-img-cham', 'img'); }
function inputFileValImgOrcam() { validateAndPreview(document.querySelector("#new_image_orcam"), 'preview-img-orcam', 'pdf'); }

// Carrossel Previews (Slide 1 a 4)
function inputFileValImgSlideUm() { validateAndPreview(document.querySelector("#image_1"), 'preview-img_1', 'img'); }
function inputFileValImgSlideDois() { validateAndPreview(document.querySelector("#image_2"), 'preview-img_2', 'img'); }
function inputFileValImgSlideTres() { validateAndPreview(document.querySelector("#image_3"), 'preview-img_3', 'img'); }
function inputFileValImgSlideQuatro() { validateAndPreview(document.querySelector("#image_4"), 'preview-img_4', 'img'); }

// Outros formulários de cadastro (Cores, Empresas, Setores, etc)
const simpleForms = [
    "form-add-sit-user", "form-add-color", "form-add-conf-emails", "form-edit-conf-emails",
    "form-edit-conf-emails-pass", "form-add-access-levels", "form-add-sit-pages", "form-edit-sit-pages",
    "form-add-groups-pages", "form-edit-groups-pages", "form-add-types-pages", "form-edit-types-pages",
    "form-add-pages", "form-edit-pages", "form-add-empresas", "form-edit-empresas",
    "form-add-setor", "form-edit-setor", "form-edit-level-form", "form-add-item-menu", "form-edit-page-menu"
];

// Adiciona listener genérico para validar campos vazios básicos se o form existir
simpleForms.forEach(id => {
    const form = document.getElementById(id);
    if(form) {
        form.addEventListener("submit", function(e) {
            // Validação simples: verifica input obrigatórios genéricos
            let inputs = form.querySelectorAll("input[required], select[required]");
            for(let input of inputs) {
                if(input.value.trim() === "") {
                    e.preventDefault();
                    showError("Preencha todos os campos obrigatórios.");
                    return;
                }
            }
        });
    }
});
/* ============================================================
   3. SISTEMA DE MENUS E LAYOUT (CORREÇÃO DE CLIQUES)
   ============================================================ */
document.addEventListener("DOMContentLoaded", function () {
    console.log("--- Sistema de Menu DocNet Iniciado (Versão Link-Safe) ---");

    /* --- LÓGICA DO MENU DE PERFIL (NAVBAR) --- */
    const avatar = document.querySelector('.avatar');
    // Busca pela classe nova OU antiga
    const profileMenu = document.querySelector('.dropdown-menu-modern') || document.querySelector('.dropdown-menu.setting');

    if (avatar && profileMenu) {
        avatar.addEventListener('click', function (e) {
            
            // CORREÇÃO DO PROBLEMA:
            // Se o elemento clicado for um link (a) ou estiver dentro de um link,
            // NÃO previne o padrão e NÃO roda o resto do script. Deixa o navegador ir para a página.
            if (e.target.closest('a')) {
                return; 
            }

            // Se não for link (ex: clicou na foto), aí sim previne comportamento e abre o menu
            e.preventDefault();
            e.stopPropagation();
            
            // Alterna classe e força display
            profileMenu.classList.toggle('active');
            
            // Força visualização via estilo inline para garantir
            if (profileMenu.classList.contains('active')) {
                profileMenu.style.display = 'block';
            } else {
                profileMenu.style.display = 'none';
            }
        });

        // Fechar ao clicar fora
        document.addEventListener('click', function (e) {
            if (!avatar.contains(e.target)) {
                profileMenu.classList.remove('active');
                profileMenu.style.display = 'none';
            }
        });
    }

    /* --- LÓGICA DOS SUBMENUS (SIDEBAR) --- */
    document.body.addEventListener('click', function(e) {
        // Verifica se clicou em um botão de dropdown da sidebar
        const btn = e.target.closest('.nav-dropdown-btn');

        if (btn) {
            e.preventDefault(); 
            
            // 1. Gira a setinha
            btn.classList.toggle('active');

            // 2. Acha o conteúdo do menu
            const targetId = btn.getAttribute('data-target');
            if(targetId) {
                const content = document.getElementById(targetId);
                if (content) {
                    if (content.style.display === "block") {
                        content.style.display = "none";
                        content.classList.remove('active');
                    } else {
                        content.style.display = "block";
                        content.classList.add('active');
                    }
                }
            }
        }
    });

    /* --- LÓGICA MOBILE (BARRAS) --- */
    const barsBtn = document.querySelector('.bars') || document.getElementById('bars-btn');
    const sidebar = document.querySelector('.sidebar');
    const wrapper = document.querySelector('.wrapper') || document.querySelector('.content');

    if (barsBtn && sidebar) {
        barsBtn.addEventListener('click', function (e) {
            e.preventDefault();
            sidebar.classList.toggle('active');
            
            // Ajusta o conteúdo principal para esticar
            if(wrapper) {
                wrapper.classList.toggle('sidebar-active');
            }
        });
    }
});