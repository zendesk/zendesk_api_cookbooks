<?php
/*************
** Example Written By: Ben Evans
*************/

include("vendor/autoload.php");
use Zendesk\API\Client as ZendeskAPI;
$subdomain = "";
$username = "";
$token = ""; // replace this with your token
$client = new ZendeskAPI($subdomain, $username);
$client->setAuth('token', $token); // set either token or password




$ticket_fields = $client->ticketFields()->findAll(); //assigns all ticket fields to $ticket_fields
echo "<pre>";
print_r($ticket_fields);
echo "\n\n--------------------------------------------------------------\n\n";
$new_ticket_field = $client->ticketFields()->create(array(
            'type' => 'text',
            'title' => 'Age'
        ));
print_r($new_ticket_field);
echo "</pre>";
$id_to_delete = $new_ticket_field->ticket_field->id; //Get the id for our newly created field.
$deleted_field = $client->ticketFields($id_to_delete)->delete(); //keep this example neat, delete example field.
?>