<?php
$current_date = date("Y-m-d H:i:s");
echo "<h1>Date is $current_date</h1>";
?>

<p>The volume works like below.</p>

<ul>
    <li>Host machine: sample_app folder</li>
    <li>↓</li>
    <li>nginx: /var/www/php</li>
</ul>

<p>So, change the path of "root" directive in nginx/conf.d/php.conf to match the structure of "sample_app" folder on the Host machine.</p>

