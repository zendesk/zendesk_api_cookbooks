/**
 * Attachments sample code for upload_with_body
 * (note need to hardcode working ticket id)
 */
<?php
 include("vendor/autoload.php");

  use Zendesk\API\Client as ZendeskAPI;

  $subdomain = "";
  $username = "";
  $token = ""; // replace this with your token

  $client = new ZendeskAPI($subdomain, $username);
  $client->setAuth('token', $token); // set either token or password

  try {
    // upload attachment
    $data = file_get_contents("./test.png");
    $attachment = $client->attachments()->uploadWithBody(array(
            'body' => $data,
            'type' => 'image/png',
            'name' => 'test.png' // rename file to test1.png
        ));
    $a = get_object_vars(reset($attachment));
    $token = $a['token'];

    // update ticket and attach attachment (ticket id hardcoded to 20 for testing)
    $ticket_update = $client->tickets()->update(array('id'=> 20, 
      'comment' => array(
        'body' => "comment required for upload",
        'uploads' => array($token),
      )));
    echo("Done!\n");
  } catch (Exception $e) {
    $error = ("---Caught Exception---\n\n" . $e . "\nWhile attempting to create attachment\n");
    echo($error);
  }

?>

