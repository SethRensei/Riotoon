<?php

namespace Riotoon\Service;

use PDO;
use Riotoon\Service\DbConnection;

class Pagination
{
    private $connec;

    private $limit;

    private $page;

    private $query;

    private $total;

    private $row_start;

    /**
     * Constructor method to initialize Pagination object.
     * @param string $query The SQL query string.
     */
    public function __construct(string $query)
    {
        $this->connec = DbConnection::GetConnection(); // Establish database connection
        $this->query = $query; // Assign SQL query string

        $rs = $this->connec->query($this->query);
        $this->total = count($rs->fetchAll(PDO::FETCH_ASSOC)); // Count total number of rows
    }

    /**
     * Retrieve paginated data.
     * @param mixed $fetch_class The class name to fetch the data into.
     * @param int $limit The limit of records per page (default is 10).
     * @param int $page The current page number (default is 1).
     * @return array An array of fetched data objects.
     */
    public function getData($fetch_class, int $limit = 10, int $page = 1)
    {
        $this->limit = $limit;
        $this->page = $page;

        // aucune limitation nécessaire, utilisez la requête telle quelle
        if ($this->limit == "all") {
            $query = $this->query;
        } else {
            $this->row_start = (($this->page - 1) * $this->limit); // the limit records from page; to limit
            $query = $this->query . " LIMIT {$this->limit} OFFSET {$this->row_start}"; //add to original query
        }

        $rs = $this->connec->query($query) or die($this->connec->errorInfo());

        return $rs->fetchAll(PDO::FETCH_CLASS, $fetch_class);
    }

    /**
     * Generate pagination links.
     * @param mixed $links The number of links to display.
     * @param mixed $list_class The CSS class for the pagination list.
     * @return string HTML markup for pagination links.
     */
    public function createLinks($links, $list_class)
    {
        if ($this->limit == 'all') {
            return '';
        }
        // On calcule la dernière page
        $last = ceil($this->total / $this->limit);

        // Calculer le début de la plage pour l'impression du lien
        $start = (($this->page - $links) > 0) ? $this->page - $links : 1;

        // Calculer la fin de la plage pour l'impression du lien
        $end = (($this->page + $links) < $last) ? $this->page + $links : $last;

        //ul bootstrap class - 'pagination pagination-sm'
        $html = '<nav data-pagination><ul class ="' . $list_class . '">';

        $class = ($this->page === 1) ? 'disabled' : ''; //Désactive le lien vers la page précédente <<

        //$this->page - 1 = previous page (<< link)
        $previous_page = ($this->page == 1) ? '<li><a href="" ' . $class . '><i class="fas fa-chevron-left"></i></a></li>' : //remove link from previous button
            '<li><a href="?page=' . ($this->page - 1) . '"' . $class . '><i class="fas fa-chevron-left"></i></a></li>';

        $html .= $previous_page;

        if ($start > 1) {
            $html .= '<li><a href="?page=1">1</a></li>'; // Affiche la première page
            $html .= '<li><span>...</span></li>';
        }

        // On affiche tous les numéros de page
        for ($i = $start; $i <= $end; $i++) {
            $class = ($this->page == $i) ? 'active' : ''; // Active la page courante
            $html .= '<li class="' . $class . '"><a href="?page=' . $i . '">' . $i . '</a></li>';
        }

        if ($end < $last) {
            $html .= '<li><span>...</span></li>';
            $html .= '<li><a href="?page=' . $last . '">' . $last . '</a></li>'; // affiche la première page
        }

        $class = ($this->page == $last) ? 'disabled' : ''; // Désactive le lien vers la page suivante >>

        // Crée les liens et transmettre la limite et la page en tant que paramètres $_GET

        //$this->page - 1 = previous page (<<< link)
        $next_page = ($this->page == $last) ? '<li><a href="" ' . $class . '><i class="fas fa-chevron-right"></i></a></li>' :
            '<li><a href="?page=' . ($this->page + 1) . '"' . $class . '><i class="fas fa-chevron-right"></i></a></li>';

        $html .= $next_page;
        $html .= '</ul></nav>';

        return $html;
    }
}
