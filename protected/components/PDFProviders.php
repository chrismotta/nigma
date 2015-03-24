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


    /**
     * Print company information to pdf
     * @param  $pdf TCPDF object
     */
    private function printSpace($pdf,$space)
    {

        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(255);
        $pdf->Cell($space, 7, ' ', 0, 0, 'L', true);
    }
    /**
     * Print company information to pdf
     * @param  $pdf TCPDF object
     */
    private function printCompanyInfo($pdf, $io)
    {
        $title='Company Info';
        $table=array(
            'Company Name'=>$io->name,
            'Legal Name'=>$io->commercial_name,
            'Adress'=>$io->address,
            'Tax Id'=>$io->tax_id,
            );
        $this->printInfo($pdf,$title,$table);
        $title='Company Contact Info';
        $table=array(
            'Complete Name'=>'',
            'Position'=>'',
            'Email'=>'',
            'Phone'=>'',
            'Mobile'=>'',
            'Fax'=>'',
            'Skype'=>'',
            );
        $this->printInfo($pdf,$title,$table);    
    }

    /**
     * Print company information to pdf
     * @param  $pdf TCPDF object
     */
    private function printPaymentInfo($pdf)
    {
        $title='Payment Info';
        $table=array(
            'Beneficiary Name'=>'',
            'Payment Terms'=>'',
            );
        $this->printInfo($pdf,$title,$table);
        $title='Wire Transfer';
        $table=array(
            'Bank Account Info'=>'',
            'ABA'=>'',
            'Swift'=>'',
            );
        $this->printInfo($pdf,$title,$table); 
        $title='PayPal';
        $table=array(
            'ID'=>'',
            );
        $this->printInfo($pdf,$title,$table);    
    }
   
    /**
     * Print company information to pdf
     * @param  $pdf TCPDF object
     */
    private function printSelfCompanyInfo($pdf)
    {
        $title='TML Info';
        $table=array(
            'Company Name'=>'The Media Lab',
            'Legal Name'=>'TML Media LLC',
            'Adress'=>'30 East Pine Street, Georgetown, Sussex County, Delaware 19947',
            'Tax Id'=>'-',
            );
        $this->printInfo($pdf,$title,$table);
        $title='TML Contact Info';
        $table=array(
            'Complete Name'=>'Pedro Drago',
            'Position'=>'Media Manager',
            'Email'=>'pedro@themedialab.co',
            'Phone'=>'54 11 3323 0859',
            'Mobile'=>'54 11 3323 0859',
            'Fax'=>'-',
            'Skype'=>'grago.pe',
            );
        $this->printInfo($pdf,$title,$table);
    }
    protected function initMargins()
    {
        $pdf = $this->getPdf();
        $pdf->SetMargins(10, 35, 10);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(38);

        $pdf->SetAutoPageBreak(TRUE, 38);
    }
	public function renderPdf()
    {
    	$this->addPage();
        $pdf = $this->getPdf();	// get TCPDF instance

        $this->setConfig($pdf); // set format configuration

        // Print presentation
		$provider  = $this->getDataItem('provider');        
        $company='TML Media LLC';

        $this->printSelfCompanyInfo($pdf);
        $pdf->Ln();

        // Print provider section
		unset($values);
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
        if($publisher=Publishers::model()->findByPk($provider->id))
        {
            $values[$publisher->getAttributeLabel('rate')]           = $publisher->rate;
            if($provider->model=='RS')
                $values[$publisher->getAttributeLabel('RS_perc')]           = $publisher->RS_perc;
            
        }
        
        $this->printTitle($pdf, 'Provider');
        $this->printTable($pdf, $values);
        $pdf->Ln();
        if(Networks::model()->findByPk($provider->id))
            $type='network';
        if(Affiliates::model()->findByPk($provider->id))
            $type='affiliate';
        if(Publishers::model()->findByPk($provider->id))
            $type='publisher';
        /*if($type=='affiliate')
        {
            $terms='<p><b>TERMS AND CONDITIONS</b></p>

<p><b>REPORTING:</b> All reported numbers for the purposes of billing, payment and general delivery reporting are based on '.$company.' server reports.</p>

<p><b>CANCELLATION:</b> '.$company.' and Affiliates have the right to cancel the campaign, any time, providing the other party 48 hours labour days notice. </p>';
            $pdf->WriteHTML($terms, true,false,false,false, 'J');
            
        }*/
		// Print terms and signature in a new page
		$this->addPage();
        $this->terms = "Section 1. Incorporation Terms. These Terms and Conditions (together with any Insertion Orders hereunder, the “Agreement”), dated as of the date of the latest signature below (the “Effective Date”), is entered into by and between TML Media, LLC, a Delaware limited liability partnership (“Media Company”), and                                           (Name of the Advertiser), a company organized in the                                             (Country of the Advertiser), (“Advertiser”) (each a “Party,” and, collectively, the “Parties”). The IAB/AAAA Standard Terms and Conditions for Internet Advertising Media Buys One Year or Less Version 3.0 (“IAB Terms”), located at http://www.iab.net/media/file/IAB_4As-tsandcs-FINAL.pdf, are hereby incorporated by reference, as modified herein. All capitalized terms not defined herein shall have the meanings set forth in the IAB Terms. 
1.1 Construction. Any conflict or inconsistency among terms will be resolved in the following order of precedence: (i) the Insertion Order (the “IO”), (ii) Terms and Conditions, and (iii) IAB Terms. (Items (i), (ii) and (iii) are referred to collectively as the “Agreement”). 
1.2 Party Language. Any reference to “Agency” or “Advertiser” in the IAB Terms applies to Advertiser, as specified in the applicable Insertion Order, and any reference to Media Company in the IAB Terms applies TML Media, LLC. For sake of clarity, the party named as the Advertiser in any IO is assuming the responsibilities, rights, and obligations of both the “Advertiser” and the “Agency” under the IAB Terms. 

Section 2. Amendments
2.1 Additional Definitions. Under the “Definitions” Section in the IAB Terms, append the following definitions: 
“Revenue Share” or “Rev Share” means a percentage, as specified in an IO, of all gross revenue that Advertiser earns as directly or indirectly attributable to an Internet user clicking, viewing or otherwise interacting with an Ad. 
2.2 Replace Definitions. Under the “Definitions” Section in the IAB terms, the definitions of the terms “Media Company Properties” and “Network Properties” are, respectively, stricken and replaced with the following: 
“Media Company Properties” means any online property, that Media Company owns or controls, on which an Ad may be displayed. 
“Network Properties” means any online property on which Media Company may cause or enable an Ad to be displayed because of a contractual relationship with a third party. 
2.3 Payment Terms. Section III b. is replaced with “Unless specified otherwise in an IO, Advertiser shall pay Media Company within 30 days from the receipt of invoice. Any late payments shall be subject to an interest rate of 2% per month, and if Advertiser is more than 60 days late, it shall pay any and all costs related to Media Company trying to collect payment, including, but not limited to, all collection agency costs and any and all costs related to litigation, including attorneys fees.” 
2.4 Reporting. Section IV.c of the IAB Terms is stricken and replaced with the following: 
“Invoicing and Disputes. Unless otherwise specified in the IO, all invoices will be based on Media Company’s tracking and reporting systems. Advertiser shall have five (5) days after receipt of an invoice to present reasonable evidence that the invoice, or the reporting underlying the invoice, is inaccurate or incomplete. If Advertiser fails to notify Media Company of a dispute and present such evidence during the five (5) day period, any dispute shall be deemed waived. Media Company shall evaluate any evidence presented by Advertiser in good faith. All determinations made by Media Company in good faith regarding reporting or amounts invoiced shall be final, binding and determinative.” 
2.5 Some Sections Stricken. The following sections are stricken from the IAB Terms: Section II. d., Section III. c., Section X. a. (ii) and (iii). 
2.6 Law and Jurisdiction. In the blank spaces in Section XIV. d. of the IAB Terms , insert “Delaware” and “Ver que poner aca,” respectively. 
2.7 Indemnities. Section X. a. (i) of the IAB Terms is stricken and replaced with: “Media Company’s alleged breach of Section XII, or” 
2.8 Addition to Limitation of Liability. The following is appended after the last sentence in Section XI of the IAB Terms: “Furthermore, Media Company’s aggregate liability under this agreement for any claim or series of claims is limited to the lesser of (i) the amount paid by Advertiser or Agency to Media Company during the 6-month period prior to the date the liability first arose, or (ii) USD 7,000.”
2.9 The second and third sentences of Section XIV.d. are hereby deleted in their entirety and replaced with the following: “All IOs will be governed by the laws of the State of California, without reference to conflict of laws principles. Media Company and Advertiser agree that any claims, legal proceedings, or litigation arising in connection with the IO (including these amended Terms) will be brought solely in the federal or state courts located in San Francisco, California, and the parties consent to the jurisdiction of such courts.”

Section 3. Additional Provisions 
3.1 Inventory. Advertiser acknowledges and agrees that Media Company might not always have control over the content surrounding the placement of Ads, and that Media Company may not have a contractual relationship with the author, distributor, or owner of the content surrounding Ads. Notwithstanding any provision of these Terms and Conditions or the IAB Terms to the contrary, Media Company hereby disclaims all damages, liabilities, warranties, and representations and has no obligation to indemnify Advertiser or Agency for the placement of Ads on, in or near online content not directly controlled by Media Company. 
3.2 Disclaimer of Warranties. EXCEPT AS EXPRESSLY PROVIDED OTHERWISE IN THIS AGREEMENT, ALL CONTENT, PRODUCTS AND SERVICES PROVIDED BY MEDIA COMPANY ARE PROVIDED ON AN “AS IS” BASIS. MEDIA COMPANY DOES NOT MAKE, AND HEREBY DISCLAIMS, ANY AND ALL OTHER EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, NON- INFRINGEMENT, COMPLIANCE WITH LAW, AND UNINTERRUPTED, ERROR-FREE, OR SECURE OPERATION. 
3.3 Taxes. Except as expressly stated in an IO and for taxes on Media Company’s net income, all value added, sales, and other taxes arising out of or relating to these Terms shall be the responsibility of Advertiser.
3.4 Liquidated Damages. Advertiser recognizes that a breach of any of these conditions could result in immediate, extraordinary and irreparable damage to Media Company and its relationships with its advertisers, publishers and other third parties, and that damages may be difficult to measure. Upon a determination by Media Company, in its sole discretion, that Advertiser has violated any of the foregoing conditions, Advertiser agrees that Media Company may, in addition to other legal remedies, assess liquidated damages of up to $1,000.00 USD per occurrence of each such violation, and that such liquidated damages are reasonable. 
3.5 Ad Placement and Positioning. Advertiser agrees and acknowledges that the Media Company shall determine in its reasonable discretion the ad placement and positioning in accordance with rates set forth on the Insertion Order for each campaign. Unless previously stated on the Insertion Order.
3.6 IOs and Amendments to IOs. Advertiser agrees and acknowledges that every request for a new campaign must be submitted on an executed IO with the Media Company.
                                                                                 ";
		$this->printTerms($pdf);
		$pdf->Ln();
		$pdf->Ln();
		$this->printSignature($pdf,$provider->commercial_name);
    }
}