<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
$atleta = $this->data['atleta'] ?? null;
?>
<style>
    .foto-perfil-img {
        width: 150px;    /* Largura fixa na exibição */
        height: 150px;   /* Altura fixa na exibição */
        object-fit: cover; /* Garante que a foto não fique esticada */
        border-radius: 50%; /* Opcional: Deixa a foto redonda */
        border: 3px solid #0044cc; /* Azul Royal que você gosta */
    }
</style>
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Perfil do Atleta: <?= $atleta['nome'] ?></span>
            <div class="top-list-right">
                <a href="<?= URLADM ?>list-atletas/index" class="btn-info">Listar</a>
                <a href="<?= URLADM ?>edit-atleta/index/<?= $atleta['id'] ?>" class="btn-warning">Editar</a>
            </div>
        </div>

        <div class="content-adm">
            <div class="perfil-container">
                <div class="perfil-foto">
                    <?php
                    // Define a foto padrão caso o atleta não tenha uma
                    $foto = "atleta_padrao.png";
                    if (!empty($atleta['imagem']) && file_exists("app/adms/assets/image/atletas/" . $atleta['imagem'])) {
                        $foto = $atleta['imagem'];
                    }
                    ?>
                    <img src="<?= URLADM ?>app/adms/assets/image/atletas/<?= $foto ?>" id="preview-foto" class="foto-perfil-img" alt="Foto de <?= $atleta['nome'] ?>">
                    
                    <form method="POST" action="" enctype="multipart/form-data" class="form-adm form-upload">
                        <input type="file" name="imagem" id="imagem" class="input-adm" required accept="image/*" onchange="previewImage()">
                        <button type="submit" name="AdmsUploadFoto" class="btn-success" value="Enviar">Enviar Foto</button>
                    </form>
                </div>

                <div class="perfil-dados">
                    <h3 class="dados-titulo">Informações Técnicas</h3>
                    <ul class="dados-lista">
                        <li><strong>Apelido:</strong> <?= $atleta['apelido'] ?></li>
                        <li><strong>Estilo de Jogo:</strong> <?= $atleta['estilo_jogo'] ?></li>
                        <li><strong>Mão Dominante:</strong> <?= $atleta['mao_dominante'] ?></li>
                        <li><strong>Pontuação Ranking:</strong> <span class="badge-ranking"><?= $atleta['pontuacao_ranking'] ?> pts</span></li>
                        <li><strong>Data de Nascimento:</strong> <?= (!empty($atleta['data_nascimento'])) ? date('d/m/Y', strtotime($atleta['data_nascimento'])) : 'Não informado' ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Função JavaScript para pré-visualizar a imagem selecionada
    function previewImage() {
        var imagem = document.getElementById('imagem').files[0];
        var preview = document.getElementById('preview-foto');
        
        var reader = new FileReader();
        
        reader.onloadend = function () {
            preview.src = reader.result;
        }
        
        if (imagem) {
            reader.readAsDataURL(imagem);
        } else {
            // Se não selecionar nenhuma imagem, volta para a foto original ou padrão
            <?php
            $foto = "atleta_padrao.png";
            if (!empty($atleta['imagem']) && file_exists("app/adms/assets/image/atletas/" . $atleta['imagem'])) {
                $foto = $atleta['imagem'];
            }
            ?>
            preview.src = "<?= URLADM ?>app/adms/assets/image/atletas/<?= $foto ?>";
        }
    }
</script>