<?php
function show_header($title = "Hype") {
    echo '<!DOCTYPE html><html><head><title>'.$title.'</title>
    <style>
      body {font-family: Arial, sans-serif; background: #181818; color: #eee; margin:0;}
      .banner {display: block; width: 100%; max-width: 900px; margin: 20px auto 30px auto; border-radius: 16px; box-shadow: 0 4px 32px #090a;}
      .container {background: #242526; border-radius: 15px; max-width: 400px; margin: auto; padding: 30px; box-shadow: 0 2px 12px #2229;}
      label {display:block; margin:18px 0 6px 0;}
      input[type=text],input[type=password],input[type=email] {
        width: 95%; padding: 8px; border-radius: 6px; border: 1px solid #3ed; margin-bottom: 15px;
      }
      input[type=submit]{padding:10px 34px;background-color:#19fb91;color:#fff;border-radius:10px;border:none;font-weight:bold;cursor:pointer;transition:background 0.3s;}
      input[type=submit]:hover{background:#14f58c;}
      .error {color:#ff4a4a;padding:8px;}
      .success {color:#19fb91;padding:8px;}
      ul {list-style: none; padding: 0;}
      li {margin: 7px 0; background: #222; padding: 6px 14px; border-radius:8px;}
      a {color: #19fb91;}
    </style>
    <script>
      function lightenBanner() {
        document.querySelector(\'.banner\').style.boxShadow = \'0 8px 40px #19fb91\';
      }
    </script>
    </head><body onload="lightenBanner()">
    <img src="CBC4A1AD-3BB3-4F79-88F4-4061003D0C74.png" class="banner" alt="Hype Banner">
    <div class="container">';
}
?>
