<?php

class PDFInsertionOrder extends PDF
{

	public function getPdfName()
    {
		$io  = $this->getDataItem('io');
    	return 'TML-' . $io->financeEntities->advertisers->name . '-' . date("m_d_Y", strtotime($io->date)) . '-IO_' . $io->id . '.pdf';
    }

    protected function initHeader()
    {
        $pdf = $this->getPdf();
        $pdf->setHeaderData( Yii::getPathOfAlias('webroot') . '/themes/tml/img/logo_tml.gif', 50, '', '', array(), array(255, 255, 255));
    }


    protected function initMargins()
    {
        $pdf = $this->getPdf();
        $pdf->SetMargins(10, 39, 10);
        $pdf->SetHeaderMargin(8);
        $pdf->SetFooterMargin(10);

        $pdf->SetAutoPageBreak(TRUE, 10);
    }
    /**
     * Print company information to pdf
     * @param  $pdf TCPDF object
     */
    private function printCompanyInfo($pdf, $io)
    {
		$pdf->SetFillColor(8, 150, 153);
        $pdf->SetTextColor(255);
    	$pdf->Cell(190, 7, 'TML Media LLC', 0, 1, 'L', true);
		// $this->printSpace($pdf, 5);
		// $pdf->SetFillColor(8, 150, 153);
		// $pdf->SetTextColor(255);
		// $pdf->Cell(90, 7, 'MOBILE ADVERTISER ORDER #' . $io->id, 0, 1, 'L', true);
    	
    	$pdf->SetTextColor(0);
    	$pdf->Ln(2);
    	
    	$pdf->Cell(100, 5, '30 East Pine Street, Georgetown', 0, 0, 'L', false);
    	$pdf->Cell(90, 5, 'Date: ' . date('F j, Y', strtotime($io->date)), 0, 1, 'L', false);
    	$pdf->Cell(100, 5, 'Sussex County, Delaware 19947, United States', 0, 0, 'L', false);
    	$pdf->Cell(90, 5, 'Insertion Order Number: #' . $io->id, 0, 1, 'L', false);
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
		$values[$io->financeEntities->getAttributeLabel('commercial_name')] = $io->financeEntities->commercial_name;
		$values[$io->financeEntities->getAttributeLabel('tax_id')]          = $io->financeEntities->tax_id;
		$values[$io->financeEntities->getAttributeLabel('address')]         = $io->financeEntities->address;
		$values[$io->financeEntities->getAttributeLabel('state')]           = $io->financeEntities->state;
		$values[$io->financeEntities->getAttributeLabel('zip_code')]        = $io->financeEntities->zip_code;
		$values[$io->financeEntities->getAttributeLabel('country')]         = $io->financeEntities->country->name;
		$values[$io->financeEntities->getAttributeLabel('phone')]           = $io->financeEntities->phone;
		$values[$io->financeEntities->getAttributeLabel('contact_com')]     = $io->financeEntities->contact_com;
		$values[$io->financeEntities->getAttributeLabel('email_com')]       = $io->financeEntities->email_com;
		$values[$io->financeEntities->getAttributeLabel('contact_adm')]     = $io->financeEntities->contact_adm;
		$values[$io->financeEntities->getAttributeLabel('email_adm')]       = $io->financeEntities->email_adm;
		$values[$io->financeEntities->getAttributeLabel('currency')]        = $io->financeEntities->currency;
		$values[$io->financeEntities->getAttributeLabel('ret')]             = $io->financeEntities->ret;
		if ($io->budget != 0) $values[$io->getAttributeLabel('budget')]     = $io->budget;
		$this->printTitle($pdf, 'Insertion Order');
		$this->printTable($pdf, $values);
		$pdf->Ln();

		// Print Opportunities section
		$opp_ids = $this->getDataItem('opportunities');
		//$pdf->Ln();
        $i = 1;
		foreach ($opp_ids as $opp_id) {
			unset($values);
			$opp = Opportunities::model()->findByPk($opp_id);
			$this->printTitle($pdf, 'Campaign #' . $i);
			if ($opp->regions->country_id) $values[$opp->getAttributeLabel('country_id')]  = $opp->regions->country->name;
			if ($opp->carriers)            $values[$opp->getAttributeLabel('carriers_id')] = $opp->carriers->mobile_brand;
			if ($opp->rate)                $values[$opp->getAttributeLabel('rate')]        = $opp->rate;
			if ($opp->model_adv)           $values[$opp->getAttributeLabel('model_adv')]   = $opp->model_adv;
			if ($opp->product)             $values[$opp->getAttributeLabel('product')]     = $opp->product;
			if ($opp->comment)             $values[$opp->getAttributeLabel('comment')]     = $opp->comment;
			$values[$opp->getAttributeLabel('wifi')]                                       = $opp->wifi;
			if ($opp->budget != 0)         $values[$opp->getAttributeLabel('budget')]      = $opp->budget;
			if ($opp->startDate != 0)      $values[$opp->getAttributeLabel('startDate')]   = $opp->startDate == 0 ? '' : date('d-m-Y', strtotime($opp->startDate));
			if ($opp->endDate != 0)        $values[$opp->getAttributeLabel('endDate')]     = $opp->endDate == 0 ? '' : date('d-m-Y', strtotime($opp->endDate));

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
		$company='TML Media ';//.$io->financeEntities->entity[0];

		// $this->payment = "Payment Terms: ".$io->financeEntities->net_payment;
		// if(is_numeric($io->financeEntities->net_payment)) $this->payment .= " days.";

		$vasTerms = "Terms and conditions:

Advertiser accepts and acknowledges the terms and conditions from TML Media LLC by signing this “Insertion Order“. TML Media LLC and the Advertiser have the right to cancel the campaign, any time, providing the other party 48-labor hours notice. TML Media LLC will invoice based on records from proprietary technology, plus extra validation with Advertiser if needed.

Advertiser declares that it’s authorized and empowered enough to sign this agreement and have received a copy. The Parties agree that any work orders, proposals and insertion order are subject to modifications or amendments to the sole discretion of TML Media LLC. The terms of this document override any other terms and will be taken as valid in case of conflict. 

";

		// enable with prepayment
		//if($prepayment) $vasTerms.= "Advertiser will be invoiced the same day the IO is fully consumed or at the end of month, whichever comes first. The Advertiser has [30] days to pay the invoice from the invoicing date.
 		
 		//";
		$vasTerms.= "Advertiser shall immediately notify TML Media LLC of any suspected fraudulent or illegal activity, and shall submit any “action” disputes no later than 5 days after suspected fraudulent “action” has been registered. For all “action” disputes, Advertiser shall provide valid and reasonable evidence supporting the basis for such dispute, including but not limited to contact information, timestamp, IP address, proof of multiple uses of the fraudulent information, among others.

As part of this agreement with TML Media LLC, the Advertiser agrees to implement a server-to-server conversion tracking that will enable TML Media LLC to independently validate any conversions that are generated. TML Media LLC will assist the Advertiser with the technical requirements for the implementation, which will allow TML Media LLC to manage performance campaigns more effectively. 

Taxes: Except it's expressly stated in an IO, all taxes and/or deductions on TML Media LLC's net income, all value added, sales, and other taxes arising out of or relating to these Terms, shall be the responsibility of Advertiser.

";

        $defaultTerms = "Terms and conditions:
Section 1. Incorporation Terms. These Terms and Conditions (together with any Insertion Orders hereunder, the “Agreement”), dated as of the date of the latest signature below (the “Effective Date”), is entered into by and between TML Media, LLC, a Delaware limited liability partnership (“Media Company”), and ".$io->financeEntities->commercial_name." (“Advertiser”), a company organized in ".$io->financeEntities->country->name." , (each a “Party,” and, collectively, the “Parties”). The IAB/AAAA Standard Terms and Conditions for Internet Advertising Media Buys One Year or Less Version 3.0 (“IAB Terms”), located at http://www.iab.net/media/file/IAB_4As-tsandcs-FINAL.pdf, are hereby incorporated by reference, as modified herein. All capitalized terms not defined herein shall have the meanings set forth in the IAB Terms. 
1.1 Construction. Any conflict or inconsistency among terms will be resolved in the following order of precedence: (i) the Insertion Order (the “IO”), (ii) Terms and Conditions, and (iii) IAB Terms. (Items (i), (ii) and (iii) are referred to collectively as the “Agreement”). 
1.2 Party Language. Any reference to “Agency” or “Advertiser” in the IAB Terms applies to Advertiser, as specified in the applicable Insertion Order, and any reference to Media Company in the IAB Terms applies TML Media, LLC. For sake of clarity, the party named as the Advertiser in any IO is assuming the responsibilities, rights, and obligations of both the “Advertiser” and the “Agency” under the IAB Terms. 

Section 2. Amendments
2.1 Replace Definitions. Under the “Definitions” Section in the IAB terms, the definitions of the terms “Media Company Properties” and “Network Properties” are, respectively, stricken and replaced with the following: 
“Media Company Properties” means any online property, that Media Company owns or controls, on which an Ad may be displayed. 
“Network Properties” means any online property on which Media Company may cause or enable an Ad to be displayed because of a contractual relationship with a third party. 
2.2 Payment Terms. Section III b. in the IAB terms is replaced with “Unless specified otherwise in an IO, Advertiser shall pay Media Company within 30 days from the receipt of invoice. Any late payments shall be subject to an interest rate of 2% per month, and if Advertiser is more than 60 days late, it shall pay any and all costs related to Media Company trying to collect payment, including, but not limited to, all collection agency costs and any and all costs related to litigation, including attorneys fees.” 
2.3 Reporting. Section IV.c of the IAB Terms is stricken and replaced with the following: 
“Invoicing and Disputes. Unless otherwise specified in the IO, all invoices will be based on Media Company’s tracking and reporting systems. Advertiser shall have five (5) days after receipt of an invoice to present reasonable evidence that the invoice, or the reporting underlying the invoice, is inaccurate or incomplete. If Advertiser fails to notify Media Company of a dispute and present such evidence during the five (5) day period, any dispute shall be deemed waived. Media Company shall evaluate any evidence presented by Advertiser in good faith. All determinations made by Media Company in good faith regarding reporting or amounts invoiced shall be final, binding and determinative.” 
2.4 Some Sections Stricken. The following sections are stricken from the IAB Terms: Section II. d., Section III. c., Section X. a. (ii) and (iii). 
2.5 Law and Jurisdiction. In the blank spaces in Section XIV. d. of the IAB Terms , insert ”State Of California” and “San Francisco, California” respectively. 
2.6 Indemnities. Section X. a. (i) of the IAB Terms is stricken and replaced with: “Media Company’s alleged breach of Section XII, or” 
2.7 Addition to Limitation of Liability. The following is appended after the last sentence in Section XI of the IAB Terms: “Furthermore, Media Company’s aggregate liability under this agreement for any claim or series of claims is limited to the lesser of (i) the amount paid by Advertiser or Agency to Media Company during the 6-month period prior to the date the liability first arose, or (ii) USD 5,000.”
2.8 The second and third sentences of Section XIV.d. in the IAB terms are hereby deleted in their entirety and replaced with the following: “All IOs will be governed by the laws of the State of California, without reference to conflict of laws principles. Media Company and Advertiser agree that any claims, legal proceedings, or litigation arising in connection with the IO (including these amended Terms) will be brought solely in the federal or state courts located in San Francisco, California, and the parties consent to the jurisdiction of such courts.”

Section 3. Additional Provisions 
3.1 Inventory. Advertiser acknowledges and agrees that Media Company might not always have control over the content surrounding the placement of Ads, and that Media Company may not have a contractual relationship with the author, distributor, or owner of the content surrounding Ads. Notwithstanding any provision of these Terms and Conditions or the IAB Terms to the contrary, Media Company hereby disclaims all damages, liabilities, warranties, and representations and has no obligation to indemnify Advertiser or Agency for the placement of Ads on, in or near online content not directly controlled by Media Company. 
3.2 Disclaimer of Warranties. EXCEPT AS EXPRESSLY PROVIDED OTHERWISE IN THIS AGREEMENT, ALL CONTENT, PRODUCTS AND SERVICES PROVIDED BY MEDIA COMPANY ARE PROVIDED ON AN “AS IS” BASIS. MEDIA COMPANY DOES NOT MAKE, AND HEREBY DISCLAIMS, ANY AND ALL OTHER EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, NON- INFRINGEMENT, COMPLIANCE WITH LAW, AND UNINTERRUPTED, ERROR-FREE, OR SECURE OPERATION. 
3.3 Taxes. Except as expressly stated in an IO and for taxes on Media Company’s net income, all value added, sales, and other taxes arising out of or relating to these Terms shall be the responsibility of Advertiser.
3.4 Liquidated Damages. Advertiser recognizes that a breach of any of these conditions could result in immediate, extraordinary and irreparable damage to Media Company and its relationships with its advertisers, publishers and other third parties, and that damages may be difficult to measure. Upon a determination by Media Company, in its sole discretion, that Advertiser has violated any of the foregoing conditions, Advertiser agrees that Media Company may, in addition to other legal remedies, assess liquidated damages of up to $1,000.00 USD per occurrence of each such violation, and that such liquidated damages are reasonable. 
3.5 Ad Placement and Positioning. Advertiser agrees and acknowledges that the Media Company shall determine in its reasonable discretion the ad placement and positioning in accordance with rates set forth on the Insertion Order for each campaign. Unless previously stated on the Insertion Order.
3.6 IOs and Amendments to IOs. Advertiser agrees and acknowledges that every request for a new campaign must be submitted on an executed IO with the Media Company.
3.7 If Media Company and/or Advertiser utilizes an electronic signature (“E-Signature”), it agrees that its E-Signature is the legal equivalent of its manual signature on all contracts and shall be construed as an acceptance of the terms.  In the event that an E-Signature does not result in a signature, any automated mechanism that is used by the Advertiser in order to confirm the acceptance of a contract, Insertion Order, or any amendment will be the legal equivalent of an original signature and will be construed as an acceptance of the terms.  Advertiser also agrees that no certification authority is necessary to validate its E-Signature and that the lack of such certification or third party verification will not in any way affect the enforceability of its E-Signature or any resulting contract between Advertiser and Media Company.  Media Company and Advertiser further agree that each use of its E-Signature constitutes its agreement to be bound by the terms and conditions of the Insertion Orders as they exist on the date of the E-Signature.
        ";

        switch ($io->financeEntities->advertisers->cat) {
        	case 'VAS':
        	case 'App Owners':
				$this->terms = $vasTerms;
        		break;
        	default:
				$this->terms = $defaultTerms;
        		break;
        }

		$this->printPayment($pdf, $io->financeEntities->net_payment);
		$pdf->Ln();
		$this->printTerms($pdf);
		$pdf->Ln();
		$pdf->Ln();
		$this->printSignature($pdf, $io->financeEntities->commercial_name, 'TML Media LLC', 'Matt');
    }
}