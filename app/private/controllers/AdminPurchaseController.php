<?php

loadController('AdminController');

class AdminPurchaseController extends AdminController {

    public function indexAction() {
        $this->render("admin/purchase/index");
    }

}