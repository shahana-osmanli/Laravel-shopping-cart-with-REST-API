<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class DocumentController extends Controller
{
    public function fileDoc()
    {
        $products = Product::get();

        $word = new \PhpOffice\PhpWord\PhpWord();
        $section = $word->addSection();

        $styleTable = array('borderSize'=>6, 'borderColor'=>'006699', 'cellMargin'=>80);
        $word->addTableStyle('tableProducts', $styleTable);
        $table = $section->addTable('tableProducts');

        $styleCell = array('valign'=>'center');
        $fontStyle = array('italic'=>true, 'align'=>'center');

        $firstRow = $table->addRow();
                            $firstRow->addCell(3000, $styleCell)
                                    ->addText('ID', $fontStyle);
                            $firstRow->addCell(3000, $styleCell)
                                    ->addText('PRICE', $fontStyle);
                            $firstRow->addCell(3000, $styleCell)
                                    ->addText('CREATED_AT', $fontStyle);
        
        for( $i = 0; $i < count($products); $i++){
            $row = $table->addRow();
            $cell = $row->addCell(3000, $styleCell)
                        ->addText($products[$i]->id, $fontStyle);
            $row->addCell(3000, $styleCell)
                ->addText($products[$i]->price, $fontStyle);
            $row->addCell(3000, $styleCell)
                ->addText($products[$i]->created_at, $fontStyle);
        }
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($word, 'Word2007');
        $objWriter->save(public_path('Products.docx')); 
        return "ok";
    }
}
