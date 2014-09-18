<?php

class Pdf extends EPdfFactoryDoc
{


    public function getPdfName()
    {
    	return 'IO-' . $this->getDataItem('io')->id . '-KickAds.pdf';
    }

	public function renderPdf()
    {
    	$this->addPage();
        $pdf = $this->getPdf();	// get TCPDF instance

        $this->setConfig($pdf); // set format configuration

        // Print presentation
		$io  = $this->getDataItem('io');
        $this->printCompanyInfo($pdf, $io);
        $pdf->Ln();

        // get advertisers.
		$adv = $this->getDataItem('advertiser');

        // Print io section
		unset($values);
		$values[$io->getAttributeLabel('commercial_name')] = $io->name;
		$values[$io->getAttributeLabel('tax_id')] = $io->tax_id;
        $values[$io->getAttributeLabel('address')] = $io->address;
        $values[$io->getAttributeLabel('state')] = $io->state;
        $values[$io->getAttributeLabel('zip_code')] = $io->zip_code;
        $values[$io->getAttributeLabel('phone')] = $io->phone;
        $values[$io->getAttributeLabel('contact_com')] = $io->contact_com;
        $values[$io->getAttributeLabel('email_adm')] = $io->email_adm;
        $values[$io->getAttributeLabel('contact_adm')] = $io->contact_adm;
        $values[$io->getAttributeLabel('currency')] = $io->currency;
        $values[$io->getAttributeLabel('ret')] = $io->ret;
		$this->printTitle($pdf, 'Io');
		$this->printTable($pdf, $values);
		$pdf->Ln();

		// Print Opportunities section
		$opps = $this->getDataItem('opportunities');
		$pdf->Ln();
		unset($values);
        $i = 1;
		foreach ($opps as $opp) {
			$this->printTitle($pdf, 'Campaign #' . $i);
			$values[$opp->getAttributeLabel('carriers_id')] = $opp->carriers ? $opp->carriers->mobile_brand : '';
            $values[$opp->getAttributeLabel('rate')] = $opp->rate;
			$values[$opp->getAttributeLabel('model_adv')] = $opp->model_adv;
			$values[$opp->getAttributeLabel('product')] = $opp->product;
			$values[$opp->getAttributeLabel('comment')] = $opp->comment;
			$values[$opp->getAttributeLabel('wifi')] = $opp->wifi ? 'Habilitado' : 'Inhabilitado';
			$values[$opp->getAttributeLabel('budget')] = $opp->budget;
			$values[$opp->getAttributeLabel('startDate')] = $opp->startDate == 0 ? '' : date('d-m-Y', strtotime($opp->startDate));
			$values[$opp->getAttributeLabel('endDate')] = $opp->endDate == 0 ? '' : date('d-m-Y', strtotime($opp->endDate));

            if ( $adv->cat == 'Branding' ) {
                $values[$opp->getAttributeLabel('freq_cap')] = $opp->freq_cap;
                $values[$opp->getAttributeLabel('imp_per_day')] = $opp->imp_per_day;
                $values[$opp->getAttributeLabel('imp_total')] = $opp->imp_total;
                $values[$opp->getAttributeLabel('targeting')] = $opp->targeting;
                $values[$opp->getAttributeLabel('sizes')] = $opp->sizes;
                $values[$opp->getAttributeLabel('channel')] = $opp->channel;
                $values[$opp->getAttributeLabel('channel_description')] = $opp->channel_description;
            }

			$this->printTable($pdf, $values);
			$pdf->Ln();
            $i++;
		}

		// Print terms and signature in a new page
		$this->addPage();
		$this->printTerms($pdf);
		$pdf->Ln();
		$pdf->Ln();
		$this->printSignature($pdf);
    }

    protected function initDocInfo()
    {
    	$pdf = $this->getPdf();
    	$pdf->SetTitle('KickAds');
        $pdf->SetSubject('IO');
        // $pdf->SetKeywords('x, y, z');
    }

    protected function initHeader()
    {
        $pdf = $this->getPdf();
        $pdf->setHeaderData( Yii::getPathOfAlias('webroot') . '/themes/bootstrap/img/pdf-header.png', 190, '', '', array(), array(255, 255, 255));
    }

