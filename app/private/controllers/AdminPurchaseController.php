<?php

loadController('AdminController');

class AdminPurchaseController extends AdminController {

    public function indexAction(){

        $this->setVar("title","Purchase a Key");
        $this->render("admin/purchase/index");
    }

}