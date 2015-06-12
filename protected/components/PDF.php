<?php

class PDF extends EPdfFactoryDoc
{

    protected function initDocInfo()
    {
    	$pdf = $this->getPdf();
    	$pdf->SetTitle('TheMediaLab');
        $pdf->SetSubject('IO');
        // $pdf->SetKeywords('x, y, z');
    }

    protected function initFooter()
    {
        $this->getPdf()->setPrintFooter(false);
    }

    /**
     * Print a title to pdf
     * @param  $pdf   TCPDF object
     * @param  $title string to display
     */
    protected function printTitle($pdf, $title)
    {
    	$pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->Cell(0, 7, strtoupper($title), 0, 1, 'L', true);
    }
    
    /**
     * Print table to pdf
     * @param  $pdf  TCPDF object
     * @param  $info Associative array with values
     */
    protected function printTable($pdf, $info,$size=array(60,7))
    {
        $even = true;

		foreach ($info as $key => $value) {
			$pdf->SetFillColor(8, 150, 153);
        	$pdf->SetTextColor(255);
            // $pdf->Cell(60, 7, strtoupper($key), 1, 0, 'L', true);
        	
            // $w,
            // $h,
            // $txt,
            // $border = 0,
            // $align = 'J',
            // $fill = false,
            // $ln = 1,
            // $x = '',
            // $y = '',
            // $reseth = true,
            // $stretch = 0,
            // $ishtml = false,
            // $autopadding = true,
            // $maxh = 0,
            // $valign = 'T',
            // $fitcell = false 

            $pdf->MultiCell($size[0], $size[1], strtoupper($key), 1, 'L', true, 0, '', '', true, 0, false, true, $size[1], 'M', false);

        	if ($even) {
        		$pdf->SetFillColor(240, 240, 240);
        	} else {
        		$pdf->SetFillColor(220, 220, 220);
        	}
        	$even = ! $even;

        	$pdf->SetTextColor(0);
            // $pdf->Cell(0, 7, strtoupper($value), 1, 1, 'L', true);
        	$pdf->MultiCell(0, 7, strtoupper($value), 1, 'L', true, 1, '', '', true, 0, false, true, 7, 'M', false);

		}
    }

    /**
     * Print company information to pdf
     * @param  $pdf TCPDF object
     */
    protected function printSpace($pdf,$space)
    {

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(255);
        $pdf->Cell($space, 7, ' ', 0, 0, 'L', true);
    }
     /**
     * Print company information to pdf
     * @param  $pdf TCPDF object
     */
    protected function printInfo($pdf,$title,$table)
    {

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
        $pdf->Cell(80, 7, $title, 0, 1, 'L', true);
        $table=$table;
        $this->printTable($pdf,$table,array(40,7));
    }

    /**
     * [printPayment description]
     * @param  [type] $pdf [description]
     * @return [type]      [description]
     */
    protected function printPayment($pdf, $payment)
    {
        $this->payment = "Payment Terms: ".$payment;
        if(is_numeric($payment)) $this->payment .= " days.";

        $pdf->SetTextColor(0);
        $pdf->SetFont('helveticaB','',7);
        $pdf->Write(0, $this->payment, 0, false, 'L', true);
    }

    /**
     * Print Terms and Condictions to pdf
     * @param  $pdf TCPDF object
     */
	protected function printTerms($pdf)
	{
		$pdf->SetTextColor(0);
        $pdf->SetFont('helveticaB','',5);
		$pdf->Write(0, $this->terms, 0, false, 'J', true);
	}

	/**
     * Print signature section to pdf
     * @param  $pdf TCPDF object
     */
    protected function printSignature($pdf,$name,$company='TML Media LLC',$signature=null)
    {
        $pdf->SetTextColor(0);  
        $pdf->Cell(50, 10, '', 0, 0, 'L', false);
        $pdf->Cell(50, 10, '', 0, 0, 'L', false);
        $pdf->Cell(30, 10, '', 0, 0, 'L', false);
        $pdf->Ln();   
        $pdf->SetFont('helveticaB','',8.5);
        $pdf->Cell(10, 10, '', 0, 0, 'L', false);
        $pdf->Cell(80, 10, '_________________________________', 0, 0, 'L', false);
        $pdf->Cell('10%', 10, '', 0, 0, 'L', false);
        if($signature == 'Matt')
            $pdf->Image(Yii::getPathOfAlias('webroot') . '/themes/tml/img/matt_signature.png',$pdf->getX()+8,$pdf->getY()-17,'40%','40%');
        $pdf->Cell(100, 10, '_________________________________', 0, 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell(10, 10, '', 0, 0, 'L', false);
        $pdf->Cell(80, 4, $name, 0, 0, 'L', false);
        $pdf->Cell('10%', 10, '', 0, 0, 'L', false);
        $pdf->Cell(100, 4, $company, 0, 1, 'L', false);
        $pdf->Ln();
        $pdf->Cell(10, 10, '', 0, 0, 'L', false);
        $pdf->Cell(80, 4, 'Name:___________________________', 0, 0, 'L', false);
        $pdf->Cell('10%', 10, '', 0, 0, 'L', false);
        $sigName = $signature == 'Matt' ? 'Matías Vernetti' : '';
        $pdf->Cell(100, 4, 'Name: '.$sigName, 0, 1, 'L', false);
        $pdf->Ln();
        $pdf->Cell(10, 10, '', 0, 0, 'L', false);
        $pdf->Cell(80, 4, 'Title:____________________________', 0, 0, 'L', false);
        $pdf->Cell('10%', 10, '', 0, 0, 'L', false);
        $sigTittle = $signature == 'Matt' ? 'Jack of All Trades' : '';
        $pdf->Cell(100, 4, 'Title: '.$sigTittle, 0, 1, 'L', false);
    }

    /**
     * Set general configuration for pdf
     * @param  $pdf TCPDF object
     */
    protected function setConfig($pdf)
    {
    	$pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(0.5);
        $pdf->SetFont('helvetica','',10);
    }

    protected $payment = "";
    protected $terms = "";

    public static function getPath() 
    {
        return Yii::getPathOfAlias('webroot') . "/uploads/";
    }

}