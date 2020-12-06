<?php

function response($status,$status_message,$data)
{
	header("HTTP/1.1 ".$status);

	$response['status'] = $status;
	$response['status_message'] = $status_message;
	$response['data'] = $data;

	$json_response = json_encode($response);
	echo $json_response;
	exit;
}

function min2h($min)
{
  if ($min > 0) {
    $h = intval($min/60);
    $hour = ($h == 0) ? $min . 'min' : $h . 'h' . ($min - ($h*60)) . 'min';
    return $hour;
  }
  return '';
}
