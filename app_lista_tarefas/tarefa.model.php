<?php

class Tarefa {
    private $id;
    private $id_status;
    private $tarefa;
    private $data_cadastro;
    private $prazo;           // Novo atributo para prazos
    private $categoria;       // Novo atributo para categorias
    private $prioridade;      // Novo atributo para prioridade

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }
    
    // Novo método para obter a prioridade formatada
    public function obterPrioridadeFormatada() {
        switch($this->prioridade) {
            case 1:
                return 'Baixa';
            case 2:
                return 'Média';
            case 3:
                return 'Alta';
            default:
                return 'Não definida';
        }
    }
}

?>