    protected function initFooter()
    {
        $this->getPdf()->setPrintFooter(false);
    }

    protected function initMargins()
    {
        $pdf = $this->getPdf();
        $pdf->SetMargins(10, 35, 10);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(38);

        $pdf->SetAutoPageBreak(TRUE, 38);
    }

    /**
     * Print company information to pdf
     * @param  $pdf TCPDF object
     */
    private function printCompanyInfo($pdf, $io)
    {
    	$pdf->Cell(90, 7, 'KICKADS', 0, 0, 'L', false);

    	$pdf->SetFillColor(155, 187, 89);
        $pdf->SetTextColor(255);
    	$pdf->Cell(90, 7, 'MOBILE ADVERTISER ORDER #' . $io->id, 0, 1, 'L', true);

    	$pdf->SetTextColor(0);
    	$pdf->Cell(90, 7, 'Castillo 1366 - Edificio 2', 0, 0, 'L', false);
    	$pdf->Cell(90, 7, 'DATE: ' . date('d-m-Y', time()), 0, 1, 'L', false);
    	$pdf->Cell(90, 7, 'BUENOS AIRES - ARGENTINA', 0, 0, 'L', false);
    	$pdf->Cell(90, 7, 'ORDER NUMBER: #' . $io->id, 0, 1, 'L', false);
    	$pdf->Cell(90, 7, 'FINANCE@KICKADS.MOBI', 0, 0, 'L', false);
    	$pdf->Cell(90, 7, 'KICKADS ADVERTISING NETWORK - ORDER', 0, 1, 'L', false);
    }

    /**
     * Print a title to pdf
     * @param  $pdf   TCPDF object
     * @param  $title string to display
     */
    private function printTitle($pdf, $title)
    {
    	$pdf->SetFillColor(155, 187, 89);
        $pdf->SetTextColor(255);
        $pdf->Cell(0, 7, strtoupper($title), 0, 1, 'L', true);
    }
    
    /**
     * Print table to pdf
     * @param  $pdf  TCPDF object
     * @param  $info Associative array with values
     */
    private function printTable($pdf, $info)
    {
        $even = true;

		foreach ($info as $key => $value) {
			$pdf->SetFillColor(155, 187, 89);
        	$pdf->SetTextColor(255);
            // $pdf->Cell(60, 7, strtoupper($key), 1, 0, 'L', true);
        	$pdf->MultiCell(60, 7, strtoupper($key), 1, 'L', true, 0);

        	if ($even) {
        		$pdf->SetFillColor(205, 221, 172);
        	} else {
        		$pdf->SetFillColor(230, 238, 213);
        	}
        	$even = ! $even;

        	$pdf->SetTextColor(0);
            // $pdf->Cell(0, 7, strtoupper($value), 1, 1, 'L', true);
        	$pdf->MultiCell(0, 7, strtoupper($value), 1, 'L', true, 1);

		}
    }

    /**
     * Print Terms and Condictions to pdf
     * @param  $pdf TCPDF object
     */
	private function printTerms($pdf)
	{
		$pdf->SetTextColor(0);
		$pdf->Write(0, $this->terms, 0, false, 'J', true);
	}

	/**
     * Print signature section to pdf
     * @param  $pdf TCPDF object
     */
    private function printSignature($pdf)
    {
		$pdf->SetTextColor(0);
		$pdf->Cell(80, 7, 'Legal Representative', 0, 0, 'C', false);
		$pdf->Cell(100, 7, 'Customer Legal Representative', 0, 1, 'C', false);
		$pdf->Ln();
		$pdf->Cell(80, 7, '...................................', 0, 0, 'C', false);
		$pdf->Cell(100, 7, '...................................................', 0, 0, 'C', false);
    }

    /**
     * Set general configuration for pdf
     * @param  $pdf TCPDF object
     */
    private function setConfig($pdf)
    {
    	$pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(0.5);
        $pdf->SetFont('');
    }

    private $terms = "Payment terms: Payment net 30 days from invoicing date.
		
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