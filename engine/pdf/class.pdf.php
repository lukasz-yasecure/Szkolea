<?php

/**
 * klasa generuje pliki pdf
 */
require_once('fpdf/fpdf.php');

class Pdf extends FPDF
{
    public $type; 
    function cnv($str){
        return iconv("utf-8", "iso-8859-2", $str);
    }
    
    // header
    function Header()
    {



    }

    function generate(User $u,$f,$type)
    {
        // STD::pre($u);exit;
        $this->AddPage();
       
         // Sprawdzany typ faktury
        if($type == 'fpf') $what = ' PRO FORMA';
        else $what = ' VAT';

        $top_right = 'Numer faktury ' . $f->numer_fpf . '/Szkolea/' . date('Y',$f->data_fpf); // Numer faktury
        $title = 'FAKTURA ' . $what; // Faktura proforma/vat
        
        // Header 
        $this->AddFont('Arial','','arial.php');$this->SetFont('Arial','',14);
        $this->Cell(100,5,$this->cnv("VALUE CREATION, Jarosław Rubin"));
        $this->SetFont('Arial','',11);
        $this->Cell(90,5,$this->cnv($top_right),0,0,'R');$this->Ln();
        $this->Cell(100,5,$this->cnv("ul. Pilata 12/2, 51-605 Wrocław"));
        $this->Cell(90,5,$this->cnv("ORYGINAŁ/KOPIA"),0,0,'R');$this->Ln();
        $this->SetFont('Arial','b',11);
        $this->Cell(0,5,$this->cnv("NIP 898-100-07-58"));$this->Ln();
        $this->SetFont('Arial','',11);
        $this->Cell(0,5,$this->cnv("PKO BP SA - Inteligo"));$this->Ln();
        $this->SetFont('Arial','b',11);
        $this->Cell(0,5,$this->cnv("nr 50102055581111118939500024"));$this->Ln(20);
        $this->SetFont('Arial','b',20);
        $this->Cell(0,10,$this->cnv($title),1,1,'C');$this->Ln(5);

        // Dane odbiorcy
        $this->AddFont('Arial','','arial.php');
        $this->SetFont('Arial','',11);
        $name = (
            $u->getF_name()!=NULL ? 
                $u->getF_company().','.$u->getF_name().' '.$u->getF_surname() : 
                $u->getOs_name().' '.$u->getOs_surname()
            );
        $this->Cell(100,5,'Nazwa: ' . $this->cnv($name)); // nazwa
        $this->Cell(90,5,'Data wystawienia: ' . date('d m Y', $f->data_fpf),0,0,'R');$this->Ln(); // nazwa
        $adress = (
            $u->getF_street()!=NULL ? 
                $u->getF_street().' '.$u->getF_house_number() : 
                $u->getOs_street().' '.$u->getOs_house_number()
            );
        $this->Cell(100,5,'Adres: ' . $this->cnv($adress));
        $this->Cell(90,5,$this->cnv('Data sprzedaży: ') . date('d m Y', $f->data_fpf),0,0,'R');$this->Ln(); // nazwa
        $city = (
            $u->getF_city()!=NULL ? 
                $u->getF_postcode().', '.$u->getF_city() : 
                $u->getOs_postcode().', '.$u->getOs_city()
            );       
        $this->Cell(100,5, $this->cnv('Kod, miejscowość: ') . $this->cnv($city));
        $this->Cell(90,5,$this->cnv('Termin zapłaty: Przelew 7 dni'),0,0,'R');$this->Ln(); // nazwa
        if($u->getF_nip()!=NULL) $this->Cell(0,5, 'NIP: ' . $this->cnv($u->getF_nip()));
        $this->Ln(10);
        
        // Tabela Header
        $this->SetFont('Arial','',10);
        $this->Cell(12,7,$this->cnv('Ilość'),1,0,'C');
        $this->Cell(75,7,$this->cnv('Nazwa towaru lub usługi'),1,0,'C');
        $this->Cell(15,7,$this->cnv('PKWiU'),1,0,'C');
        $this->Cell(8,7,$this->cnv('JM'),1,0,'C');
        $this->Cell(20,7,$this->cnv('Cena netto'),1,0,'C');
        $this->Cell(25,7,$this->cnv('Wartość netto'),1,0,'C');
        $this->Cell(10,7,$this->cnv('VAT'),1,0,'C');
        $this->Cell(25,7,$this->cnv('Wartość VAT'),1,0,'C');
        $this->Ln();
 
        // Tabela 
        $vatproc = 0.23; 
        $vat = $f->kwota_brutto * $vatproc;

        $towar = ($f->typ == 1 ? 'Prowizja' : 'Pakiet');
       
        // Tu ma być pętla
        $this->SetFont('Arial','',10);
        $this->Cell(12,7,'1',1,0,'C');
        $this->Cell(75,7,$this->cnv($towar),1,0,'C');
        $this->Cell(15,7,'7312C',1,0,'C');
        $this->Cell(8,7,'1',1,0,'C');
        $this->Cell(20,7,$f->kwota_brutto-$vat.$this->cnv(' zł'),1,0,'C');
        $this->Cell(25,7,$f->kwota_brutto-$vat.$this->cnv(' zł'),1,0,'C');
        $this->Cell(10,7,($vatproc*100).'%',1,0,'C');
        $this->Cell(25,7,$vat.$this->cnv(' zł'),1,0,'C');
        $this->Ln(20);  
        
        // Podsumowanie        
        $this->Cell('90');
        $this->Cell(30,7,$this->cnv('Do zapłaty'));
        $this->Cell(30,7,$this->cnv('VAT'),0,0,'R');
        $this->Cell(10);
        $this->Cell(30,7,$this->cnv('NETTO'));
        $this->Ln();
        $this->Cell('90');
        $this->Cell(30,7,$f->kwota_brutto.$this->cnv(' zł'),1);
        $this->Cell(30,7,$vat.$this->cnv(' zł'),1,0,'R');
        $this->Cell(10);
        $this->Cell(30,7,$f->kwota_brutto-$vat.$this->cnv(' zł'),1);
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
