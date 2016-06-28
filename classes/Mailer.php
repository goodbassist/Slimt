<?php

class Mailer
{
	/*==============================================
	// HOW TO USE MAILER CLASS
	// 
	// Sample Code
	// 
	//	$to 						= 'example@example.com'; // Email of the recipient
	//	$title 						= 'Title of the message'; // This is the title that will be attached to the email
	//	$subject 					= 'Subject of the Email'; // This is the Subject of the email
	//	$cont 						= 'Html Content to be sent to the user, refer to creatBody() for  CSS STYLES';
	//	$footerOption 				= 'Options for footer. This Could be 1,2,3 ... REFER TO createFooter()'; // Footer of the email	//	OPTIONAL
	//	
	//
	//	$Mailer 					= new Mailer($to, $cont, $subject, $footerOption);
	//
	//
	//  // SET EXTRA PARAMETERS (ALL OPTIONAL)
	//
	// 	$Mailer->title 				= 'Title of the email';
	// 	$Mailer->attachment 		= 'array containing elements to be attached to the mail. MUST BE AN ARRAY', eg array('file.txt','book.zip');
	// 	$Mailer->fromEmail 			= 'Specifies the email of the sender'; 
	// 	$Mailer->fromNAme 			= 'Specifies the name of the sender';
	// 	$Mailer->replyTo 			= 'eg, noreply@atv.orgSpecifies the Email to which they can reply';
	// 	$Mailer->replyToInfo 		= 'Info to be attached to reply email';
	// 	$Mailer->toName 			= 'Name of the Recipient' ;
	// 	$Mailer->cc					= 'Array of emails to be copied, MUST BE AN ARRAY, eg array('a@yahoo.com','b@gmail.com')';
	// 	$Mailer->bcc 				= 'Array of bcc's. MUST BE AN ARRAY, eg array('a@yahoo.com','b@gmail.com');
	//
	//	$Mailer->sendMail() // SEND THE MAIL
	//
	//
	
	*/
	
	private $_footer_option, $_to, $_cont, $_subject;


	public $cc 						= array(); // array of cc's
	public $bcc 					= array(); // array of bcc's
	public $attachment 				= array(); // array of attachments
	public $title 					= 'Shobbu Notification'; // Set Title for mails
	public $fromEmail 				= 'info@shobbutest.com'; // Set Default Sender
	public $fromName 				= 'Shobbu'; // Set Default sender name;
	public $replyTo 				= 'info@shobbutest.com'; // Set Default Reply email;
	public $replyToInfo				= ''; // Set Default Reply email;
	
	function __construct($to, $cont, $subject, $footerOption = '')
	{
		$this->_to					= $to;
		$this->_cont				= $cont;
		$this->_subject				= $subject;
		$this->_footerOption		= $footerOption;
		define(SITE_LOGO, BASEURL."img/logo.png");
	}
	
