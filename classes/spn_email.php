<?php



class spn_email

{



  function SendPasswordResetEmail($plaintexttoken, $username, $fromemailaddress, $fromemailaddresslabel, $toemailaddress, $toemailaddresslabel)

  {

    require '3rdparty/PHPMailer/PHPMailerAutoload.php';

    // require_once("3rdparty/PHPMailer/class.phpmailer.php");

    require_once("MailCreds.php");



    $return  = 0;

    $mail = new PHPMailer(true);

    $MailCreds = new MailCreds();



    try {

      /*  $mail->isSMTP();

      $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)

      $mail->SMTPAuth   = true;                  // enable SMTP authentication

      $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier

      $mail->Host       = "mail.qwihi.com";      // sets GMAIL as the SMTP server

      $mail->Port       = 465;                   // set the SMTP port for the GMAIL server

      $mail->Username   = $MailCreds->MailUser;  // GMAIL Username

      $mail->Password   = $MailCreds->MailPass;  // GMAIL password

      $mail->AddAddress($toemailaddress,$toemailaddresslabel);

      $mail->SetFrom($fromemailaddress,$fromemailaddresslabel);

      $mail->AddReplyTo('no-reply@qwihi.com','Scol Pa Nos App | Do Not Reply');

      $mail->Subject = 'Password reset for Scol Pa Nos App';

      $mail->IsHtml(true);

      $mail->Body = 'Please click on this <a href='.appconfig::GetBaseURL().'/reset.php?token=' . $plaintexttoken . '&username=' . urlencode($username) . '>link</a> to reset your password <p> The link is only valid for 30 minutes</p>'; // optional - MsgHTML will create an alternate automatically



      $mail->Send(); */


      $subject = "Password reset for Scol Pa Nos App";
      $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
      $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

      $cabeceras .= 'From: Qwihi <no-reply@qwihi.com>' . "\r\n";
      $mensaje = 'Please click on this <a href=' . appconfig::GetBaseURL() . '/reset.php?token=' . $plaintexttoken . '&username=' . urlencode($username) . '>link</a> to reset your password <p> The link is only valid for 30 minutes</p> <b>no-reply@qwihi.com ,Scol Pa Nos App | Do Not Reply</b>';

      mail($username, $subject, $mensaje, $cabeceras);



      $return = 1;

      // Audit by Caribe Developers

    } catch (phpmailerException $e) {

      //echo $e->errorMessage(); //Pretty error messages from PHPMailer

    } catch (Exception $e) {

      //echo $e->getMessage(); //Boring error messages from anything else!

    }



    return $return;
  }





  function SendQuoteToEmail($toemailaddress)

