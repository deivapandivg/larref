<?php

if (!function_exists(function:'getChild')) {

	function getChild($UserId)
	{
		$GetUsers= DB::table('users')->select('*')->where('deleted','No')->orderby('id')->get();
		$UsersCount = $GetUsers->count();
		$getSubId= DB::table('users')->select('id')->where('deleted','No')->where('reporting_to_id','=',$UserId)->get();
	}
}

if (!function_exists(function:'CheckIsNull')) {
	function CheckIsNull($Data)
	{
	   if(is_null($Data))
	   {
	      return "";
	   }
	   else
	   {
	      return $Data;
	   }
	}
}

if (!function_exists(function:'SendLeadAssignNotificationToMobile')) {

	function SendLeadAssignNotificationToMobile($UserId,$LeadId)
	{
	   $NotificationFor="Lead Assigned";

	   	$GetUsers= DB::table('users')->select('*')->where('deleted','No')->where('id',$UserId)->orderby('id')->first();
	   	$UserName = $GetUsers->first_name;
	   	$ServerKey = $GetUsers->server_key;

	   	$GetLeads=DB::table('leads')->select('*')->where('deleted','No')->where('lead_id',$LeadId)->first();
		$LeadName=$GetLeads->lead_name;
	 	$message_title="Wow, New Lead Assigned To You";
	   	$message_body="Dear $UserName, You got new lead. LeadId: $LeadId, Lead Name: $LeadName.";

	   
	   if($ServerKey!='')
	   {
	      $user_fcm_token=$ServerKey;
	      $fcm_server_key="AAAApjgcNGA:APA91bEwakUxsPGIqbisPhFayejiEk8cRKqPNNmkMRD0DhH7vsSvkqQwYKJpY2Rsjy-Mp7LNrZPBh1w18qtcYBJvHv1NxBOa4uQ_A3s7d69vaU-LvLA_tywFaYtpymsJ1LkVpbAv2OEK";
	      $curl = curl_init();
	      curl_setopt_array($curl, array(
	        CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_ENCODING => '',
	        CURLOPT_MAXREDIRS => 10,
	        CURLOPT_TIMEOUT => 0,
	        CURLOPT_FOLLOWLOCATION => true,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	        CURLOPT_CUSTOMREQUEST => 'POST',
	        CURLOPT_POSTFIELDS =>'{"notification": {"body": "'.$message_body.'","title": "'.$message_title.'"}, "priority": "high", "data": {"leadId":"$LeadId","leadName":"$LeadName"},"click_action":"FLUTTER_NOTIFICATION_CLICK", "to": "'.$user_fcm_token.'"}',
	        CURLOPT_HTTPHEADER => array(
	          'Content-Type:  application/json',
	          'Authorization:  key='.$fcm_server_key
	        ),
	      ));
	      $response = curl_exec($curl);
	      curl_close($curl);
	   }
	   else
	   {
	      $response="Notification Not Send Because Of User don't have a key";
	   }
	   echo $response;
	   $createdby=Auth::id();
	    $data = array( 
            'notification_for' => $NotificationFor,
            'lead_id' => $LeadId,
            'sending_response' => $response,
            'created_by' => $createdby,
            'message_body' => $message_body,
            'created_at' => NOW()
        );

	   $InsertMobileNotification=DB::table('mobile_push_notification_log')->insert($data);
	}
}



?>