<?php

class PDFProviders extends PDF
{

	public function getPdfName()
    {
    	return 'Provider-' . $this->getDataItem('provider')->id . '-TML.pdf';
    }

    protected function initHeader()
    {
        $pdf = $this->getPdf();
        $pdf->setHeaderData( Yii::getPathOfAlias('webroot') . '/themes/tml/img/logo_tml.gif', 50, '', '', array(), array(255, 255, 255));
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
        $this->printCompanyInfo($pdf,$provider);
        $pdf->Ln();
        $this->printPaymentInfo($pdf);
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
        $this->terms='Terms and conditions:
        ';
        $pdf->Ln();
        if(Networks::model()->findByPk($provider->id))
            $type='network';
        if(Affiliates::model()->findByPk($provider->id))
            $type='affiliate';
        if(Publishers::model()->findByPk($provider->id))
            $type='publisher';
        if($type=='publisher')
        {
            $this->terms.='This insertion order is governed by the AAAA/IAB Standard Terms and Conditions for Internet Advertising for Media Buys One Year or Less, Version 3.0 currently available at http://www.iab.net/media/file/IAB_4As-tsandcs-FINAL.pdf (the “4A Terms”), as hereby amended. In the event of conflict or inconsistency among terms will be resolved in the following order of precedence: (i) the Insertion Order (the “IO”), (ii) Terms and Conditions, and (iii) IAB Terms. (Items (i), (ii) and (iii) are referred to collectively as the “Agreement”). Capitalized terms used but not defined herein have the meanings ascribed to them in the 4A Terms. Any 

The 4A Terms are hereby amended as follows: 
    The Media Lab LLC
1.  DEFINITIONS 
The definition of the term “Agency” is amended to add the following: “Agency means TML Media LLC. acting as the Ad provider regardless whether the Agency sources the Ads directly or through the use of an ad sales ad networks or any other demand side platforms”. 
The definition of the term “Advertiser” is entirely replaced with the following: “Advertiser means a person or entity provider that has contracted with the Agency to purchase or to have Agency purchase the inventory for the purpose of serving the Ads on the Media Company’s Properties.  
The definition of the term “Third Party” is amended to remove the term “Advertiser” from the definition. 

2.  I. PURCHASE ORDERS AND INVENTORY AVAILABILITY 
Section I.a. is amended to include the following: “Advertiser shall own and retain all right, title and interest in all Advertising Materials provided by Advertiser to Media Company.” 
 
3.  II. AD PLACEMENT AND POSITIONING 
Section II.d. is amended to include the following:
“Media Company will comply with the following additional Editorial Adjacency Guidelines :
-   Content or material promoting or containing links related to pornographic, adult sexual, obscene or that contain sexually explicit images or activity;
-   Content or materials promoting or containing links related to gambling, online casinos, tobacco, alcohol and/ or weapons;
-   Content or materials promoting or containing links related to illegal activities including but not limited to illegal drugs, terrorism, criminal activities;
-   Content or materials promoting or containing links related with graphic or explicit violence or is discriminatory, hate, offensive, defamatory or profane;
-   Content or materials promoting or containing links that is disparaging to The Media Lab LLC. or any of its Publishers or Ads Vendors:
-   Content or materials promoting or containing links that are libelous, harassing, abusive, fraudulent, unlawful;
-   Content that has links or promotes activities that are understood or seen as Internet abuse including but not limited to, use of spyware, corrupted files viruses, or other materials that are intended to or damage or render inoperable software of hardware;
-   Content or materials promoting or containing links that violates or infringes upon any third party intellectual property rights or any other third party rights.
-   Mobile Advertisement Style & Grammar Guidelines: a) Proper grammar conventions should be followed. b) Use of common text message abbreviations is permitted.”

4.  III. PAYMENT AND PAYMENT LIABILITY
Section III. is amended to include the following:
d.  “In order to receive payment, Media Company must have sent an invoice to Agency at the beginning of the following month. Media Company must also have provided its payment information and confirmed the payment method  in accordance with the Terms specified in this document. This sending of the invoice is necessary to claim the payment. Until this invoice is sent and received by Agency, Media Company may not claim payment. If this invoice is sent by Media Company later than 10 days following the end of the month to be paid, Agency reserves the right to pay the amount due at its convenience, notwithstanding any other applicable term .
e.  Invoices will be based solely on Agency’s figures and no other measurements or statistics of any kind may be taken into account nor have any bearing hereunder. These payment will be performed by PayPal if the amount is lower or equal to $1,999.99, if the amount to be paid is higher or equal to $2000, payments will be performed by Wire Transfer. 
f.  Notwithstanding the foregoing, if the amount payable to Media Company for any given month is less than U.S. $50, Agency may roll such amount over to the subsequent payment period until the amount payable reaches a minimum of U.S. $50. All payments will be made in U.S. dollars. 
g.  No payment shall be made hereunder in connection with clicks or impressions on Ads served on the Sites, that have been generated by fraud or other illegal conduct or whether generated manually or by use of a device or other automated process or other technical means where there is no bona fide user that actually views or performs the click on their mobile or web device (“Fraudulent”). In the event that Media Company has already received payments for actions that are found to be Fraudulent, Agency reserves the right to seek credit or remedy from future earnings or to demand reimbursement.
 
5.  V. CANCELLATION AND TERMINATION 
Section V is amended to include the following:
d.  “Notwithstanding anything to the contrary in this Section V, Agency may terminate this IO at any time, with or without cause, and with no further financial obligation on the part of Agency (except for payment for ads delivered up to the termination date) upon giving 48 hours’ prior written notice to Media Company.”
 
6.  IX. AD MATERIALS 
Section IX.a. is amended and will read as follows:
“Agency will use commercially reasonable efforts to submit Advertising Materials pursuant to Section II(c) in accordance with Media Company’s then-existing Policies. Media Company’s sole remedies for a breach of this provision are set forth in Section V(c), above, Sections IX (c) and (d), below, and Sections X (b) and (c), below.

Section IX.b is hereby repealed in its entirety and replaced with the following:
“The purchase of the inventory by Agency is made contingent on Agency having effectively assigned such inventory to an Advertiser.” 

Section IX.g. is amended to include the following:
“Notwithstanding the foregoing, Agency may reference in its marketing materials or on its website that Media Company is a part of its advertising network and Agency may use the trademark and logo of Media Company for that limited purpose.”
 
7.  X. INDEMNIFICATION 
Section X.b. is amended to include the following:
“Media Company further agrees to defend, indemnify, and hold harmless Agency, Advertiser, and each of its Affiliates and Representatives from Losses resulting from any Claims brought by a Third Party resulting from (x) the Sites on which Ads are displayed or delivered; (y) the products or services promoted or offered on the Sites (other than Advertiser’s products or services); (z) the collection and use by Media Company of personal information collected from users of the Sites.”  

Media Company acknowledges and agrees that Agency is not in fact an agent of Advertiser, but a conduit for Advertisers to reach a network of publishers. Therefore, Section X.c., which states that Agency is an agent of Advertisers, is hereby repealed in its entirety. Agency will have no liability to Media Company or its Affiliates for breach by Advertisers, or liability caused by Advertisers. 
 
8.  XI. LIMITATION OF LIABILITY 
Section XI is amended to include the following:
“Notwithstanding any other provision herein to the contrary, in no event shall Agency aggregate liability exceed the lesser of (i) the amount paid by Agency to Media Company during the 6-month period prior to the date the liability first arose, or (ii) USD 7,000.” 

 
9.  XII. NON-DISCLOSURE, DATA USAGE AND OWNERSHIP, PRIVACY AND LAWS 
a. Media Company understands and agrees that Agency collects non-personally identifiable data in connection with serving Ads on the Sites and Media Company agrees that it will not pass any personally identifiable information to Agency. Agency has the right to use and disclose such data for any purpose. Media Company will ensure that each of the Sites on which Ads are displayed contains an effective and enforced privacy policy that (a) discloses (i) the usage of third-party technology and (ii) the data collection, sharing and usage of user information; and (b) complies with all applicable privacy laws and regulations. Media Company will obtain all legally required consents from users for the data collection, sharing and usage resulting from the placement of Ads hereunder. Media Company’s collection, use and disclosure of user data hereunder will comply with all applicable privacy laws and regulations, including without limitation the Children’s Online Privacy Protection Act (“COPPA”).  
b. Section XII.c. is amended to include the following: 
“viii. “Agency Data” means all data that the Agency collects following the purchase of a particular impression or unit of mobile or web ad inventory using its own or third party technology independently of Media Company and the Media Company Sites”.
c. Section XII.h. is amended to include the following:
“Agency will use Collected Data: (a) to serve an Ad following purchase of a particular unit of mobile or web ad inventory, (b) to disclose aggregate statistics about purchases made by Agency, (c) for general campaign performance reporting to its Advertisers, (d) to determine the amounts to bid or pay for such impression or unit of mobile or web ad inventory. Agency owns all Agency Data.”
 
10. XIV. MISCELLANEOUS 
Section XIV.b and Sections XIV.d. are amended and will read:
b.  “Assignment. Neither Agency nor Advertiser may resell, assign, or transfer any of its rights or obligations hereunder, and any attempt to resell, assign, or transfer such rights or obligations without Media Company’s prior written approval will be null and void. Notwithstanding the foregoing, Agency may assign the 4A Terms and IOs thereunder without Media Company’s prior written approval in connection with a transfer of all or substantially all of its business, whether by sale, merger or otherwise. All terms and conditions in these Terms and each IO will be binding upon and inure to the benefit of the parties hereto and their respective permitted transferees, successors, and assigns.” 
Section XIV.d. is amended and will read: 
d.  The second and third sentences of Section XIV.d. are hereby deleted in their entirety and replaced with the following: “All IOs will be governed by the laws of the State of California, without reference to conflict of laws principles. Media Company and Agency (on behalf of itself and Advertiser) agree that any claims, legal proceedings, or litigation arising in connection with the IO (including these amended Terms) will be brought solely in the federal or state courts located in San Francisco, California, and the parties consent to the jurisdiction of such courts.”';
            
        }
        else {
		// Print terms and signature in a new page
        $this->terms .= "Section 1. Incorporation Terms. These Terms and Conditions (together with any Insertion Orders hereunder, the “Agreement”), dated as of the date of the latest signature below (the “Effective Date”), is entered into by and between TML Media, LLC, a Delaware limited liability partnership (“Media Company”), and                                           (Name of the Advertiser), a company organized in the                                             (Country of the Advertiser), (“Advertiser”) (each a “Party,” and, collectively, the “Parties”). The IAB/AAAA Standard Terms and Conditions for Internet Advertising Media Buys One Year or Less Version 3.0 (“IAB Terms”), located at http://www.iab.net/media/file/IAB_4As-tsandcs-FINAL.pdf, are hereby incorporated by reference, as modified herein. All capitalized terms not defined herein shall have the meanings set forth in the IAB Terms. 
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
        }
		$this->addPage();
        $this->printTerms($pdf);
        $pdf->Ln();
        $pdf->Ln();
        $this->printSignature($pdf,$provider->commercial_name);
    }
}