  {

    require_once("3rdparty/PHPMailer/class.phpmailer.php");

    require_once("MailCreds.php");



    $return  = 0;

    $mail = new PHPMailer(true);

    $MailCreds = new MailCreds();



    $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.

    w3.org/TR/html4/loose.dtd">

    <html><head>

    <title>Thank you</title>

    </head>

    <body >

    <table width="750" border="0" align="center" cellpadding="0"

    cellspacing="0" style="border:1px #CCC solid; ">

    <tbody >

    <tr valign="top" >

    <td width="599" style="font-family:Arial, Helvetica, sans-serif;

    font-size:14px; padding-left:30px; padding-right:30px; color:#403F3F; padd

    ing-top:30px; padding-bottom:60px; line-height:120%; font-weight:normal;">





    <div class="mktEditable" id="column_text" style="font-size:18px; font-weight:normal; color:#666 ; text-transform:uppercase; font-family:Arial, Helvetica, sans-serif; margin-top:10px;">New India | Insurance Quote

    </div>

    <hr style="margin-top:15px; height:.02em">



    <div class="mktEditable" id="Dynamic_Content_1" >

    <p><span style="color: #333333;">



    <span style="color: #403f3f; font-family: Arial, H

    elvetica, sans-serif; font-size: 14px; font-style: normal; font-variant: no

    rmal; font-weight: normal; letter-spacing: normal; line-height: 16.8px; orp

    hans: auto; text-align: start; text-indent: 0px; text-transform: none; whit

    e-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0

    px; display: inline !important; float: none; background-color: #ffffff;">

    </span></span></p>



    <!-- sample text here -->

    <b>Sample Calculation (Test Mode)</b>

    <br />

    <br />

    Broker: Aruba Bank (ARB)<br />

    Catalog Value:  65000 <br />

    Product:  APS2 (Smart Car)<br />

    TP Coverage Limit: 150000 <br />

    Accident-free Years: 2 Years <br />

    Special Discount 1: 0% <br />

    Special Discount 2: 0% <br />

    Period of Insurance: 1 Year <br />

    <br />

    Name of person: Elbert Lumenier <br />

    Vehicle Make: Nissan <br />

    Vehicle Model: Skyline <br />

    Vehicle Year: 2014 <br />

    <br />

    <p style="color: #000000; font-family: Times; font-size: medium; font-sty

    le: normal; font-variant: normal; font-weight: normal; letter-spacing: norm

    al; line-height: normal; orphans: auto; text-align: start; text-indent: 0px

    ; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px;

    -webkit-text-stroke-width: 0px;">

    <span style="color: #333333;">



    </span></p>

    <p style="color: #000000; font-family: Times; font-size: medium; font-sty

    le: normal; font-variant: normal; font-weight: normal; letter-spacing: norm

    al; line-height: normal; orphans: auto; text-align: start; text-indent: 0px

    ; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px;

    -webkit-text-stroke-width: 0px;"><span style="color: #333333;">







    </span><br /><br /><span style="color: #333333;">

    </span><br /><br /><span style="color: #3333

    33;"><strong></strong></span></p></div>

    </td>

    </tr>

    </tbody>

    </table>

    <table width="750" border="0" align="center" cellspacing="0" ><tbod

    y>

    <tr style="background-color:#435670; ">

    <td width="750" valign="middle" style="font-family:Arial, Helvetica, sans-serif; color:#FFF; font-size:10px;

    padding-left:30px; height:25px; padding-top:3px; padding-bottom:3px;">

    <div class="mktEditable" id="Dynamic_Content_8" >New India Assurance Representative N.V.</div>

    </td>

    </tr>

    </tbody>

    </table>



    </body>

    </html>';



    try {

      //$mail->Host       = "mail.yourdomain.com";

      //$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)

      $mail->SMTPAuth   = true;                  // enable SMTP authentication

      $mail->SMTPSecure = "tls";                 // sets the prefix to the servier

      $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server

      $mail->Port       = 587;                   // set the SMTP port for the GMAIL server

      $mail->Username   = $MailCreds->MailUser;  // GMAIL Username

      $mail->Password   = $MailCreds->MailPass;  // GMAIL password

      $mail->AddAddress($toemailaddress, $toemailaddress);

      $mail->SetFrom('donotreply@newindia.aw', 'New India | Broker Portal');

      $mail->AddReplyTo('donotreply@newindia.aw', 'New India | Broker Portal');

      $mail->Subject = 'Your New India Assurance Quotation';

      $mail->IsHtml(true);

      $mail->Body = $body;

      $mail->Send();

      $return = 1;

      // Audit by Caribe Developers

      require_once("spn_audit.php");

      $spn_audit = new spn_audit();

      $UserGUID = $_SESSION['UserGUID'];

      $spn_audit->create_audit($UserGUID, 'email', ' send quote email', appconfig::GetDummy());
    } catch (phpmailerException $e) {

      //echo $e->errorMessage(); //Pretty error messages from PHPMailer

    } catch (Exception $e) {

      //echo $e->getMessage(); //Boring error messages from anything else!

    }



    return $return;
  }





  function SendSecurePINResetEmail($StudentInfo, $securepin, $fromemailaddress, $fromemailaddresslabel, $toemailaddress)

  {

    require '3rdparty/PHPMailer/PHPMailerAutoload.php';

    // require_once("3rdparty/PHPMailer/class.phpmailer.php");

    require_once("MailCreds.php");



    $return  = 0;

    $mail = new PHPMailer(true);

    $MailCreds = new MailCreds();



    try {

      for ($i = 0; $i < count($toemailaddress); $i++) {

        $mail->isSMTP();

        $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)

        $mail->SMTPAuth   = true;                  // enable SMTP authentication

        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier

        $mail->Host       = "mail.qwihi.com";      // sets GMAIL as the SMTP server

        $mail->Port       = 465;                   // set the SMTP port for the GMAIL server

        $mail->Username   = $MailCreds->MailUser;  // GMAIL Username

        $mail->Password   = $MailCreds->MailPass;  // GMAIL password

        $mail->AddAddress($toemailaddress[$i], $toemailaddress[$i]);

        $mail->SetFrom($fromemailaddress, $fromemailaddresslabel);

        $mail->AddReplyTo('no-reply@qwihi.com', 'Scol Pa Nos App | Do Not Reply');

        $mail->Subject = 'Secure PIN reset for Scol Pa Nos Parents App';

        $mail->IsHtml(true);



        $mail->Body = ' The new SecurePIN of <b>' . $StudentInfo["firstname"] . ' ' . $StudentInfo["lastname"] . '</b> to enter the Parents module is: <b>' . $securepin . '</b>';

        // optional - MsgHTML will create an alternate automatically



        $mail->Send();
      }

      $return = 1;

      // Audit by Caribe Developers

    } catch (phpmailerException $e) {

      //echo $e->errorMessage(); //Pretty error messages from PHPMailer

    } catch (Exception $e) {

      //echo $e->getMessage(); //Boring error messages from anything else!

    }



    return $return;
  }
}
