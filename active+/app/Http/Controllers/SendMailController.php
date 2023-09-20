<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use DateTime;
use Illuminate\Support\Facades\Auth;
use App\Models\UserModel;



class SendMailController extends Controller
{

  public function __construct()
   {
      $this->middleware('auth');
   }

  public function send_mail($mail_type, $data)
    {
        // dd($data);
        $mail_content = $to_mail_id = $subject = "";
         if($mail_type=="Welcome_mail")
        {
            $to_mail_id=$data['to_mail_id'];
            $subject=$data['subject'];
            $first_name=$data['first_name'];
            $mail_content='<h3>Dear '.$first_name.'</h3> 
            <p>We are all really excited to welcome you to our family. </p><br>
            
            <p>Regards,<br>
           Vingreen Technologies</p>';
        }
        elseif($mail_type=='Login_OTP')
        {
            $to_mail_id=$data['to_mail_id'];
            $subject=$data['subject'];
            $otp=$data['otp'];
            $mail_content='<h3>Hello !</h3> 
            <p>Your Login Otp '.$otp.'</p><br>
            
            <p>Regards,<br>
           Vingreen Technologies</p>';
        }
        elseif($mail_type=='quotation')
        {
            $to_mail_id=$data['to_mail_id'];
            $subject=$data['subject'];
            $client_name=$data['client_name'];
            $quotation_id=$data['quotation_id'];
            $date_issue=$data['date_issue'];
            $grand_total=$data['grand_total'];
            $download_url=$data['download_url'];
            $mail_content='<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                <tr>
                    <td style="padding:0 0 36px 0;color:#153643;">
                        <h3 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">Hi '.$client_name.',</h3>
                        <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Thanks For Your Bussiness!</p>
                        <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><span style="">Quotation Id : <b>'.$quotation_id.'</b></span></p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0;">
                        <table>
                            <thead>
                                <tr>
                                    <th style="font-size 15px;">Date </th>
                                    <td></td>
                                    <td style="font-size 15px;">: '.$date_issue.'</td>
                                </tr>
                                <tr>
                                    <th style="font-size 15px;">Grand Total </th>
                                    <td></td>
                                    <td style="font-size 15px;">: '.$grand_total.'.00</td>
                                </tr>
                            </thead>
                        </table>
                    </td>
                </tr>
                 <a href='.$download_url.'><h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">Download Pdf</h2></a>
                <tr>
                    <td style="padding:0 0 36px 0;color:#153643;">
                        <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;"></h1>
                        <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Notice something wrong? contact our support team and Will be happy to help  <b>deivavingreen@gmail.com</b> or whats app us at 8940645050.</p>
                        <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"></p>
                    </td>
                </tr>
            </table>';
        }

         elseif($mail_type=='invoice')
        {
            $to_mail_id=$data['to_mail_id'];
            $subject=$data['subject'];
            $client_name=$data['client_name'];
            $invoice_id=$data['invoice_id'];
            $date_issue=$data['date_issue'];
            $grand_total=$data['grand_total'];
            $download_url=$data['download_url'];
            $mail_content='<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                <tr>
                    <td style="padding:0 0 36px 0;color:#153643;">
                        <h3 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">Hi '.$client_name.',</h3>
                        <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Thanks For Your Bussiness!</p>
                        <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><span style="">Invoice Id : <b>'.$invoice_id.'</b></span></p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0;">
                        <table>
                            <thead>
                                <tr>
                                    <th style="font-size 15px;">Date </th>
                                    <td></td>
                                    <td style="font-size 15px;">: '.$date_issue.'</td>
                                </tr>
                                <tr>
                                    <th style="font-size 15px;">Grand Total </th>
                                    <td></td>
                                    <td style="font-size 15px;">: '.$grand_total.'.00</td>
                                </tr>
                            </thead>
                        </table>
                    </td>
                </tr>
                 <a href='.$download_url.'><h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">Download Pdf</h2></a>
                <tr>
                    <td style="padding:0 0 36px 0;color:#153643;">
                        <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;"></h1>
                        <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Notice something wrong? contact our support team and Will be happy to help  <b>deivavingreen@gmail.com</b> or whats app us at 8940645050.</p>
                        <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"></p>
                    </td>
                </tr>
            </table>';
        }
        elseif($mail_type=='proforma_invoice')
        {
            $to_mail_id=$data['to_mail_id'];
            $subject=$data['subject'];
            $client_name=$data['client_name'];
            $proforma_invoice_id=$data['proforma_invoice_id'];
            $date_issue=$data['date_issue'];
            $grand_total=$data['grand_total'];
            $download_url=$data['download_url'];
            $mail_content='<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                <tr>
                    <td style="padding:0 0 36px 0;color:#153643;">
                        <h3 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">Hi '.$client_name.',</h3>
                        <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Thanks For Your Bussiness!</p>
                        <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><span style="">Invoice Id : <b>'.$proforma_invoice_id.'</b></span></p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0;">
                        <table>
                            <thead>
                                <tr>
                                    <th style="font-size 15px;">Date </th>
                                    <td></td>
                                    <td style="font-size 15px;">: '.$date_issue.'</td>
                                </tr>
                                <tr>
                                    <th style="font-size 15px;">Grand Total </th>
                                    <td></td>
                                    <td style="font-size 15px;">: '.$grand_total.'.00</td>
                                </tr>
                            </thead>
                        </table>
                    </td>
                </tr>
                 <a href='.$download_url.'><h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">Download Pdf</h2></a>
                <tr>
                    <td style="padding:0 0 36px 0;color:#153643;">
                        <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;"></h1>
                        <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Notice something wrong? contact our support team and Will be happy to help  <b>deivavingreen@gmail.com</b> or whats app us at 8940645050.</p>
                        <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"></p>
                    </td>
                </tr>
            </table>';
        }
        elseif($mail_type=='report'){
            $today_date = date('Y-m-d');
            $to_mail_id=$data['to_mail_id'];
            $subject=$data['subject'];
            $mail_content='<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                <tr>
                    <td style="padding:0 0 36px 0;color:#153643;">
                        <h3 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">Hi Admin,</h3>
                        <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Employees Daily Performance Report !</p>
                        <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><span style="">Date : <b>'.$today_date.'</b></span></p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0;">
                        <table style="width:100%;border:1px solid #435784;border-radius:3px;">
                            <thead style="border:1px solid #e6ebf4;background:#0e4094;color:#ffffff;">
                                <tr style="text-align:center;border:1px solid #e6ebf4;">
                                    <th style="border:1px solid #e6ebf4;width:25%;font-size 15px;text-align:left;">Employees </th>
                                    <th style="border:1px solid #e6ebf4;width:25%;font-size 15px;text-align:center;">Timelines</th>
                                    <th style="border:1px solid #e6ebf4;width:25%;font-size 15px;text-align:center;">Lead Conversions</th>
                                </tr>
                            </thead>
                            <tbody>';
                                $user_details = DB::table('users')->where('deleted', 'No')->get();

                                foreach($user_details as $user_detail){
    
                                    $timeline_counts_results = DB::table('timelines as a')->where('a.created_by', $user_detail->id)->where(DB::raw("(DATE_FORMAT(a.created_at,'%Y-%m-%d'))"), "=", $today_date)->count('a.timeline_id');

                                    $lead_conversion_counts_results = DB::table('leads as a')->where('a.updated_by', $user_detail->id)->where('a.lead_sub_stage_id', '=', 13)->where(DB::raw("(DATE_FORMAT(a.updated_at,'%Y-%m-%d'))"), "=", $today_date)->count('a.lead_id');
                                    
                                    $mail_content.='<tr style="background:#e6ebf4;color:#000000;">
                                        <td style="text-align:left;border:1px solid #e6ebf4;width:25%;">'.$user_detail->first_name.'</td>
                                        
                                        <td style="text-align:center;border:1px solid #e6ebf4;width:25%;padding-left:15px;">'.$timeline_counts_results.'</td>
                                        
                                        <td style="text-align:center;border:1px solid #e6ebf4;width:25%;padding-left:15px;">'.$lead_conversion_counts_results.'</td>
                                    </tr>';
                                }
                            $mail_content.='</tbody>
                        </table>
                    </td>
                </tr>
            </table>';
        }
        elseif($mail_type=='job_queue'){
            $today_date = date('Y-m-d');
            $to_mail_id=$data['to_mail_id'];
            $subject=$data['subject'];
            $mail_content='<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                <tr>
                    <td style="padding:0 0 36px 0;color:#153643;">
                        <h3 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">Hi Admin,</h3>
                        <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Employees Daily Performance Report !</p>
                        <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><span style="">Date : <b>'.$today_date.'</b></span></p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0;">
                        <p>Job Queue Testing</p>
                    </td>
                </tr>
            </table>';
        } 
        if ($to_mail_id != "") {
            $mail_template ='<!DOCTYPE html>
            <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width,initial-scale=1">
                <meta name="x-apple-disable-message-reformatting">
                <title></title>
            
                <style>
                    table, td, div, h1, p {font-family: Arial, sans-serif;}
                </style>
            </head>
            <body style="margin:0;padding:0;">
                <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
                    <tr>
                        <td align="center" style="padding:0;">
                            <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                                <tr>
                                    <td style="padding:10px 0 10px 0;background:#fff; margin-left: 15%; !important">
                                        <img src="https://vingreentech.com/assets/images/logos/green_logo.png" alt="" width="150" style="height:auto;display:block;" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:36px 30px 42px 30px;">
                                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                            <tr>
                                                <td style="padding:0 0 36px 0;color:#153643;">
                                                    ' . $mail_content . '
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <p style="margin-left: 5%;font-size:0.9em;">Regards,<br />VinGreen Technologies</p>
                                <hr style="border:none;border-top:1px solid #eee" />
                            </table>
                        </td>
                    </tr>
                </table>
            </body>
            </html>';

            $url = 'https://api.elasticemail.com/v2/email/send';
            try {
                // $post = array(
                //     'from' => 'vgtmails@gmail.com',
                //     'fromName' => 'Vingreen Technologies',
                //     'apikey' => '18C3473FDD9AD94E2E917A632BE2FBFE6A08326F8B7E51D6D35DA6F27451528731DF45C0D0A10F29651D9F382D47DEAE',
                //     'subject' => $subject,
                //     'to' => $to_mail_id,
                //     'bodyHtml' => $mail_template,
                //     'isTransactional' => false
                // );
                $post = array(
                    'from' => 'scopeingredientsspecialist@gmail.com',
                    'fromName' => 'Scope Ingredients Specialist',
                    'apikey' => '1B63633268036162FA028B1B17A43873FFA10D8690E3F565BF249BC48546290BC1A148A8925FB8F9633E2CAF1974FB7A',
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
                $result = curl_exec($ch);
                curl_close($ch);
            } catch (Exception $ex) {
               echo $result = $ex->getMessage();
            }

            $mail_log = array('from_mail_id' => 'vgtmails@gmail.com', 'to_mail_id' => $to_mail_id, 'subject' => $subject, 'mail_type'=> $mail_type,'mail_send_user_id' => '1', 'mail_response' => $result, 'created_at' => now());
            $mail_log_insert_query = DB::table('send_mail_log')->insert($mail_log);
        }

    }

    public function SendMailSMTP($subject, $email ,$MailContent){

        $Post = [
          "subject"=>$subject,
          "email"=>$email,
          "comments"=>$MailContent, 
       ];

       // $URL="http://192.168.1.7/l9/active+/send_mail_smtp";
   
       $this->send_mail_smtp($Post);
    }

    public function send_mail_smtp($Post){
        // dd($Post);

        $FromEmail='vgtmails@gmail.com';
        // $MailContent= 'hello';
        // $ToMailId= 'harishvingreen@gmail.com';
        // $Subject= 'Testing';

        if($FromEmail==''){$FromEmail='vgtmails@gmail.com';}
        if(isset($Post['comments'])){$MailContent=$Post['comments'];}else{$MailContent="";}
        if(isset($Post['email'])){$ToMailId=$Post['email'];}else{$ToMailId="selvakumarvingreen@gmail.com";}
        if(isset($Post['subject'])){$Subject=$Post['subject'];}else{$Subject="";}
        // dd($MailContent);
        if($Subject!="" AND $ToMailId!="" AND $MailContent!="")
        {
            $url = 'https://api.elasticemail.com/v2/email/send';
           
            try{
                $post = array('from' => $FromEmail,
                    'fromName' => 'VGTMails',
                    'apikey' => '18C3473FDD9AD94E2E917A632BE2FBFE6A08326F8B7E51D6D35DA6F27451528731DF45C0D0A10F29651D9F382D47DEAE',
                    'subject' => $Subject,
                    'to' => $ToMailId,
                    'bodyHtml' => $MailContent,
                    'isTransactional' => false);
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => $url,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $post,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HEADER => false,
                    CURLOPT_SSL_VERIFYPEER => false
                ));
                $result=curl_exec ($ch);
                curl_close ($ch);
                echo $result;   
            }
            catch(Exception $ex){
                echo $result=$ex->getMessage();
            }
        }
    }

   //  public function curl_post($URL,$Post)
   // {
   //  // dd($URL);
   //      $ch = curl_init($URL);
   //      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   //      curl_setopt($ch, CURLOPT_POSTFIELDS, $Post);
   //      return $response = curl_exec($ch);
   //      curl_close($ch);
   //  }
}
