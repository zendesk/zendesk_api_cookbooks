<h4>Groups</h4>
<form action="groups-endpoint.php" method="POST">
    <select name="form">
        <option value="listGroups">List All Groups</option>
        <option value="showGroup">Show Group</option>
        <option value="createGroup">Create a New Group</option>
        <option value="updateGroup">Update a Group</option>
        <option value="deleteGroup">Delete a Group</option>
        <option value="assignableGroups">Show assignable Groups</option>
        <option value="usersGroups">Show Users Groups</option>
    </select></br>
    <input type="text" name="id" placeholder="Group or User ID"/></br>
    <input type="text" name="name" placeholder="Group Name"/></br>
    <button type="submit">Submit</button></br>
</form>
<?php

include("vendor/autoload.php");

use Zendesk\API\Client as ZendeskAPI;

$subdomain = "";
$username = "";
$token = ""; // replace this with your token

$client = new ZendeskAPI($subdomain, $username);
$client->setAuth('token', $token); // set either token or password

$array = array();

//Print output of various requests
function print_me($content) {
    echo "<pre>";
    print_r($content);
    echo "</pre>";
}

//Used to quickly create a test object
function create_test(){
    global $client;
    return $client->groups()->create(array('name' => 'group+'.mt_rand(5, 150000)));
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    switch ($_REQUEST['form']){

    // Get all Groups   
        case "listGroups":
            print_me($client->groups()->findAll());
            break;
    
    // Get assignable Groups    
        case "assignableGroups":
            print_me($client->groups()->findAll(array('assignable' => true)));
            break;
    
    // Get individual user's Groups
        case "usersGroups":
            print_me($client->groups()->findAll(array('user_id' => $_REQUEST['id'])));
            break;
    
    // Get group by user-provided ID, or create a group and return it if no ID provided
        case "showGroup":
            if (!empty($_REQUEST['id'])) print_me($client->groups()->find($_REQUEST));
            else print_me($client->groups()->find(array('id' => create_test()->group->id)));
            break;
    
    // Delete individual by ID provided or fail if none provided
        case "deleteGroup":
            if(!empty($_REQUEST['id'])) ($client->groups()->delete(array("id"=>$_REQUEST['id']))) ? print_me("Success") : print_me("There was an error. Please try again.");
            else echo "Please enter an ID to delete";
            break;
    
    // Create a new group with user input or create example if no values provided for the required field - name 
        case "createGroup":
            (!empty($_REQUEST['name'])) ? print_me($client->groups()->create($_REQUEST)) : print_me(create_test());
            break;
        
    // update a group via user input, fail if no value provided for the required field - id
        case "updateGroup":
            (!empty($_REQUEST['id'])) ? print_me($client->groups()->update($_REQUEST)) :  print_me("Please add ID");
            break;
    }
}

?>
