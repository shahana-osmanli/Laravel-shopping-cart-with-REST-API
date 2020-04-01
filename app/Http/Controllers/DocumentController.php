<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class DocumentController extends Controller
{
    public function docfile()
    {
        $word = new \PhpOffice\PhpWord\PhpWord();
        $section = $word->addSection();
        $products = Product::get();
        $text = $section->addText($products,array('name'=>'Arial','size' => 14,'bold' => true));
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($word, 'Word2007');
        $objWriter->save(public_path('Products.docx')); 
        try {
            $objWriter->save(public_path('Products.docx'));
        } catch (Exception $e) {
        }
        return "ok";
    }
}
