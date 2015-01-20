<?php

class PDFInsertionOrder extends PDF
{

	public function getPdfName()
    {
    	return 'IO-' . $this->getDataItem('io')->id . '-KickAds.pdf';
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
		$values[$io->getAttributeLabel('commercial_name')] = $io->commercial_name;
		$values[$io->getAttributeLabel('tax_id')]          = $io->tax_id;
		$values[$io->getAttributeLabel('address')]         = $io->address;
		$values[$io->getAttributeLabel('state')]           = $io->state;
		$values[$io->getAttributeLabel('zip_code')]        = $io->zip_code;
		$values[$io->getAttributeLabel('phone')]           = $io->phone;
		$values[$io->getAttributeLabel('contact_com')]     = $io->contact_com;
		$values[$io->getAttributeLabel('email_adm')]       = $io->email_adm;
		$values[$io->getAttributeLabel('contact_adm')]     = $io->contact_adm;
		$values[$io->getAttributeLabel('currency')]        = $io->currency;
		$values[$io->getAttributeLabel('ret')]             = $io->ret;
		$this->printTitle($pdf, 'Io');
		$this->printTable($pdf, $values);
		$pdf->Ln();

		// Print Opportunities section
		$opp_ids = $this->getDataItem('opportunities');
		$pdf->Ln();
		unset($values);
        $i = 1;
		foreach ($opp_ids as $opp_id) {
			$opp = Opportunities::model()->findByPk($opp_id);
			$this->printTitle($pdf, 'Campaign #' . $i);
			$values[$opp->getAttributeLabel('country_id')]  = $opp->country ? $opp->country->name : '';
			$values[$opp->getAttributeLabel('carriers_id')] = $opp->carriers ? $opp->carriers->mobile_brand : '';
			$values[$opp->getAttributeLabel('rate')]        = $opp->rate;
			$values[$opp->getAttributeLabel('model_adv')]   = $opp->model_adv;
			$values[$opp->getAttributeLabel('product')]     = $opp->product;
			$values[$opp->getAttributeLabel('comment')]     = $opp->comment;
			$values[$opp->getAttributeLabel('wifi')]        = $opp->wifi ? 'Habilitado' : 'Inhabilitado';
			$values[$opp->getAttributeLabel('budget')]      = $opp->budget;
			$values[$opp->getAttributeLabel('startDate')]   = $opp->startDate == 0 ? '' : date('d-m-Y', strtotime($opp->startDate));
			$values[$opp->getAttributeLabel('endDate')]     = $opp->endDate == 0 ? '' : date('d-m-Y', strtotime($opp->endDate));

            if ( $adv->cat == 'Branding' ) {
				$values[$opp->getAttributeLabel('freq_cap')]            = $opp->freq_cap;
				$values[$opp->getAttributeLabel('imp_per_day')]         = $opp->imp_per_day;
				$values[$opp->getAttributeLabel('imp_total')]           = $opp->imp_total;
				$values[$opp->getAttributeLabel('targeting')]           = $opp->targeting;
				$values[$opp->getAttributeLabel('sizes')]               = $opp->sizes;
				$values[$opp->getAttributeLabel('channel')]             = $opp->channel;
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
		$this->printSignature($pdf,$io->commercial_name);
    }
}