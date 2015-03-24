<?php

class PDF extends EPdfFactoryDoc
{

    protected function initDocInfo()
    {
    	$pdf = $this->getPdf();
    	$pdf->SetTitle('KickAds');
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
        	$pdf->MultiCell($size[0], $size[1], strtoupper($key), 1, 'L', true, 0);

        	if ($even) {
        		$pdf->SetFillColor(240, 240, 240);
        	} else {
        		$pdf->SetFillColor(220, 220, 220);
        	}
        	$even = ! $even;

        	$pdf->SetTextColor(0);
            // $pdf->Cell(0, 7, strtoupper($value), 1, 1, 'L', true);
        	$pdf->MultiCell(0, 7, strtoupper($value), 1, 'L', true, 1);

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
     * Print Terms and Condictions to pdf
     * @param  $pdf TCPDF object
     */
	protected function printTerms($pdf)
	{
		$pdf->SetTextColor(0);
		$pdf->Write(0, $this->terms, 0, false, 'J', true);
	}

	/**
     * Print signature section to pdf
     * @param  $pdf TCPDF object
     */
    protected function printSignature($pdf,$name,$company='TML Media')
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
        $pdf->Image(Yii::getPathOfAlias('webroot') . '/themes/tml/img/firma.png',$pdf->getX()+5,$pdf->getY()-20,'40%','40%');
        $pdf->Cell(100, 10, '_________________________________', 0, 0, 'L', false);
        $pdf->Ln();
        $pdf->Cell(10, 10, '', 0, 0, 'L', false);
        $pdf->Cell(80, 4, $name, 0, 0, 'L', false);
        $pdf->Cell('10%', 10, '', 0, 0, 'L', false);
        $pdf->Cell(100, 4, $company, 0, 1, 'L', false);
        $pdf->Ln();
        $pdf->Cell(10, 10, '', 0, 0, 'L', false);
        $pdf->Cell(80, 4, 'By:___________________', 0, 0, 'L', false);
        $pdf->Cell('10%', 10, '', 0, 0, 'L', false);
        $pdf->Cell(100, 4, 'By: ', 0, 1, 'L', false);
        $pdf->Ln();
        $pdf->Cell(10, 10, '', 0, 0, 'L', false);
        $pdf->Cell(80, 4, 'Title:___________________', 0, 0, 'L', false);
        $pdf->Cell('10%', 10, '', 0, 0, 'L', false);
        $pdf->Cell(100, 4, 'Title:', 0, 1, 'L', false);
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

    protected $terms = "Payment terms: Payment net 30 days from invoicing date.
		
		Aditional terms:
		If any deduction/tax applies, it must be paid by customer. signing this \"insertion order\" we do accept the terms and conditions from KICKADS. KICKADS and the company has the right to cancel the campaign, any time, providing the other party 24 hours labour days notice. KICKADS will invoice based on records from current systems. 

		Client is invoiced on the date the io is consumed or end of month, whichever comes first. The client has 30 days to pay the invoice from the invoice date. 

		I hereby agree to the terms and conditions. I also declare that i'm authorized and empowered enough to sign this document and i have received a copy. The parties agree that any work orders, proposals and insertion order are subject to modifications or amendments to the sole discretion of the company. In case of conflict between terms and conditions and the terms of this agreement be taken as valid signed terms here.

		Advertiser shall immediately notify KICKADS of any suspected fraudulent or illegal activity, and shall submit any lead disputes no later than 5 days after suspected fraudulent lead has been registered. For all lead disputes, advertiser shall provide valid and reasonable evidence supporting the basis for such dispute, including but not limited to contact information, timestamp, ip address, proof of multiple uses of the same credit card and fraudulent information entered.

		As part of this agreement with KICKADS, the client agrees to implement a server side pixel that will enable KICKADS to independently validate any conversions that are received. KICKADS will assist the client regarding the technical requirements for implementing this pixel, which when validated will allow KICKADS manage cpa campaigns effectively.
        ";

    public static function getPath() 
    {
        return Yii::getPathOfAlias('webroot') . "/uploads/";
    }

}