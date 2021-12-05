<?php

echo '<h1> Client Certificate Login Test</h1>';

if (isset($_SERVER['SSL_CLIENT_VERIFY']) != 'NONE') 
{
  echo '<h2>Basic data from certificate</h2>';
  echo '<p>Success (SSL_CLIENT_VERIFY): ' . $_SERVER['SSL_CLIENT_VERIFY'] . '</p>';
  echo '<p>Serial_Number (SSL_CLIENT_M_SERIAL): ' . $_SERVER['SSL_CLIENT_M_SERIAL'] . '</p>';
  echo '<p>Start (SSL_CLIENT_V_START): ' . $_SERVER['SSL_CLIENT_V_START'] . '</p>';
  echo '<p>End (SSL_CLIENT_V_END): ' . $_SERVER['SSL_CLIENT_V_END'] . '</p>';
  echo '<p>Remaining days (SSL_CLIENT_V_REMAIN): ' . $_SERVER['SSL_CLIENT_V_REMAIN'] . '</p>';
  echo "<p>Issuer DN of client's certificate (SSL_CLIENT_I_DN): " . $_SERVER['SSL_CLIENT_I_DN'] . '</p>';
  echo "<p>Subject DN in client's certificate (SSL_CLIENT_S_DN): " . $_SERVER['SSL_CLIENT_S_DN'] . '</p>';
  
  if (hasValidCert()==true)
  {
    echo '<p style="color:green;">Valid certificate given.</p>';
    echo '<h3>Distinct name user</h3>';
    showDN($_SERVER['SSL_CLIENT_S_DN']);
    echo '<h3>Distinct name issuer</h3>';
    showDN($_SERVER['SSL_CLIENT_I_DN']);
  } else {
    echo '<p style="color:red;">No valid certificate given.</p>';
  }
} else {
  echo '<p style="color:red;">No certificate given.</p>';
}

echo '<p>If you want to use a different certificate you need to restart the browser.</p>';




function hasValidCert($strict=false)
    {
        //basic check
        if (!isset($_SERVER['SSL_CLIENT_M_SERIAL'])
            || !isset($_SERVER['SSL_CLIENT_V_END'])
            || !isset($_SERVER['SSL_CLIENT_VERIFY'])
            || !isset($_SERVER['SSL_CLIENT_I_DN'])
        ) {
            return false;
        }

        // check strict = true SSL_CLIENT_VERIFY must be SUCCESS
        if ($strict==true && $_SERVER['SSL_CLIENT_VERIFY'] !== 'SUCCESS')
        {
          return false;
        }

        if ($_SERVER['SSL_CLIENT_V_REMAIN'] <= 0) {
            return false;
        }

        $dtNow = new DateTime("now");
        $start = new DateTime($_SERVER['SSL_CLIENT_V_START']);
        if ($dtNow < $start)
        {
            return false;
        }

        $end = new DateTime($_SERVER['SSL_CLIENT_V_END']);
        if ($dtNow > $end)
        {
            return false;
        }
        return true;
    }

   function showDN($dn){
     $data = explode(',', $dn);
     foreach ($data as $entry) {
       $d = explode('=', $entry);
       echo '<b>' . $d[0] . '</b>: ' . $d[1] . '</br>';
     }
   }
?>