	private function _createBody()
	{
		$date 						= date("F, j Y");
		
		$messanger					= '
			<html xmlns="http://www.w3.org/1999/xhtml"><head>
			      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			      <meta name="viewport" content="width=device-width, initial-scale=1.0">
			      <title>Minty-Multipurpose Responsive Email Template</title>
			      <style type="text/css">
			         /* Client-specific Styles */
			         #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
			         body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
			         /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
			         .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
			         .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing.  More on that: http://www.emailonacid.com/forum/viewthread/43/ */
			         #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
			         img {outline:none; text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
			         a img {border:none;}
			         .image_fix {display:block;}
			         p {margin: 0px 0px !important;}
			         
			         table td {border-collapse: collapse;}
			         table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
			         /*a {color: #e95353;text-decoration: none;text-decoration:none!important;}*/
			         /*STYLES*/
			         table[class=full] { width: 100%; clear: both; }
			         
			         /*################################################*/
			         /*IPAD STYLES*/
			         /*################################################*/
			         @media only screen and (max-width: 640px) {
			         a[href^="tel"], a[href^="sms"] {
			         text-decoration: none;
			         color: #ffffff; /* or whatever your want */
			         pointer-events: none;
			         cursor: default;
			         }
			         .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
			         text-decoration: default;
			         color: #ffffff !important;
			         pointer-events: auto;
			         cursor: default;
			         }
			         table[class=devicewidth] {width: 440px!important;text-align:center!important;}
			         table[class=devicewidthinner] {width: 420px!important;text-align:center!important;}
			         table[class="sthide"]{display: none!important;}
			         img[class="bigimage"]{width: 420px!important;height:219px!important;}
			         img[class="col2img"]{width: 420px!important;height:258px!important;}
			         img[class="image-banner"]{width: 440px!important;height:106px!important;}
			         td[class="menu"]{text-align:center !important; padding: 0 0 10px 0 !important;}
			         td[class="logo"]{padding:10px 0 5px 0!important;margin: 0 auto !important;}
			         img[class="logo"]{padding:0!important;margin: 0 auto !important;}

			         }
			         /*##############################################*/
			         /*IPHONE STYLES*/
			         /*##############################################*/
			         @media only screen and (max-width: 480px) {
			         a[href^="tel"], a[href^="sms"] {
			         text-decoration: none;
			         color: #ffffff; /* or whatever your want */
			         pointer-events: none;
			         cursor: default;
			         }
			         .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
			         text-decoration: default;
			         color: #ffffff !important; 
			         pointer-events: auto;
			         cursor: default;
			         }
			         table[class=devicewidth] {width: 280px!important;text-align:center!important;}
			         table[class=devicewidthinner] {width: 260px!important;text-align:center!important;}
			         table[class="sthide"]{display: none!important;}
			         img[class="bigimage"]{width: 260px!important;height:136px!important;}
			         img[class="col2img"]{width: 260px!important;height:160px!important;}
			         img[class="image-banner"]{width: 280px!important;height:68px!important;}
			         
			         }
			      </style>

			      
			   </head>
			<body>
			<div class="block">
			   <!-- Start of preheader -->
			   <table width="100%" bgcolor="#f6f4f5" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="preheader">
			      <tbody>
			         <tr>
			            <td width="100%">
			               <table width="580" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
			                  <tbody>
			                     <!-- Spacing -->
			                     <tr>
			                        <td width="100%" height="5"></td>
			                     </tr>
			                     <!-- Spacing -->
			                     <tr>
			                        <td align="right" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 10px;color: #999999" st-content="preheader">
			                           If you cannot read this email, please  <a class="hlite" href="#" style="text-decoration: none; color: #0db9ea">click here</a> 
			                        </td>
			                     </tr>
			                     <!-- Spacing -->
			                     <tr>
			                        <td width="100%" height="5"></td>
			                     </tr>
			                     <!-- Spacing -->
			                  </tbody>
			               </table>
			            </td>
			         </tr>
			      </tbody>
			   </table>
			   <!-- End of preheader -->
			</div>
			<div class="block">
			   <!-- start of header -->
			   <table width="100%" bgcolor="#fff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
			      <tbody>
			         <tr>
			            <td>
			               <table width="580" bgcolor="#fff" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" hlitebg="edit" shadow="edit">
			                  <tbody>
			                     <tr>
			                        <td>
			                           <!-- logo -->
			                           <table width="280" cellpadding="0" cellspacing="0" border="0" align="left" class="devicewidth">
			                              <tbody>
			                                 <tr>
			                                    <td valign="middle" width="270" style="padding: 10px 0 10px 20px;" class="logo">
			                                       <div class="imgpop">
			                                          <a href="#"><img src="'.SITE_LOGO.'" alt="logo" border="0" style="display:block; border:none; outline:none; text-decoration:none;" st-image="edit" class="logo"></a>
			                                       </div>
			                                    </td>
			                                 </tr>
			                              </tbody>
			                           </table>
			                           <!-- End of logo -->
			                           <!-- menu -->
			                           <table width="280" cellpadding="0" cellspacing="0" border="0" align="right" class="devicewidth">
			                              <tbody>
			                                 <tr>
			                                    <td width="270" valign="middle" style="font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;line-height: 24px; padding: 10px 0;" align="right" class="menu" st-content="menu">&nbsp;</td>
			                                    <td width="20"></td>
			                                 </tr>
			                              </tbody>
			                           </table>
			                           <!-- End of Menu -->
			                        </td>
			                     </tr>
			                  </tbody>
			               </table>
			            </td>
			         </tr>
			      </tbody>
			   </table>
			   <!-- end of header -->
			</div><div class="block">
			   <!-- image + text -->
			   <table width="100%" bgcolor="#f6f4f5" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="bigimage">
			      <tbody>
			         <tr>
			            <td>
			               <table bgcolor="#ffffff" width="580" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" modulebg="edit">
			                  <tbody>
			                     <tr>
			                        <td width="100%" height="20"></td>
			                     </tr>
			                     <tr>
		                           <td height="">
                            		</td>
			                     </tr>
			                  </tbody>
			               </table>
			            </td>
			         </tr>
			      </tbody>
			   </table>
			</div>

