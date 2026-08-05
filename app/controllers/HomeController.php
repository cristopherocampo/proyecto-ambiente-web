<?php
class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }
    public function index(): void
    {
        $this->redirect("/catalogo/index");
    }
}
