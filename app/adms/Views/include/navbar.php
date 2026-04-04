<nav class="navbar">
    <div class="navbar-left">
        <div class="bars" id="bars-btn">
            <i class="fa-solid fa-bars-staggered"></i>
        </div>
        <img src="<?php echo URLADM; ?>app/adms/assets/image/logo/logo.png" alt="Docnet" class="logo">
    </div>

    <div class="navbar-right">
        
        <div class="user-profile">
            <div class="user-info">
                <span class="user-name"><?= $_SESSION['user_nickname'] ?? 'Usuário' ?></span>
                <span class="user-role">Online</span>
            </div>
            
            <div class="avatar" style="position: relative;">
                <?php
                $avatarPath = (!empty($_SESSION['user_image']) && file_exists("app/adms/assets/image/users/" . $_SESSION['user_id'] . "/" . $_SESSION['user_image'])) 
                    ? URLADM . "app/adms/assets/image/users/" . $_SESSION['user_id'] . "/" . $_SESSION['user_image']
                    : URLADM . "app/adms/assets/image/users/icon_user.png";
                ?>
                <img src="<?= $avatarPath ?>" alt="Profile" class="avatar-img">
                
                <div class="dropdown-menu-modern">
                    <div class="dropdown-header">Minha Conta</div>
                    <a href="<?= URLADM ?>view-profile/index" class="item">
                        <i class="p-2 fa-solid fa-circle-user"></i> Perfil
                    </a>
                    <a href="<?= URLADM ?>edit-profile/index" class="item">
                        <i class="p-2 fa-solid fa-sliders"></i> Configurações
                    </a>
                    <hr>
                    <a href="<?= URLADM ?>logout/index" class="item logout">
                        <i class="p-2 fa-solid fa-power-off"></i> Sair
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>