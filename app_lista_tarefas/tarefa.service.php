<?php

// CRUD
class TarefaService {

    private $conexao;
    private $tarefa;

    public function __construct(Conexao $conexao, Tarefa $tarefa) {
        $this->conexao = $conexao->conectar();
        $this->tarefa = $tarefa;
    }

    public function inserir() { // Create
        $query = '
            INSERT INTO tb_tarefas (tarefa, prazo, categoria, prioridade) 
            VALUES (:tarefa, :prazo, :categoria, :prioridade)
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':tarefa', $this->tarefa->__get('tarefa'));
        $stmt->bindValue(':prazo', $this->tarefa->__get('prazo'));
        $stmt->bindValue(':categoria', $this->tarefa->__get('categoria'));
        $stmt->bindValue(':prioridade', $this->tarefa->__get('prioridade'));
        $stmt->execute();
    }

    public function recuperar() { // Read
        $query = '
            SELECT 
                t.id, s.status, t.tarefa, t.prazo, t.categoria, t.prioridade 
            FROM 
                tb_tarefas AS t
                LEFT JOIN tb_status AS s ON (t.id_status = s.id)
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function atualizar() { // Update
        $query = '
            UPDATE tb_tarefas 
            SET tarefa = ?, prazo = ?, categoria = ?, prioridade = ? 
            WHERE id = ?
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(1, $this->tarefa->__get('tarefa'));
        $stmt->bindValue(2, $this->tarefa->__get('prazo'));
        $stmt->bindValue(3, $this->tarefa->__get('categoria'));
        $stmt->bindValue(4, $this->tarefa->__get('prioridade'));
        $stmt->bindValue(5, $this->tarefa->__get('id'));
        return $stmt->execute(); 
    }

    public function remover() { // Delete
        $query = 'DELETE FROM tb_tarefas WHERE id = :id';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':id', $this->tarefa->__get('id'));
        $stmt->execute();
    }

    public function marcarRealizada() { // Update
        $query = "UPDATE tb_tarefas SET id_status = ? WHERE id = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(1, $this->tarefa->__get('id_status'));
        $stmt->bindValue(2, $this->tarefa->__get('id'));
        return $stmt->execute(); 
    }

    public function recuperarTarefasPendentes() {
        $query = '
            SELECT 
                t.id, s.status, t.tarefa, t.prazo, t.categoria, t.prioridade 
            FROM 
                tb_tarefas AS t
                LEFT JOIN tb_status AS s ON (t.id_status = s.id)
            WHERE
                t.id_status = :id_status
        ';
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':id_status', $this->tarefa->__get('id_status'));
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Novo método para ordenar tarefas
    public function ordenarTarefas($criterio = 'data_cadastro', $ordem = 'ASC') {
        // Validação básica dos parâmetros de ordenação
        $criterio = in_array($criterio, ['data_cadastro', 'prazo', 'prioridade']) ? $criterio : 'data_cadastro';
        $ordem = strtoupper($ordem) === 'DESC' ? 'DESC' : 'ASC';

        $query = '
            SELECT 
                t.id, s.status, t.tarefa, t.prazo, t.categoria, t.prioridade 
            FROM 
                tb_tarefas AS t
                LEFT JOIN tb_status AS s ON (t.id_status = s.id)
            ORDER BY ' . $criterio . ' ' . $ordem;
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Novo método para filtrar tarefas
    public function filtrarTarefas($status = null, $categoria = null) {
        $query = '
            SELECT 
                t.id, s.status, t.tarefa, t.prazo, t.categoria, t.prioridade 
            FROM 
                tb_tarefas AS t
                LEFT JOIN tb_status AS s ON (t.id_status = s.id)
            WHERE 1=1
        ';
        if ($status !== null) {
            $query .= ' AND t.id_status = :status';
        }
        if ($categoria !== null) {
            $query .= ' AND t.categoria = :categoria';
        }
        $stmt = $this->conexao->prepare($query);
        if ($status !== null) {
            $stmt->bindValue(':status', $status);
        }
        if ($categoria !== null) {
            $stmt->bindValue(':categoria', $categoria);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Novo método para arquivar tarefas
    public function arquivarTarefa() {
        $query = "UPDATE tb_tarefas SET id_status = ? WHERE id = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(1, 4); // Supondo que 4 seja o ID de arquivada
        $stmt->bindValue(2, $this->tarefa->__get('id'));
        return $stmt->execute(); 
    }
}

?>
