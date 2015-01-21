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
		$company='KICKADS '.$io->entity[0];
		$this->terms= "Payment terms: Payment net 30 days from invoicing date.
		
		Aditional terms:
		If any deduction/tax applies, it must be paid by customer. signing this \"insertion order\" we do accept the terms and conditions from ".$company.". ".$company." and the company has the right to cancel the campaign, any time, providing the other party 24 hours labour days notice. ".$company." will invoice based on records from current systems. 

		Client is invoiced on the date the io is consumed or end of month, whichever comes first. The client has 30 days to pay the invoice from the invoice date. 

		I hereby agree to the terms and conditions. I also declare that i'm authorized and empowered enough to sign this document and i have received a copy. The parties agree that any work orders, proposals and insertion order are subject to modifications or amendments to the sole discretion of the company. In case of conflict between terms and conditions and the terms of this agreement be taken as valid signed terms here.

		Advertiser shall immediately notify ".$company." of any suspected fraudulent or illegal activity, and shall submit any lead disputes no later than 5 days after suspected fraudulent lead has been registered. For all lead disputes, advertiser shall provide valid and reasonable evidence supporting the basis for such dispute, including but not limited to contact information, timestamp, ip address, proof of multiple uses of the same credit card and fraudulent information entered.

		As part of this agreement with ".$company.", the client agrees to implement a server side pixel that will enable ".$company." to independently validate any conversions that are received. ".$company." will assist the client regarding the technical requirements for implementing this pixel, which when validated will allow ".$company." manage cpa campaigns effectively.
        ";
		$this->printTerms($pdf);
		$pdf->Ln();
		$pdf->Ln();
		$this->printSignature($pdf,$io->commercial_name);
    }
}