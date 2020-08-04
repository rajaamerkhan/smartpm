<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
  <title>Roofing Email template</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0 ">
  <meta name="format-detection" content="telephone=no">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
</head>

<body class="em_body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" bgcolor="#efefef">
  <table class="em_full_wrap" valign="top" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#efefef" align="center" style="border-collapse: collapse; mso-table-lspace: 0px; mso-table-rspace: 0px;">
    <tbody>
      <tr>
        <td valign="top" align="center" style="border-collapse: collapse; mso-line-height-rule: exactly;">
          <table class="em_main_table" style="border-collapse: collapse; mso-table-lspace: 0px; mso-table-rspace: 0px; width: 700px;" width="700" cellspacing="0" cellpadding="0" border="0" align="center">
            <tbody>
              <tr>
                <td style="border-collapse: collapse; mso-line-height-rule: exactly; padding: 15px;" class="em_padd" valign="top" bgcolor="#FFA500" align="center">
                  <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="border-collapse: collapse; mso-table-lspace: 0px; mso-table-rspace: 0px;">
                    <tbody>
                      <tr>
                        <td valign="top" align="center" style="border-collapse: collapse; mso-line-height-rule: exactly;"><img class="em_img" alt="logo" style="display: block; font-family: Arial, sans-serif; font-size: 30px; line-height: 34px; color: #000000; max-width: 700px; border: 0; outline: none;" src="<?= $logoUrl ?>" width="150" border="0"></td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="border-collapse: collapse; mso-line-height-rule: exactly; padding: 35px 35px 30px;" class="em_padd" valign="top" bgcolor="#ffffff" align="center">
                  <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="border-collapse: collapse; mso-table-lspace: 0px; mso-table-rspace: 0px;">
                    <tbody>
                      <tr>
                        <td style="border-collapse: collapse; mso-line-height-rule: exactly; font-family: 'Open Sans', Arial, sans-serif; font-size: 16px; line-height: 20px; color: #333333;" valign="top" align="left">
                          	<p>Lead <b>#<?= $lead_id ?> <?= $lead_name ?></b> has been assigned to you.</p>
							<br><br>
							<p><a href="<?php echo $link ?>" target="_blank">Click here</a> to view it.</p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="border-collapse: collapse; mso-line-height-rule: exactly; padding: 38px 30px;" class="em_padd" valign="top" bgcolor="#f6f7f8" align="center">
                  <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="border-collapse: collapse; mso-table-lspace: 0px; mso-table-rspace: 0px;">
                    <tbody>
                      <tr>
                        <td style="border-collapse: collapse; mso-line-height-rule: exactly; font-family: 'Open Sans', Arial, sans-serif; font-size: 11px; line-height: 18px; color: #999999;" valign="top" align="center"><a href="#" target="_blank" style="border-collapse: collapse; mso-line-height-rule: exactly; color: #999999; text-decoration: underline;">PRIVACY STATEMENT</a> | <a href="#" target="_blank" style="border-collapse: collapse; mso-line-height-rule: exactly; color: #999999; text-decoration: underline;">TERMS OF SERVICE</a> | <a href="#" target="_blank" style="border-collapse: collapse; mso-line-height-rule: exactly; color: #999999; text-decoration: underline;">RETURNS</a><br>
                          © <?= date('Y') ?> SmartPM. All Rights Reserved.</td>
                      </tr>
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
  <table cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse; mso-table-lspace: 0px; mso-table-rspace: 0px;">
    <tr>
      <td class="em_hide" style="border-collapse: collapse; mso-line-height-rule: exactly; white-space: nowrap; display: none; font-size: 0px; line-height: 0px;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
    </tr>
  </table>
</body>

</html>
