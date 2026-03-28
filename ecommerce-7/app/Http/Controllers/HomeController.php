<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = [
            [
                'name' => 'Product 1',
                'price' => 100000,
                'image' => 'https://img.lazcdn.com/g/p/979fc2a3327d6cfd87ed74bfdc8beb26.jpg_720x720q80.jpg'
            ],
            [
                'name' => 'Product 2',
                'price' => 250000,
                'image' => 'https://img.lazcdn.com/g/p/0e448864b6f3c0c80df9731d6c97a2be.jpg_720x720q80.jpg'
            ],
            [
                'name' => 'Product 3',
                'price' => 150000,
                'image' => 'https://img.lazcdn.com/g/p/7b8c9e5d94dcce4e8b1f2e8f32a02766.jpg_720x720q80.jpg'
            ]
        ];

        $accordionItems = [
            [
                'title' => 'Accordion Item #1',
                'body' => 'content for accordion item #1.'
            ],
            [
                'title' => 'Accordion Item #2',
                'body' => 'content for accordion item #2.'
            ],
            [
                'title' => 'Accordion Item #3',
                'body' => 'content for accordion item #3.'
            ]
        ];
        return view('home', compact('products', 'accordionItems'));
    }

    public function productDetail()
    {
        return view('product_detail');
    }

}
