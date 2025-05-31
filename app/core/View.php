<?php
namespace Core;

class View {
    public function render($vista, $data = []) {
        extract($data);
        require __DIR__ . '/../views/' . $vista . '.php';
    }
}
