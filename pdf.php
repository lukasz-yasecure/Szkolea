<?php

/**
 * klasa generuje pliki pdf
 */
include('fpdf/fpdf.php');

class Pdf extends FPDF
{
    public $type; 
    // header
    function Header()
    {
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30,10,'Faktura '.$this->type,0,0,'C');
        // Line break
        $this->Ln(20);
    }

    function generate($type,$data)
    {
        $this->type = $type;
        $this->AddPage();
        $this->SetFont('Arial','',12);
        $this->MultiCell(0,5,$data);
        $this->Ln();
        $this->Output();       
    }

    // footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Strona: '.$this->PageNo(),0,0,'C');
    }    
}

$pdf = new Pdf();
$pdf->generate('VAT','text text text');
?>
