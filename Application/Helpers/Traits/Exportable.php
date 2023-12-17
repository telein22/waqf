<?php

namespace Application\Helpers\Traits;

trait Exportable
{
    public function buildTable(array $data): string
    {
        $html = "<table>";
        foreach ($data as $row) {
            $html .= '<tr>';
            $html .= implode('', array_map(fn($item) => "<td>{$item}</td>", $row));
            $html .= "</tr>";
        }

        $html .= "</table>";

        return $html;
    }
}