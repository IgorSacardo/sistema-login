<?php
    session_start();
    require_once '../conexao/conexao.php';

    if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] != 'admin') {
        header("Location: ../index.html");
        exit;
    }

    $nome_admin = htmlspecialchars($_SESSION['usuario_nome']);

    try {
        $sql = "SELECT usu_id, usu_nome, usu_email, usu_nivel_acesso FROM usuarios WHERE usu_nivel_acesso <> 'admin' ORDER BY usu_nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erro ao buscar usuários: ".$e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/tab_usuarios.css">
</head>
<body class="site-body">
    <header class="nav-bar">
        <div class="info-usu">
            <div class="det-usu">
                <span class="nome-usu">Bem-vindo, Administrador</span>
            </div>
            <div>
                <a href="../logout.php" class="btn-sair">Sair</a>
            </div>
        </div>
    </header>

    <main class="cont-principal">
        <div class="apresentacao" style="align-items: flex-start;">
            <div class="txt-apresentacao">
                <h1>Gerenciamento de <span class="destaque">Usuários</span></h1>
                <p>Aqui você pode visualizar, editar e excluir os usuários cadastrados no sistema.</p>
                <a href="admin_pag.php" class="btn-principal">Voltar ao Painel</a>
            </div>
        </div>

        <div class="tabela-container">
            <table class="tabela-usuarios">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Nível</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($usuarios) > 0): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['usu_id']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['usu_nome']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['usu_email']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['usu_nivel_acesso']); ?></td>
                                <td class="acoes">
                                    <a data-id="<?php echo $usuario['usu_id']; ?>" class="btn-acao btn-editar">Editar</a>
                                    <a href="excluir_usuario.php?id=<?php echo $usuario['usu_id']; ?>" class="btn-acao btn-excluir"
                                        onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Nenhum usuário encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <div id="modal-edicao" class="modal">
        <div class="modal-conteudo">
            <span class="botao-fechar">&times;</span>
            <h1>Editar <span class="destaque">Usuário</span></h1>
            
            <form id="form-edicao" class="inputs">
                <input type="hidden" id="editar-id" name="id">

                <label>Nome</label>
                <input type="text" id="editar-nome" name="nome" required>

                <label>E-mail</label>
                <input type="email" id="editar-email" name="email" required>
                
                <label>Nível de Acesso</label>
                <select id="editar-nivel" name="nivel" style="padding: 12px; border: 1px solid #444; border-radius: 8px; font-size: 14px; background-color: #222; color: #EAEAEA;">
                    <option value="usuario">Usuário Comum</option>
                    <option value="admin">Administrador</option>
                </select>

                <button type="submit">Atualizar Usuário</button>
            </form>
            <div id="mensagem-edicao" style="margin-top: 15px; font-weight: bold;"></div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modal-edicao');
        const botaoFechar = document.querySelector('.botao-fechar');
        const formEdicao = document.getElementById('form-edicao');
        const mensagemEdicao = document.getElementById('mensagem-edicao');

        let linhaSendoEditada = null; // Armazena a linha <tr> da tabela

        const abrirModal = () => modal.classList.add('mostrar');
        const fecharModal = () => {
            modal.classList.remove('mostrar');
            mensagemEdicao.textContent = ''; // Limpa mensagens
        };

        // Adiciona um "escutador" de cliques na tabela
        document.querySelector('.tabela-usuarios tbody').addEventListener('click', async (evento) => {
            // Verifica se o clique foi em um botão com a classe 'btn-editar'
            if (evento.target.classList.contains('btn-editar')) {
                const botao = evento.target;
                const idUsuario = botao.dataset.id;
                linhaSendoEditada = botao.closest('tr'); // Pega a linha <tr> mais próxima do botão

                try {
                    // Busca os dados do usuário na nossa API
                    const resposta = await fetch(`api_usuario.php?id=${idUsuario}`);
                    const resultado = await resposta.json();

                    if (resultado.status === 'success') {
                        const dadosUsuario = resultado.data;
                        // Preenche o formulário do modal com os dados retornados
                        document.getElementById('editar-id').value = dadosUsuario.usu_id;
                        document.getElementById('editar-nome').value = dadosUsuario.usu_nome;
                        document.getElementById('editar-email').value = dadosUsuario.usu_email;
                        document.getElementById('editar-nivel').value = dadosUsuario.usu_nivel_acesso;
                        abrirModal();
                    } else {
                        alert('Erro ao buscar dados do usuário: ' + resultado.message);
                    }
                } catch (erro) {
                    alert('Ocorreu um erro de rede. Tente novamente.');
                }
            }
        });

        // "Escutador" para quando o formulário de edição for enviado
        formEdicao.addEventListener('submit', async (evento) => {
            evento.preventDefault(); // Impede o recarregamento da página
            const formData = new FormData(formEdicao);
            const dados = Object.fromEntries(formData.entries());

            try {
                const resposta = await fetch('api_usuario.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(dados) // Converte os dados para JSON
                });
                const resultado = await resposta.json();

                if (resultado.status === 'success') {
                    // Se a atualização deu certo, atualiza a tabela na tela
                    if (linhaSendoEditada) {
                        linhaSendoEditada.cells[1].textContent = dados.nome;
                        linhaSendoEditada.cells[2].textContent = dados.email;
                        linhaSendoEditada.cells[3].textContent = dados.nivel;
                    }
                    fecharModal();
                } else {
                    mensagemEdicao.textContent = 'Erro: ' + resultado.message;
                    mensagemEdicao.style.color = '#E7297E';
                }
            } catch (erro) {
                mensagemEdicao.textContent = 'Ocorreu um erro de rede. Tente novamente.';
                mensagemEdicao.style.color = '#E7297E';
            }
        });

        // Ações para fechar o modal
        botaoFechar.addEventListener('click', fecharModal);
        window.addEventListener('click', (evento) => {
            if (evento.target === modal) {
                fecharModal();
            }
        });
    });
    </script>
</body>
</html>