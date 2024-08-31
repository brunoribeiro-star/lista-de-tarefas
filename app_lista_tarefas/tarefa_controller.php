<?php

require "../app_lista_tarefas/tarefa.model.php";
require "../app_lista_tarefas/tarefa.service.php";
require "../app_lista_tarefas/conexao.php";

// Verifica se a ação foi definida na URL, caso contrário, define como null
$acao = isset($_GET['acao']) ? $_GET['acao'] : null;

// Cria uma nova conexão com o banco de dados
$conexao = new Conexao();

// Switch para tratar diferentes ações
switch ($acao) {
    case 'inserir':
        if (isset($_POST['tarefa'])) {
            $tarefa = new Tarefa();
            $tarefa->__set('tarefa', $_POST['tarefa'])
                   ->__set('prazo', $_POST['prazo'])  // Adicionando outros campos se necessário
                   ->__set('categoria', $_POST['categoria'])
                   ->__set('prioridade', $_POST['prioridade']);

            $tarefaService = new TarefaService($conexao, $tarefa);
            $tarefaService->inserir();

            header('Location: nova_tarefa.php?inclusao=1');
        }
        break;

    case 'recuperar':
        $tarefa = new Tarefa();
        $tarefaService = new TarefaService($conexao, $tarefa);
        $tarefas = $tarefaService->recuperar();
        break;

    case 'atualizar':
        if (isset($_POST['id']) && isset($_POST['tarefa'])) {
            $tarefa = new Tarefa();
            $tarefa->__set('id', $_POST['id'])
                   ->__set('tarefa', $_POST['tarefa'])
                   ->__set('prazo', $_POST['prazo'])
                   ->__set('categoria', $_POST['categoria'])
                   ->__set('prioridade', $_POST['prioridade']);

            $tarefaService = new TarefaService($conexao, $tarefa);
            if ($tarefaService->atualizar()) {
                $redirectPage = isset($_GET['pag']) && $_GET['pag'] == 'index' ? 'index.php' : 'todas_tarefas.php';
                header('Location: ' . $redirectPage);
            }
        }
        break;

    case 'remover':
        if (isset($_GET['id'])) {
            $tarefa = new Tarefa();
            $tarefa->__set('id', $_GET['id']);

            $tarefaService = new TarefaService($conexao, $tarefa);
            $tarefaService->remover();

            $redirectPage = isset($_GET['pag']) && $_GET['pag'] == 'index' ? 'index.php' : 'todas_tarefas.php';
            header('Location: ' . $redirectPage);
        }
        break;

    case 'marcarRealizada':
        if (isset($_GET['id'])) {
            $tarefa = new Tarefa();
            $tarefa->__set('id', $_GET['id'])->__set('id_status', 2); // Supondo que 2 é o ID para 'Realizada'

            $tarefaService = new TarefaService($conexao, $tarefa);
            $tarefaService->marcarRealizada();

            $redirectPage = isset($_GET['pag']) && $_GET['pag'] == 'index' ? 'index.php' : 'todas_tarefas.php';
            header('Location: ' . $redirectPage);
        }
        break;

    case 'recuperarTarefasPendentes':
        $tarefa = new Tarefa();
        $tarefa->__set('id_status', 1); // Supondo que 1 é o ID para 'Pendente'

        $tarefaService = new TarefaService($conexao, $tarefa);
        $tarefas = $tarefaService->recuperarTarefasPendentes();
        break;

    default:
        // Caso a ação não seja reconhecida
        echo "Ação inválida ou não definida.";
        break;
}

?>
