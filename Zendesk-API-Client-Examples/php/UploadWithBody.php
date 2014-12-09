/**
 * Attachments sample for upload_with_body
 */
<?php
 include("vendor/autoload.php");

  use Zendesk\API\Client as ZendeskAPI;

  $subdomain = "";
  $username = "";
  $token = ""; // replace this with your token

  $client = new ZendeskAPI($subdomain, $username);
  $client->setAuth('token', $token); // set either token or password

  $data = file_get_contents("./test.png");
  $attachment = $client->attachments()->uploadWithBody(array(
            'body' => $data,
            'type' => 'image/png',
            'name' => 'test1.png' // rename file to test1.png
        ));
  print_r($attachment);
?>
