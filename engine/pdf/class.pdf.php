<?php

/**
 * klasa generuje pliki pdf
 */
require_once('fpdf/fpdf.php');

class Pdf extends FPDF
{
    public $type; 
    // header
    function Header()
    {
        // Arial bold 15
        $this->AddFont('Arial','','arial.php');
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30,10,$this->title,0,0,'C');
        // Line break
        $this->Ln(20);
    }

    function generate(User $u,$f,$type)
    {
        // Sprawdzany typ faktury
        if($type == 'fpf') {
            $what = ' pro forma';
        }
        $this->title = 'Faktura ' . $f->numer_fpf . '/Szkolea/' . date('Y',$f->data_fpf).$what; // Tytuł faktury. Numer ma być liczbą
        
        $this->AddPage();
        $this->AddFont('Arial','','arial.php');
        $this->SetFont('Arial','',12);

        // dane faktury
        $this->Cell(0,5,'id_faktura: ' . $f->id_faktura); // id_faktura
        $this->Ln();
        $this->Cell(0,5,'id_user: ' . $f->id_user); // id_user
        $this->Ln();
        $this->Cell(0,5,'typ: ' .$f->typ); // typ
        $this->Ln();
        $this->Cell(0,5,'kwota_brutto: ' . $f->kwota_brutto); // kwota_brutto
        $this->Ln();
        $this->Cell(0,5,'id_pakiet: ' . $f->id_pakiet); // id_pakiet
        $this->Ln();
        $this->Cell(0,5,'id_oferta: ' . $f->id_oferta); // id_oferta
        $this->Ln();
        $this->Cell(0,5,'numer_fv: ' . $f->numer_fv); // numer_fv
        $this->Ln();
        $this->Cell(0,5,'data_fv: ' . $f->data_fv); // data_fv
        $this->Ln();
        $this->Cell(0,5,'numer_fpf: ' . $f->numer_fpf); // numer_fpf
        $this->Ln();
        $this->Cell(0,5,'data_fpf: ' . date('d m Y', $f->data_fpf)); // data_fpf
        $this->Ln();
        $this->Ln();

        // dane użytkownika
        $this->Cell(0,5,'id_user: ' .$u->getId_user()); // id_user
        $this->Ln();
        $this->Cell(0,5,'email: ' . $u->getEmail()); // email
        $this->Ln();
        $this->Cell(0,5,'status: ' . $u->getStatus()); // status
        $this->Ln();
        $this->Cell(0,5,'kind: ' . $u->getKind()); // kind
        $this->Ln();
        $this->Cell(0,5,'os_name: ' . iconv("utf-8", "iso-8859-2", $u->getOs_name())); // os_name
        $this->Ln();
        $this->Cell(0,5,'os_surname: ' . iconv("utf-8", "iso-8859-2", $u->getOs_surname())); // os_surname
        $this->Ln();
        $this->Cell(0,5,'os_street: ' . iconv("utf-8", "iso-8859-2", $u->getOs_street())); // os_street
        $this->Ln();
        $this->Cell(0,5,'os_house_number: ' . $u->getOs_house_number()); // os_house_number
        $this->Ln();
        $this->Cell(0,5,'os_postcode: ' . $u->getOs_postcode()); // os_postcode
        $this->Ln();
        $this->Cell(0,5,'os_city: ' . iconv("utf-8", "iso-8859-2", $u->getOs_city())); // os_city
        $this->Ln();
        $this->Cell(0,5,'os_woj: ' . $u->getOs_woj()); // os_woj
        $this->Ln();
        $this->Cell(0,5,'os_phone: ' . $u->getOs_phone()); // os_phone
        $this->Ln();
         
        
        $this->Output();       
    }

    // footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->AddFont('Arial','','arial.php');
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Strona: '.$this->PageNo(),0,0,'C');
    }    
}
?>
