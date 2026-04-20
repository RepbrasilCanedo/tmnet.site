<?php
    // CORREÇÃO DO ERRO: Verifica se $this->data existe e é um array antes de acessar
    $sidebar_active = "";
    if(isset($this->data) && is_array($this->data) && isset($this->data['sidebarActive'])){
        $sidebar_active = $this->data['sidebarActive'];
    }
?>
<div class="sidebar">
    <div class="sidebar-header">
        <div class="client-logo">
            <?php 
            if(!empty($_SESSION['emp_user'])){
                $empresaId = (int)$_SESSION['emp_user'];
                
                // DOCAN FIX: Busca dinâmica do nome da logo diretamente do Banco de Dados
                $readLogo = new \App\adms\Models\helper\AdmsRead();
                $readLogo->fullRead("SELECT logo FROM adms_emp_principal WHERE id = :id LIMIT 1", "id={$empresaId}");
                $logoResult = $readLogo->getResult();
                
                // Verifica se a busca teve sucesso e se a coluna logo não está vazia
                if ($logoResult && !empty($logoResult[0]['logo'])) {
                    $nomeDaLogo = $logoResult[0]['logo'];
                    $caminhoLogo = URLADM . "app/adms/assets/image/logo/clientes/{$empresaId}/{$nomeDaLogo}";
                    
                    // Exibe a imagem. O onerror protege o layout caso a imagem tenha sido apagada da pasta física.
                    echo "<img src='{$caminhoLogo}' alt='Logo Cliente' style='max-width: 40%; margin: 20px auto; display: block;' onerror=\"this.style.display='none';\">";
                } else {
                    // Fallback: O clube existe, mas ainda não fez upload de uma logo no painel
                    echo "<div class='default-client-icon' style='text-align:center; padding: 20px; color: #0044cc;'>
                            <i class='fa-solid fa-shield-halved fa-2x'></i><br>
                            <small style='font-size: 10px; color: #666; font-weight: bold;'>Clube</small>
                          </div>";
                }
            } else {
                // Fallback genérico para Super Admin (que não está atrelado a um clube específico)
                echo "<div class='default-client-icon' style='text-align:center; padding: 20px; color: #0044cc;'>
                        <i class='fa-solid fa-building fa-2x'></i>
                      </div>";
            }      
            ?>     
        </div>
    </div>

    <nav class="sidebar-nav-container">
        <?php
        if (isset($this->data['menu']) && $this->data['menu']) {
            $count_drop_start = 0;
            $count_drop_end = 0;
            
            foreach ($this->data['menu'] as $item_menu) {
                extract($item_menu);
                $active_class = ($sidebar_active == $menu_controller) ? "active" : "";
                $badge_chat = ($menu_controller == 'chat') ? "<span class='badge-dot' id='badge-chat-global' style='display:none;'></span>" : "";

                // Apenas certifique-se de que os icones tenham margem correta no CSS novo
                if ($dropdown == 1) {
                    if ($count_drop_start != $id_itm_men) {
                        if ($count_drop_end == 1 && $count_drop_start != 0) echo "</div>";
                        echo "<button class='nav-dropdown-btn' data-target='drop-$id_itm_men' style='width:100%; text-align:left; background:none; border:none; padding:12px 10px; cursor:pointer; display:flex; align-items:center; color:#333;'>";
                        echo "<i class='nav-icon $icon_itm_men' style='margin-right:10px; width:25px; text-align:center;'></i>";
                        echo "<span style='flex-grow:1;'>$name_itm_men</span>";
                        echo "<i class='fa-solid fa-chevron-right arrow-icon'></i>";
                        echo "</button>";
                        echo "<div class='nav-dropdown-content' id='drop-$id_itm_men' style='display:none; padding-left:20px;'>";
                    }
                    echo "<a href='".URLADM."$menu_controller/$menu_metodo' class='sidebar-nav sub-item $active_class'><i class='$icon'></i> $name_page $badge_chat</a>";
                    $count_drop_start = $id_itm_men;
                    $count_drop_end = 1;
                } else {
                    if ($count_drop_end == 1) { echo "</div>"; $count_drop_end = 0; }
                    echo "<a href='".URLADM."$menu_controller/$menu_metodo' class='sidebar-nav $active_class'><i class='$icon'></i> $name_page $badge_chat</a>";
                }
            }
            if ($count_drop_end == 1) echo "</div>";
        }
        ?>
    </nav>
</div>