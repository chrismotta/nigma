<?php

class PDFAgreement extends PDF
{

	public function getPdfName()
    {
    	return 'ServiceAgreement-' . $this->getDataItem('provider')->id . '-KickAds.pdf';
    }


    protected function initMargins()
    {
        $pdf = $this->getPdf();
        $pdf->SetMargins(25, 15, 20);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(20);

        $pdf->SetAutoPageBreak(TRUE, 20);
    }
	public function renderPdf()
    {
        $this->addPage();
        $pdf = $this->getPdf(); // get TCPDF instance

        $this->setConfig($pdf); // set format configuration

        // Print presentation
		$provider  = $this->getDataItem('provider');
        $company='KICKADS '.$provider->entity[0].'.'.$provider->entity[1].'.'.$provider->entity[2];
        if(Networks::model()->findByPk($provider->id))
            $type='network';
        if(Affiliates::model()->findByPk($provider->id))
            $type='affiliate';

        // Print provider section
		unset($values);
        // $values[$provider->getAttributeLabel('id')]                  = $provider->id;
        $country        =isset($provider->country->name) ? ucwords(strtolower($provider->country->name)) : '';
        $effective_date =date('d-m-Y', strtotime($provider->start_date));
        $tax            =$provider->tax_id;
        $name           =$provider->commercial_name;
        $address        =$provider->address;
        $phone          =isset($provider->phone) ? $provider->phone : '+000 0000 0000';
        $net            =$provider->net_payment;

        $headTitle='<h1>Client Service Agreement</h1><br/><b>Deal Terms</b>';
        $agreement='
<p>This Agreement (as defined below) is made on this '.$effective_date.'(the “Effective Date”), by and between:<br/>
<b>'.$name.'</b> '.$tax.' , a company registered under the laws of '.$country.' , having its registered offices at '.$address.', '.$country.'; and,</p>

<p>The company named below (“You” or “Your”):</p>

<p><b>'.$company.' </b></p>

<p>You and '.$name.' agree to be bound by the Agreement, this Deal Terms, the Terms and Conditions attached hereto. These Deal Terms, the Terms and Conditions are collectively referred to as the “Agreement”). The Terms and Conditions are incorporated by reference and form an indivisible part hereof. </p>

<p>i. <b>Your Contact Information</b><br/>
<b>Company Name:</b> '.$company.'<br/>
<b>TAX ID:</b> 30-71447415-0<br/>
<b>Full Address:</b> Av. Dr Honorio Pueyrredón 1309, 1°C, 1414, Ciudad Autónoma de Buenos Aires Argentina<br/>
<b>Phone No.:</b> +54 (011) 4777-6274<br/>
<b>Billing Contact (Full Name & Email):</b> Silvina Shimojo – silvina.shimojo@kickads.mobi<br/>
<b>Business Contact (Full Name & Email):</b> Pedro Forwe -pedro.forwe@kickads.mobi</p>

<p><b>ii. Fees</b><br/>
a. Service Fees:</p>

<p>For Clicks:<br/>
Cost per Click (CPC) rate will be agreed in advance and signed in an Insertion Order. CPC rate may be modified via self service platforms, or assisted account manager service as long as both parties are always in agreement.</p>

<p>For Acquisition/Leads/Installs/Downloads:<br/>
Cost per Acquisition/Leads/Installs/Downloads (CPA/CPL/CPI/CPD) rate will be agreed in advance and signed in an Insertion Order. Rate may be modified via self service platforms, or assisted account manager service as long as both parties are always in agreement.</p>

<p>For Impressions or Ad Requests delivered, using the ad server CPM (Cost per mille) rate will be agreed in advance and signed in an Insertion Order. Rate may be modified via self service platforms, or assisted account manager service as long as both parties are always in agreement.</p>

<p>'.$name.' shall invoice You at the beginning of each month for payment of the Fees due by You to '.$name.' Payment shall be wire-transferred to '.$name.' within '.$net.'  days of receipt of said invoice.</p>

<p>In Witness Whereof, the parties hereto have caused the Agreement, including this Deal Terms, to be executed by their respective authorized representatives, as of the Effective Date:</p>

';
        
        $pdf->SetTextColor(0);
        $pdf->WriteHTML($headTitle, true,false,false,false, 'C');
        $pdf->Ln();
        $pdf->WriteHTML($agreement, true, false, false,false,'J');
        $pdf->Ln();

