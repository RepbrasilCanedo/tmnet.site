<?php

namespace App\adms\Models\helper;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsPagination
{
    private int $page;
    private int $limitResult;
    private int $offset;
    private string $query;
    private string|null $parseString;
    private array|null $resultBd;
    private string|null $result;
    private int $total_paginas;
    private int $totalPages;
    private int $maxLinks = 2;
    private string $link;
    private string|null $var;

    function getOffset(): int { return $this->offset; }
    function getResult(): string|null { return $this->result; }

    function __construct(string $link, string|null $var = null)
    {
        $this->link = $link;
        // CORREÇÃO: Garante que os parâmetros de busca comecem com ?
        $this->var = (!empty($var)) ? "?" . $var : "";
    }

    public function condition(int $page, int $limitResult): void
    {
        $this->page = (int) $page > 0 ? $page : 1;
        $this->limitResult = (int) $limitResult;
        $this->offset = (int) ($this->page * $this->limitResult) - $this->limitResult;
    }

    public function pagination(string $query, string|null $parseString = null): void
    {
        $this->query = (string) $query;
        $this->parseString = (string) $parseString;
        $count = new \App\adms\Models\helper\AdmsRead();
        $count->fullRead($this->query, $this->parseString);
        //  Garante que se o resultado for null, ele vire um array vazio
        $this->resultBd = $count->getResult() ?? [['num_result' => 0]];
        $this->pageInstruction();
    }

    private function pageInstruction(): void
    {
        $this->total_paginas = $this->resultBd[0]['num_result'] ?? 0;

        if ($this->total_paginas > 0) {
            $this->totalPages = (int) ceil($this->total_paginas / $this->limitResult);
            if ($this->totalPages >= $this->page) {
                $this->layoutPagination();
            } else {
                header("Location: {$this->link}{$this->var}");
                exit();
            }
        } else {
            $this->result = null;
        }
    }

    private function layoutPagination(): void
    {
        $this->result = "<div class='content-pagination'>";
        $this->result .= "<div class='pagination'>";

        // Link Primeira Página
        $this->result .= "<a href='{$this->link}/1{$this->var}'>Primeira</a>";

        // Páginas Anteriores
        for ($beforePage = $this->page - $this->maxLinks; $beforePage <= $this->page - 1; $beforePage++) {
            if ($beforePage >= 1) {
                $this->result .= "<a href='{$this->link}/$beforePage{$this->var}'>$beforePage</a>";
            }
        }

        // Página Ativa
        $this->result .= "<a href='#' class='active'>{$this->page}</a>";

        // Próximas Páginas
        for ($afterPage = $this->page + 1; $afterPage <= $this->page + $this->maxLinks; $afterPage++) {
            if ($afterPage <= $this->totalPages) {
                $this->result .= "<a href='{$this->link}/$afterPage{$this->var}'>$afterPage</a>";
            }
        }

        // Última Página
        $this->result .= "<a href='{$this->link}/{$this->totalPages}{$this->var}'>Última</a>";

        $this->result .= "</div>";
        $this->result .= "</div>";
        
        $_SESSION['resultado'] = $this->total_paginas;
    }
}