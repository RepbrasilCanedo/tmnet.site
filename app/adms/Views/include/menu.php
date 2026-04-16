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
                echo "<img src='" . URLADM . "app/adms/assets/image/logo/clientes/". $_SESSION['emp_user']."/logo_etpam.png' alt='Logo Cliente' style='max-width: 40%; margin: 20px auto; display: block;'>";
            } else {
                echo "<div class='default-client-icon' style='text-align:center; padding: 20px;'><i class='fa-solid fa-building fa-2x'></i></div>";
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

                // ... (Seu código de loop continua igual aqui) ...
                
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