			<div class="block">
			   <!-- start textbox-with-title -->
			   <table width="100%" bgcolor="#f6f4f5" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="fulltext">
			      <tbody>
			         <tr>
			            <td>
			               <table bgcolor="#ffffff" width="580" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" modulebg="edit">
			                  <tbody>
			                     <!-- Spacing -->
			                     <tr>
			                        <td width="100%" height="30"></td>
			                     </tr>
			                     <!-- Spacing -->
			                     <tr>
			                        <td>
			                           <table width="540" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
			                              <tbody>
			                                 <!-- Title -->
			                                 <tr>
			                                    <td style="font-family: Helvetica, arial, sans-serif; font-size: 18px; color: #333333; text-align:center;line-height: 20px;" st-title="fulltext-title">
			                                       '.$this->title.'</td>
			                                 </tr>
			                                 <!-- End of Title -->
			                                 <!-- spacing -->
			                                 <tr>
			                                    <td height="5"></td>
			                                 </tr>
			                                 <!-- End of spacing -->
			                                 <!-- content -->
			                                 <tr>
			                                    <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #95a5a6; text-align:center;line-height: 30px;" st-content="fulltext-paragraph">
			                                       <strong>'.$this->_cont.'</strong></td>
			                                 </tr>
			                                 <!-- End of content -->
			                                 <!-- Spacing -->
			                                 <tr>
			                                    <td width="100%" height="10"></td>
			                                 </tr>
			                                 <!-- Spacing -->
			                                 <!-- button -->
			                                 <tr>
			                                    <td>			                                       
			                                    </td>
			                                 </tr>
			                                 <!-- /button -->
			                                 <!-- Spacing -->
			                                 <tr>
			                                    <td width="100%" height="30"></td>
			                                 </tr>
			                                 <!-- Spacing -->
			                              </tbody>
			                           </table>
			                        </td>
			                     </tr>
			                  </tbody>
			               </table>
			            </td>
			         </tr>
			      </tbody>
			   </table>
			   <!-- end of textbox-with-title -->
			</div>
			<div class="block">
			   <!-- Start of 2-columns -->
			   <!-- End of 2-columns -->   
			</div>
			<div class="block">
			   <!-- Full + text -->
			</div>
			<div class="block">
			   <!-- Start of preheader -->
			   <table width="100%" bgcolor="#f6f4f5" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="postfooter">
			      <tbody>
			         <tr>
			            <td width="100%">
			               <table width="580" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
			                  <tbody>
			                     <!-- Spacing -->
			                     <tr>
			                        <td width="100%" height="5"></td>
			                     </tr>
			                     <!-- Spacing -->
			                     <tr>
			                        <td align="center" valign="middle" style="font-family: Helvetica, arial, sans-serif; font-size: 10px;color: #999999" st-content="preheader">
			                           If you don\'t want to receive updates. please  <a class="hlite" href="#" style="text-decoration: none; color: #0db9ea">unsubscribe</a> 
			                        </td>
			                     </tr>
			                     <!-- Spacing -->
			                     <tr>
			                        <td width="100%" height="5"></td>
			                     </tr>
			                     <!-- Spacing -->
			                  </tbody>
			               </table>
			            </td>
			         </tr>
			      </tbody>
			   </table>
			   <!-- End of preheader -->
			</div>

			</body></html>
		';			
		return $messanger;
	}
	
		
	public function validateEmail()
	{
		$email 						= $this->_to;
		$pattern 					= "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i"; 
        if(preg_match($pattern, trim(strip_tags($email)))) 
        { 
            $ret = trim(strip_tags($email)); 
			return $ret;
        } 
        return false;
	}
		
		
    public function sendMail()
	{							

		$mail             			= new PHPMailer(); // defaults to using php "mail()"
	
		//$body             		= file_get_contents('contents.html'); // Read from external file
		
		$mail->SetFrom($this->fromEmail, $this->fromName);
		
		$mail->AddAddress($this->_to, $this->_toName);
		
		$mail->Subject    			= $this->_subject;
		
		$mail->AltBody    			= "To view the message, please use an HTML compatible email viewer!"; // Alt Body
		
		$mail->MsgHTML($this->_createBody());

		$mail->AddReplyTo($this->replyTo, $this->replyToInfo);



		
		// Add CC if they exist
		$cc 						= $this->cc;
		if(is_array($cc) && count($cc) > 0)
		{
			foreach ($cc as $cc) 
			{
				$mail->AddCC($cc);  // Add CC
			}
		}

		// Add BCC if they exist
		$bcc 						= $this->bcc;
		if(is_array($bcc) && count($bcc) > 0)
		{
			foreach ($bcc as $bccData) 
			{
				$mail->AddBCC($bccData); // Add BCC
			}
		}
		
		// Add Attcahments if they exist
		$attachment 				= $this->attachment;
		if(is_array($attachment) && count($attachment) > 0)
		{
			foreach ($attachment as $attach) 
			{
				echo $attach.' added as attachment <br>';
				$mail->AddAttachment($attach);      // attachment
			}
		}
		
		if(!$mail->Send()) 
		{
		  //echo "Mailer Error: " . $mail->ErrorInfo;
		  return false;
		} else {
			//echo "Mail sent to ".$this->_to;
		  return true;
		}

	}
	
	private function _createfooter()
	{
		$footerFunc 				= '_emailFooter'.$this->_footerOption;
		if(method_exists($this, footerFunc))
		{
			$ret 					= '
				'.$this->$footerFunc().'
				<img src="'.BASEURL.'images/site_images/lgo_email.png" class="atv_logo">
			';
			return $ret;
		}
		return $this->_defFooter();
	}

	private function _defFooter()
	{
		$ret 						= '
			<a href="#">Vist Our Site Now >> </a>
		';
		return $ret;
	}

	private function _emailFooter1()
	{
		$ret 						= '
			<a href="www.citn.org">Vist Our Site Now >> </a>
		';
		return $ret;
	}

	
}

?>