<?

// Setup array with parameters
$arrParams = array();
$arrParams['programId'] = $_POST['programId'];
$arrParams['websiteId'] = $_POST['websiteId'];
$arrParams['ipAddress'] = $_SERVER['REMOTE_ADDR'];
$arrParams['websiteLocationId'] = $_POST['websiteLocationId'];
$arrParams['extra1'] = $_POST['orderID'];
$arrParams['extra2'] = $_POST['language_id'];
$arrParams['extra3'] = $_POST['EMAIL'];
$arrParams['amount'] = $_POST['amount'];

// Setup API Url
$strUrl = "https://rest-api.pay.nl/v1/Session/create/array_serialize/";

// Prepare complete API URL
$strUrl = prepareHttpGet($strUrl, $arrParams);

// Do request
$arrResult = unserialize(@url_get_contents($strUrl));

if(is_array($arrResult) && intval($arrResult['result']) > 0)
{
  header("Location: https://www.pay.nl/betalen/?payment_session_id=".$arrResult['result']);
}
else
{
  echo "Error in generating Session";
}

function prepareHttpGet($strUrl, array $arrParams)
{
  $first = 1;

  // Prepare query string
  foreach ($arrParams as $key => $value)
  {
        if ($first != 1)
        {
          $strUrl = $strUrl . "&";
        }
        else
        {
          $strUrl = $strUrl . "?";
          $first = 0;
        }

        if(is_array($value))
        {
          foreach ($value as $k => $v)
          {
                $strUrl = $strUrl . $key."[".$k ."]=" . $v;
          }
          continue;
        }

        // Add item to string
        $strUrl = $strUrl . $key ."=" . $value;
  }

  return $strUrl;
}

function url_get_contents($strUrl)
{
  if (function_exists('curl_init'))
  {
    // use libcurl
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_URL,$strUrl);

    $data = curl_exec($curl);
  }
  else
  {
    // Try file_get_contents
    $context = stream_context_create(
    array(
    'http' => array(
    'timeout' => 10 // Timeout in seconds
    )
    ));
    $data = file_get_contents($strUrl,0,$context);
  }

  if (strlen($data) > 0)
  {
    return $data;
  }
  
  return false;
}

?>