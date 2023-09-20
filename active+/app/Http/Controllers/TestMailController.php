<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Redirect, Response, Session;
use DataTables;

class TestMailController extends Controller
{
    public function send_mail()
    {
        $users=DB::table('promo_mail')->where('deleted', 'No')->get();

        foreach($users as $user){


        $subject='Grow Your Bussiness';
        $to_mail_id= $user->email;
        $mail_template='<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <title></title> 
    <style>
        table,
        td,
        div,
        h1,
        p {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body style="margin:0;padding:0;">
    <table role="presentation"
        style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#F5f5f5;">
        <tr>
            <td align="center" style="padding:0;">
                <table role="presentation"
                    style="width:800px;border-collapse:collapse;border:1px solid #F1F1F1 ;border-spacing:0;">
                    <tr style="background: #fff;padding: 16px; width: 100%;"> 
                        <td style="display: flex;">
                            <img src="https://vingreentech.com/assets/images/logos/green_logo.png" alt="" width="150px"
                                style="height:auto;" /> 
                        </td>
                        
                    </tr>
                    <tr style=" background-color: #F1F1F1; ">
                        <td style="padding:36px 30px 42px 30px;">
                            <table role="presentation"
                                style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                    <td style="padding:0 0 36px 0;color:#333;">
                                        <h4 style="font-size:24px;margin:0 0 20px 0;font-family: Poppins, sans-serif;">
                                            Hello! '.$user->company_name.'</h4>
                                        <p
                                            style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family: \'Poppins\', sans-serif;">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            Congratulations on the opening of your new business! We at <span style="font-weight: bold; color:green;">Vingreen Technologies </span>would like to extend our warmest
                                            greetings and best wishes for your success.</p>
                                        <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family: \'Poppins\', sans-serif;">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            As a software development and IT services company, we understand the importance of leveraging technology to help
                                            businesses thrive. That\'s why we would like to offer our services to you.
                                            </p>
                                     
                                    </td>
                                </tr>
                                <tr style=" padding: 16px; display:flex;align-items: center;justify-content: center;">
                                    <td style="padding:0;">
                                        <img src="https://vingreentech.com/assets/images/logos/vingreen_mails.png" alt="" width="100%"
                                            style="height:auto;display:block;" />
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr  style="background-color: green; color: white; "> 
                        <td>
                            <ul style="list-style-type: none; display: flex; justify-content: space-between;">
                                <li><p style="font-size: 14px;padding-right:4px;">Call:
                                    <a href="tel:+917305776555"   style="text-decoration: none;color: white; ">+91 7305776555</a></p></li>
                                <li><p style="font-size: 14px;padding: 0 4px;">Website:
                                    <a href="https://www.vingreentech.com" target="_blank" style="text-decoration: none;color: white; ">
                                        www.vingreentech.com</a></p> </li>
                                <li><p style="font-size: 14px;padding-left:4px;">Email:<a href="mailto:info@vingreentech.com" t  style="text-decoration: none;color: white; ">info@vingreentech.com</a></p> </li>  
                            </ul>
                        </td> 
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>';        
         $url = 'https://api.elasticemail.com/v2/email/send';
            try {
                $post = array(
                    'from' => 'vgtmails@gmail.com',
                    'fromName' => 'vgtmails@gmail.com',
                    'apikey' => '18C3473FDD9AD94E2E917A632BE2FBFE6A08326F8B7E51D6D35DA6F27451528731DF45C0D0A10F29651D9F382D47DEAE',
                    'subject' => $subject,
                    'to' => $to_mail_id,
                    'bodyHtml' => $mail_template,
                    'isTransactional' => false
                );
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => $url,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $post,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HEADER => false,
                    CURLOPT_SSL_VERIFYPEER => false
                ));
              echo  $result = curl_exec($ch);
                curl_close($ch);
            } catch (Exception $ex) {
                 $result = $ex->getMessage();

            }
        }

        }
    }

