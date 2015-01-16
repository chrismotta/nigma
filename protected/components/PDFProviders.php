<?php

class PDFProviders extends PDF
{

	public function getPdfName()
    {
    	return 'Provider-' . $this->getDataItem('provider')->id . '-KickAds.pdf';
    }

    /**
     * Print company information to pdf
     * @param  $pdf TCPDF object
     */
    private function printCompanyInfo($pdf, $provider)
    {
        $pdf->Cell(90, 7, 'KICKADS', 0, 0, 'L', false);

        $pdf->SetFillColor(155, 187, 89);
        $pdf->SetTextColor(255);
        $pdf->Cell(90, 7, 'MOBILE PROVIDER #' . $provider->id, 0, 1, 'L', true);

        $pdf->SetTextColor(0);
        $pdf->Cell(90, 7, 'Castillo 1366 - Edificio 2', 0, 0, 'L', false);
        $pdf->Cell(90, 7, 'DATE: ' . date('d-m-Y', time()), 0, 1, 'L', false);
        $pdf->Cell(90, 7, 'BUENOS AIRES - ARGENTINA', 0, 0, 'L', false);
        $pdf->Cell(90, 7, 'PROVIDER ID: #' . $provider->id, 0, 1, 'L', false);
        $pdf->Cell(90, 7, 'FINANCE@KICKADS.MOBI', 0, 0, 'L', false);
        $pdf->Cell(90, 7, 'KICKADS PROVIDER - ORDER', 0, 1, 'L', false);
    }

	public function renderPdf()
    {
    	$this->addPage();
        $pdf = $this->getPdf();	// get TCPDF instance

        $this->setConfig($pdf); // set format configuration

        // Print presentation
		$provider  = $this->getDataItem('provider');
        $this->printCompanyInfo($pdf, $provider);
        $pdf->Ln();

        // Print provider section
		unset($values);
        $values[$provider->getAttributeLabel('id')]                  = $provider->id;
        $values[$provider->getAttributeLabel('prefix')]              = $provider->prefix;
        $values[$provider->getAttributeLabel('name')]                = $provider->name;
        $values[$provider->getAttributeLabel('currency')]            = $provider->currency;
        $values[$provider->getAttributeLabel('country_id')]          = $provider->country ? $provider->country->name : '';
        $values[$provider->getAttributeLabel('model')]               = $provider->model;
        $values[$provider->getAttributeLabel('net_payment')]         = $provider->net_payment;
        $values[$provider->getAttributeLabel('deal')]                = $provider->deal;
        $values[$provider->getAttributeLabel('post_payment_amount')] = $provider->post_payment_amount;
        $values[$provider->getAttributeLabel('start_date')]          = $provider->start_date == 0 ? '' : date('d-m-Y', strtotime($provider->start_date));
        $values[$provider->getAttributeLabel('end_date')]            = $provider->end_date == 0 ? '' : date('d-m-Y', strtotime($provider->start_date));
        $values[$provider->getAttributeLabel('daily_cap')]           = $provider->daily_cap;
        $values[$provider->getAttributeLabel('sizes')]               = $provider->sizes;
        $values[$provider->getAttributeLabel('has_s2s')]             = $provider->has_s2s ? "Yes" : "No";
        $values[$provider->getAttributeLabel('has_token')]           = $provider->has_token ? "Yes" : "No";
        $values[$provider->getAttributeLabel('callback')]            = $provider->callback;
        $values[$provider->getAttributeLabel('placeholder')]         = $provider->placeholder;
        $values[$provider->getAttributeLabel('commercial_name')]     = $provider->commercial_name;
        $values[$provider->getAttributeLabel('state')]               = $provider->state;
        $values[$provider->getAttributeLabel('zip_code')]            = $provider->zip_code;
        $values[$provider->getAttributeLabel('contact_com')]         = $provider->contact_com;
        $values[$provider->getAttributeLabel('email_com')]           = $provider->email_com;
        $values[$provider->getAttributeLabel('contact_adm')]         = $provider->contact_adm;
        $values[$provider->getAttributeLabel('email_adm')]           = $provider->email_adm;
        $values[$provider->getAttributeLabel('address')]             = $provider->address;
        $values[$provider->getAttributeLabel('tax_id')]              = $provider->tax_id;
        $values[$provider->getAttributeLabel('entity')]              = $provider->entity;
        $this->printTitle($pdf, 'Provider');
        $this->printTable($pdf, $values);
        $pdf->Ln();

		// Print terms and signature in a new page
		$this->addPage();
		$this->printTerms($pdf);
		$pdf->Ln();
		$pdf->Ln();
		$this->printSignature($pdf);
    }
}