		// Print terms and signature in a new page
		$this->addPage();
        $terms = "<b>Terms and Conditions</b>

<p>1. <b>Definitions.</b> For purposes hereof (including any and all attachments, schedules and amendments made hereto or incorporated herein now or in the future), the following capitalized terms shall have the following meaning.<br/>
1.1 <b>“Ad Banner”</b> or <b>“Ad(s)”</b> - mean a promotional message (including any code embedded therein) consisting of text, graphics, audio and/or video, or combination thereof, displayed on online media inventory for publicizing advertisers’ products and/or services;<br/>
1.2 <b>“Ad Network”</b> - means a legal entity or person that represents or works with a group of Media Buyers and/or Media Sellers. An Ad Network may act as a Media Buyer or a Media Seller;<br/>
1.3 <b>“Ad Size Limit”</b> - means amount of kilobytes, which is the maximum Ad Banner size allowed to be displayed by the Service;<br/>
1.4 <b>“Affiliate”</b> - means with respect to a party, any business entity controlling, controlled by or under common control with, such party, whereby “control” shall mean the possession of the power to direct or cause the direction of the activities, management or policies of such person, organization or entity. Control shall be deemed to exist when a person, organization or entity owns or directly controls 50% or more of the outstanding voting stock or other ownership interest of the other organization or entity;<br/>
1.5 <b>“Campaign”</b> – means a campaign as referred to in the Exchange Server as a campaign;<br/>
1.6 <b>“Confidential Information”</b> - means any material or information, disclosed by a party to the other, whether orally, visually or in writing, which is not generally available in the public domain. Such information may include, without limitation, information pertaining to the Service, the Exchange Server, and other information the parties disclose to one another that are marked or identified as being confidential, or should reasonably be understood to be confidential by the Disclosing Party given the circumstances surrounding the disclosure. Notwithstanding the foregoing, the terms of this Agreement (including pricing terms) and the Exchange Server will be deemed to be Confidential Information of ".$name.";<br/>
1.7 <b>“CPM”</b> - means the charge per 1,000 Impressions of Ad Banners;<br/>
1.8 <b>“Exchange Server”</b> - means ".$name."’ proprietary owned online ad serving platform and virtual marketplace: (i) Enabling Media Buyers to bid on online media Inventory of Media Sellers; (ii) Allowing Media Sellers to place Ads Inventory on their website(s) for auction to Media Buyers, where the Ad will be served; and (iii) Facilitating transactions between Media Sellers offering Inventory purchased by Media Buyers for placing Ad Banner and/or Ads;<br/>
1.9 <b>“Fees”</b> - mean the Service Fee;<br/>
1.10 <b>“Impression”</b> - means the display of a single Ad Banner or Ad, or the delivery of the ad request to any other server, other than the one that originated the request, as counted by ".$name.";<br/>
1.11 <b>“Intellectual Property”</b> - mean all intangible legal rights, titles and interests recognized by any jurisdiction, evidenced by or embodied in all: (i) inventions (regardless of patentability and whether or not reduced to practice), improvements thereto, and patents, patent applications, and patent disclosures, together with all reissuances, continuations, continuations in part, revisions, extensions, and reexaminations thereof; (ii) trademarks, service marks, trade dress, logos, trade names, and corporate names, together with translations, adaptations, derivations, and combinations thereof, including goodwill associated therewith, and applications, registrations, and renewals in connection therewith; (iii) any work of authorship, regardless of copyrightability, copyrightable works, copyrights (including droit morale) and applications, registrations, and renewals in connection therewith; (iv) trade secrets and Confidential Information; and (vi) other proprietary rights and any other similar rights, in each case on a worldwide basis, and copies and tangible embodiments thereof, in whatever form or medium;<br/>
1.12 <b>“Inventory”</b> - means online media inventory that You are the owner thereof, or that You control (including having the right and ability to place Ads on such inventory and to authorize others to do so), or that You manage, or if You are an Ad Network, have the contractual right to place Ads on;<br/>
1.13 <b>“External Clicks”</b> – means clicks bought by You outside the Exchange Server, but which are streamed through the Exchange Server;<br/>
1.14 <b>“Media Buyer”</b> - means a legal entity or person that bids on Inventory for the placement of Ads via the Exchange Server;<br/>
1.15 <b>“Media Seller”</b> - means a legal entity or person that wish to sell Inventory to Media Buyers using the Exchange Server;<br/>
1.16 <b>“Non-Guaranteed Ads”</b> – mean Ads that are displayed on an as available space basis on the Media Seller’s Inventory, which are not guaranteed for delivery based on duration and/or number of Impressions;<br/>
1.17 <b>“Payment Effective Date”</b> - means the date agreed in the Deal Terms as of which You are enabled to trade on the Exchange Server and as of which ".$name." starts counting the Impressions reported in the Exchange Server;<br/>
1.18 <b>“Personally Identifiable Information”</b> - mean information that does or can potentially be used to uniquely identify, contact or locate a person, for example, social security number, ID and/or credit card number;<br/>
1.19 <b>“Revenue”</b> - mean the gross revenue reported by the Exchange Server for Media Sellers, and the gross spend revenue reported by the Exchange Server for Media Buyers, as counted by the Exchange Server, which count shall be final and conclusive;<br/>
1.20 <b>“Service”</b> – means the services defined in the Insertion Order:<br/>";
if($type=='affiliate')
{
    $terms.="1.21 <b>“Statistics”</b> - means data collected by the Exchange Server on the purchase and/or the sale of Inventory that ".$name." collects from Your use of the Exchange Server, such as Impressions, revenues, targeting, budget and prices;<br/>
    1.22 <b>“Ad Request”</b> – means a request for an advertisement (before it has become an impression)</p>

    <p>2. <b>Scope of Agreement; License; Service</b><br/>
    2.1 This Agreement sets forth the conditions under which You are granted a license to Access and benefit from the Service and the Exchange Server, enabling You to trade on the Exchange Server, in consideration for the Fees You will be eligible to pay, as set forth in the Deal Terms.<br/>
    2.2 Subject to the terms and conditions herein, ".$name." grants You a limited, non-exclusive, non-sub-licenseable, non-transferable right to access and use the Service and Exchange Server, which access thereof is allowed solely via ".$name."’s web servers or API by means of a password issued by ".$name." solely to You. Your password is to be held in confidence, strictly for Your own use.</p>";
    $count=3;    
}else $count=2;

$terms.="<p>".$count.". <b>Payments</b><br/>
".$count.".1 You will pay ".$name." the Service Fees set forth in the Deal Terms. ".$name."  shall have the right to issue You an invoice for the Service Fees at the beginning of each month. Fees are due upon receipt of the invoice and payable no later than ".$net." days thereafter.<br/>
".$count++.".2 Fees hereunder are denominated in US Dollars and paid by wire transfer to an account designated by ".$name.", or by other means expressly agreed to in writing by the parties.<br/>
".$count.". <b>Term and Termination</b><br/>
".$count.".1 This Agreement will commence as of the Payment Effective Date and continue perpetually until terminated as set forth hereunder.<br/>
".$count++.".2 Each party shall have the right to terminate this Agreement at any time, for convenience and without cause, by way of a sixty (30) day written notice to the other party.</p>

<p>".$count.". <b>Confidentiality</b><br/>
".$count.".1 During the Term, one party (“Disclosing Party”) may disclose Confidential Information to the other party (“Receiving Party”).<br/>
".$count++.".2 Receiving Party agrees that for the term of this Agreement, Receiving Party will neither disclose the Confidential Information to any third party nor use the Confidential Information other than to perform its obligations under this<br/>
Agreement or as otherwise permitted in this Agreement; provided, however, that Receiving Party shall be permitted to disclose the Confidential Information of Disclosing Party only to those of its employees, representatives, Affiliates and agents who have a reasonable need to know such information and who are equally bound to keep such information confidential in a manner consistent with the terms of this agreement, the foregoing without limitation the Receiving Party’s liability for any thereof of the terms of this agreement by either of the foregoing. Receiving Party shall exercise at least the same degree of care to safeguard the confidentiality of Disclosing Party’s Confidential Information that it exercises to safeguard the confidentiality of its own confidential information, but in any event no less than reasonable care.</p>

<p>".$count.". <b>Miscellaneous</b><br/>
".$count.".1 No press releases and/or any other public announcement and/or disclosures regarding this Agreement are permitted by either party, without first obtaining the other party’s written consent.<br/>
".$count.".2 All notices and other communications given or made pursuant hereto shall be in writing and shall be deemed to have been duly given or made as of the date delivered or transmitted, and shall be effective upon receipt, if delivered personally, sent by air courier, or sent by electronic transmission, with confirmation received, to the phone number specified as follows: ".$phone.", You ".$company." +5411 4777-6274<.<br/>
".$count.".3 This Agreement may be executed: (i) in counterparts, each of which will be deemed an original, but all of which taken together will constitute one and the same instrument; or (ii) by scanning the same and attaching to an email and facsimile, such email and facsímile executions will have the same force and effect as an original document with original signatures.</p>
<br/>
";
		$pdf->WriteHTML($terms, true,false,false,false, 'J');
		$this->printSignature($pdf,$name,$company);
    }

}