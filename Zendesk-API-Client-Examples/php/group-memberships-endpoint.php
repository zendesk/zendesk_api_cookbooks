<!-- Should add sideloads to this endpoint -->

<h4>Group Memberships</h4>
<form action="group-memberships-endpoint.php" method="POST">
    <select name="form">
        <option value="listMemberships">List All Group Memberships</option>
        <option value="showMembership">Show Group Membership</option>
        <option value="createMembership">Create a New Group Membership</option>
        <option value="deleteMembership">Delete a Group Membership</option>
        <option value="assignableMemberships">Show assignable Group Memberships</option>
        <option value="groupsAssignableMemberships">Show a group's assignable Memberships</option>
        <option value="usersMemberships">Show a user's Group Memberships</option>
        <option value="groupsMemberships">Show a groups's Group Memberships</option>
        <option value="setDefault">Set Membership as Default</option>
    </select></br>
    <input type="text" name="membership_id" placeholder="Group Membership"/></br>
    <input type="text" name="user_id" placeholder="User ID"/></br>
    <input type="text" name="group_id" placeholder="Group ID"/></br>
    <input type="checkbox" name="default" value="true">Make this the default?</br>
    <button type="submit">Submit</button></br>
</form>

<?php

include("vendor/autoload.php");

use Zendesk\API\Client as ZendeskAPI;

$subdomain = "z3nburmaglot";
$username = "japeterson@zendesk.com";
$token = "FsD6L6pHGSsoHFctgl0HsPVATjEepNRHEwr2zycl"; // replace this with your token

$client = new ZendeskAPI($subdomain, $username);
$client->setAuth('token', $token); // set token authentication

//Print output of various requests
function print_me($content) {
    echo "<pre>";
    print_r($content);
    echo "</pre>";
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    switch ($_REQUEST['form']){
        
        // Get all Group Memberships    
        case "listMemberships":
            print_me($client->groupMemberships()->findAll());
            break;
            
        // Get individual Group Membership by membership_id provided, or show an existing membership at random  
        case "showMembership":
            if (!empty($_REQUEST['membership_id'])) {
                try {
                    print_me($client->groupMemberships()->find(array('id' => $_REQUEST['membership_id'])));
                } catch(Exception $e) {
                    print_me("There was an error, please enter a valid Membership ID");
                }
            }
            else {
                $random_array = $client->groupMemberships()->findAll()->group_memberships;
                shuffle($random_array);
                print_me($random_array[0]);
            }
            break;
            
        // Get individual user's Group memberships by user_id provided, or select a user at random.
        case "usersMemberships":
            if (!empty($_REQUEST['user_id'])){
                try {
                    print_me($client->groupMemberships()->findAll(array('user_id' => $_REQUEST['user_id'])));
                } catch(Exception $e) {
                    print_me("There was an error, please enter a valid User ID");
                }   
            }
            else {
                $random_array = $client->groupMemberships()->findAll()->group_memberships;
                shuffle($random_array);
                $random_userid = $random_array[0]->user_id;
                print_me($client->groupMemberships()->findAll(array('user_id' => $random_userid)));
            }
            break;
            
        // Get given group's Group Memberships by group_id provided, or select a group at random.
        case "groupsMemberships":
            if (!empty($_REQUEST['group_id'])){
                try {
                    print_me($client->groupMemberships()->findAll(array('group_id' => $_REQUEST['group_id'])));
                } catch(Exception $e) {
                    print_me("There was an error, please enter a valid Group ID");
                }
            }
            else {
                $random_array = $client->groupMemberships()->findAll()->group_memberships;
                shuffle($random_array);
                $random_groupid = $random_array[0]->group_id;
                print_me($client->groupMemberships()->findAll(array('group_id' => $random_groupid)));
            }
            break;
            
        // Create a new group with user input or fail if valid user_id and group_id are not provided    
        case "createMembership":
            if (!empty($_REQUEST['user_id']) && !empty($_REQUEST['group_id'])) {
                try {
                    print_me($client->groupMemberships()->create($_REQUEST));
                } catch (Exception $e) {
                    echo "Please provide a valid User ID and Group ID";
                }  
            } else {
                echo "Please provide a User ID and Group ID";
            }
            break;
            
        // Delete individual membership by membership_id provided, or fail if none provided
        case "deleteMembership":
            if(!empty($_REQUEST['membership_id'])) 
                try {
                    $client->groupMemberships()->delete(array('membership_id' => $_REQUEST['membership_id']));
                    print_me("Success");
                } catch (Exception $e) {
                    print_me("There was an error, please enter a valid Membership ID.");
                }
            else {
                echo "Please enter a Membership ID to delete";
            }
            break;
            
        // Show all assignable group Memberships
        case "assignableMemberships":   
            print_me($client->groupMemberships()->findAll(array('assignable' => true)));
            break;
            
        // Show all assignable group Memberships by group_id provided, or a random group will be used
        case "groupsAssignableMemberships":
            if (!empty($_REQUEST['group_id'])){
                try {
                    print_me($client->groupMemberships()->findAll(array('group_id' => $_REQUEST['group_id'], 'assignable' => true)));
                } catch(Exception $e) {
                    print_me("There was an error, please enter a valid Group ID");
                }
            }
            else {
                $random_array = $client->groupMemberships()->findAll()->group_memberships;
                shuffle($random_array);
                $random_groupid = $random_array[0]->group_id;
                print_me($client->groupMemberships()->findAll(array('group_id' => $random_groupid, 'assignable' => true)));
            }
            break;
            
        // set user's default group membership to membership_id provided, or will fail if no user_id nor membership_id is provided
        case "setDefault":
            if (!empty($_REQUEST['user_id']) && !empty($_REQUEST['membership_id'])) {
                try {
                    print_me($client->groupMemberships()->makeDefault(array('user_id'=> $_REQUEST['user_id'], 'id' => $_REQUEST['membership_id'])));
                } catch (Exception $e) {
                    print_me("There was an error, please enter a valid User and Membership ID.");
                }
            } else {
                echo "please add User ID and Membership ID you wish to make default";
            }
            break;
    }
}

?>