<?php
namespace app\index\controller;

class Index extends Check
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        return view('index');
    }
}
