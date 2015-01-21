<?php

class PDFProviders extends PDF
{

	public function getPdfName()
    {
    	return 'Provider-' . $this->getDataItem('provider')->id . '-KickAds.pdf';
    }

    protected function initHeader()
    {
        $pdf = $this->getPdf();
        $pdf->setHeaderData( Yii::getPathOfAlias('webroot') . '/themes/bootstrap/img/pdf-header.png', 190, '', '', array(), array(255, 255, 255));
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
        // $values[$provider->getAttributeLabel('id')]                  = $provider->id;
        // $values[$provider->getAttributeLabel('prefix')]              = $provider->prefix;
        // $values[$provider->getAttributeLabel('name')]                = $provider->name;
        // $values[$provider->getAttributeLabel('currency')]            = $provider->currency;
        $values[$provider->getAttributeLabel('commercial_name')]     = $provider->commercial_name;
        $values[$provider->getAttributeLabel('tax_id')]              = $provider->tax_id;
        $values[$provider->getAttributeLabel('country_id')]          = $provider->country ? $provider->country->name : '';
        $values[$provider->getAttributeLabel('state')]               = $provider->state;
        $values[$provider->getAttributeLabel('zip_code')]            = $provider->zip_code;
        $values[$provider->getAttributeLabel('address')]             = $provider->address;
        $values[$provider->getAttributeLabel('model')]               = $provider->model;
        $values[$provider->getAttributeLabel('net_payment')]         = $provider->net_payment;
        $values[$provider->getAttributeLabel('deal')]                = $provider->deal;
        $values[$provider->getAttributeLabel('post_payment_amount')] = $provider->post_payment_amount;
        $values[$provider->getAttributeLabel('start_date')]          = $provider->start_date == 0 ? '' : date('d-m-Y', strtotime($provider->start_date));
        $values[$provider->getAttributeLabel('end_date')]            = $provider->end_date == 0 ? '' : date('d-m-Y', strtotime($provider->end_date));
        $values[$provider->getAttributeLabel('contact_com')]         = $provider->contact_com;
        $values[$provider->getAttributeLabel('email_com')]           = $provider->email_com;
        $values[$provider->getAttributeLabel('contact_adm')]         = $provider->contact_adm;
        $values[$provider->getAttributeLabel('email_adm')]           = $provider->email_adm;
        // $values[$provider->getAttributeLabel('daily_cap')]           = $provider->daily_cap;
        // $values[$provider->getAttributeLabel('sizes')]               = $provider->sizes;
        // $values[$provider->getAttributeLabel('has_s2s')]             = $provider->has_s2s ? "Yes" : "No";
        // $values[$provider->getAttributeLabel('has_token')]           = $provider->has_token ? "Yes" : "No";
        // $values[$provider->getAttributeLabel('callback')]            = $provider->callback;
        // $values[$provider->getAttributeLabel('placeholder')]         = $provider->placeholder;
        // $values[$provider->getAttributeLabel('entity')]              = $provider->entity;
        $this->printTitle($pdf, 'Provider');
        $this->printTable($pdf, $values);
        $pdf->Ln();

		// Print terms and signature in a new page
		$this->addPage();
        $this->terms = "Dear partner:

Due to the central bank of Argentina introducing strict controls to foreign currency operations we need to ask you for certain details to be included and some others to be excluded off the monthly invoices you send us effective December 2014.
1) Invoice must include the month/period
2) It must not mention Vanega or any other fantasy name tan related to KickAds SRL
3) It must not mention targetting details such as Carrier, Geo, etc
4) It must include a rate per click or subscription (it can be an average), an amount in units, and a total
    For example:
    Mobile Ad Network Invoice November 2014
    Clicks: 100
    Rate: USD 0.5
    Total: USD 50

Please avoid including any further information as even though it may seem useful and informational to our bank and our tax office audits it may cause more controls to be applied and may eventually end up further delaying our exchange currency operations.

Please forward to whoever is responsible of this in your organization.

We of course apologize for any inconvenience this may cause to your admin teams but unfortunately it is beyond our control and we want to keep all operations as smooth as possible quickly. We are researching new possibilities to expedite payments.

Thanks a lot.                                                                                   ";
		$this->printTerms($pdf);
		$pdf->Ln();
		$pdf->Ln();
		$this->printSignature($pdf,$provider->commercial_name);
    }
}