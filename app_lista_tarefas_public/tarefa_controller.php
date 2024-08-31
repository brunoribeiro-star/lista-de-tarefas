<?php

require_once 'tarefa_service.php'; // Ajuste o caminho conforme necessário

$tarefaService = new TarefaService(); // Instância do serviço de tarefas

// Receber a ação a ser executada
$acao = isset($_GET['acao']) ? $_GET['acao'] : null;

function redirecionar($url) {
    header('Location: ' . $url);
    exit;
}

switch($acao) {
    case 'recuperar':
        $criterio = isset($_GET['criterio']) ? $_GET['criterio'] : 'data_cadastro';
        $ordem = isset($_GET['ordem']) ? $_GET['ordem'] : 'ASC';
        $filtroStatus = isset($_GET['status']) ? $_GET['status'] : null;
        $filtroCategoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;

        $tarefas = $tarefaService->filtrarTarefas($filtroStatus, $filtroCategoria);
        $tarefas = $tarefaService->ordenarTarefas($criterio, $ordem);

        // Renderizar ou exibir tarefas conforme a necessidade
        break;

    case 'recuperarTarefasPendentes':
        $tarefas = $tarefaService->recuperarTarefasPendentes();
        // Renderizar ou exibir tarefas pendentes conforme a necessidade
        break;

    case 'atualizar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
            $tarefa = isset($_POST['tarefa']) ? trim($_POST['tarefa']) : null;

            if ($id && $tarefa) {
                $tarefaService->atualizarTarefa($id, $tarefa);
            }
        }
        redirecionar('index.php');
        break;

    case 'remover':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if ($id) {
            $tarefaService->removerTarefa($id);
        }
        redirecionar('index.php');
        break;

    case 'marcarRealizada':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if ($id) {
            $tarefaService->marcarTarefaComoRealizada($id);
        }
        redirecionar('index.php');
        break;

    case 'adicionar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tarefa = isset($_POST['tarefa']) ? trim($_POST['tarefa']) : null;
            $categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : null;
            $prazo = isset($_POST['prazo']) ? trim($_POST['prazo']) : null;

            if ($tarefa) {
                $tarefaService->adicionarTarefa($tarefa, $categoria, $prazo);
            }
        }
        redirecionar('nova_tarefa.php');
        break;

    default:
        // Ação não reconhecida
        header('HTTP/1.0 400 Bad Request');
        echo 'Ação não reconhecida';
        exit;
}

?>
