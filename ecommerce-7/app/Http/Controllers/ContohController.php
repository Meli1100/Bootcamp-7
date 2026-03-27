<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContohController extends Controller
{
    public function index()
    {
        $name = "Meli";
        $html = "<h1>Hello World</h1>";
        $angka = 12345;
        $date = date('Y-m-d H:i:s');

        $fruits = ['Apple', 'Banana', 'Cherry'];

        return view('contoh.index', compact('name', 'html', 'angka', 'date', 'fruits'));
    }
}
