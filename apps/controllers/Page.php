<?php

class Page extends Controller {
    public function index() {
        $this->swoole->tpl->display();
    }
}