<?php

/**

 * @author Rickrods

 * @copyright 2020

 */

if (!defined('ABSPATH')) die();



function ds_ct_enqueue_parent() { wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' ); }



function ds_ct_loadjs() {

	wp_enqueue_script( 'ds-theme-script', get_stylesheet_directory_uri() . '/ds-script.js',

        array( 'jquery' )

    );

}



add_action( 'wp_enqueue_scripts', 'ds_ct_enqueue_parent' );

add_action( 'wp_enqueue_scripts', 'ds_ct_loadjs' );



include('login-editor.php');


/**

 * @author rickrods
 * @copyright 2020
 * 
 * Near Shortcodes
 */
function near_get_mainnet_seat_price()
{
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, 'http://monitor.crypto-solutions.net:9090/api/v1/query?query=near_seat_price');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);
    
    if (curl_errno($ch)) 
    {
        echo 'Error:' . curl_error($ch);
    }
    
    curl_close($ch);
    $price_array = json_decode($result, true);

    return $seat = $price_array["data"]["result"][0]["value"][1];

}
add_shortcode('near_main_seat', 'near_get_mainnet_seat_price');

function NEAR_mainnet_validator_status()
{
      $ch = curl_init();
      
      curl_setopt($ch, CURLOPT_URL, 'http://monitor.crypto-solutions.net:9090/api/v1/query?query=near_is_validator');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  
      $result = curl_exec($ch);
      
      if (curl_errno($ch)) 
      {
          echo 'Error:' . curl_error($ch);
      }
      
      curl_close($ch);
      $array = json_decode($result, true);
      $status = $array["data"]["result"][0]["value"][1];
      if ($status == 1) 
      {
         return "Yes";
      } 
      
      return "Validator Has 0 Seats";
  
  }

add_shortcode('near_val_status', 'NEAR_mainnet_validator_status');



function near_view_mainnet_account()
{
	// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'http://116.203.129.202:3030/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"id\":\"dontcare\",\"jsonrpc\":\"2.0\",\"method\":\"query\",\"params\":{\"request_type\":\"view_account\",\"finality\":\"final\",\"account_id\":\"cryptosolutions.poolv1.near\"}}");

$headers = array();
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

	$array = json_decode($result, true);
	$amount = $array['result']['amount'];
	// english notation without thousands separator
	return $near_format_number = number_format($amount, -24, '.', '');


}

add_shortcode('near_main_account', 'near_view_mainnet_account');

function near_test_visit(){
	   $url = "http://116.203.129.202:3030/status";
       $agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
	   $ch=curl_init();
       curl_setopt ($ch, CURLOPT_URL, $url );
       curl_setopt($ch, CURLOPT_USERAGENT, $agent);
       curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt ($ch,CURLOPT_VERBOSE,false);
       curl_setopt($ch, CURLOPT_TIMEOUT, 5);
       curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
       curl_setopt($ch,CURLOPT_SSLVERSION,3);
       curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
       $page=curl_exec($ch);
       //echo curl_error($ch);
       $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
       curl_close($ch);
	   $down = "NEAR Validator is DOWN";
	   $up = "NEAR Validator is UP";
       if($httpcode == 200 && $httpcode < 300) return $up;
       else return $down;
}
add_shortcode('near_validator_status', 'near_test_visit');
?>