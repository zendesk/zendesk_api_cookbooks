<h4>Organizations</h4>
<form action="organizations-endpoint.php" method="POST">
    <select name="form">
        <option value="listOrganizations">List All Organizations</option>
        <option value="usersOrganizations">Show a user's Organizations</option>
        <option value="autocomplete">Autocomplete Organizations</option>
        <option value="relatedInformation">Show Organization's related information</option>
        <option value="showOrganization">Show an Organization by ID</option>
        <option value="createOrganization">Create a New Organization</option>
        <option value="createManyOrganizations">Create multiple New Organizations</option>
        <option value="updateOrganization">Update an Organization</option>
        <option value="deleteOrganization">Delete an Organization</option>
        <option value="searchByExternalID">Search Organizations by External ID</option>
    </select></br>
    <input type="number" name="organization_id" placeholder="Organization ID"/></br>
    <input type="number" name="user_id" placeholder="User ID"/></br>
    <input type="text" name="external_id" placeholder="Organization External ID"/></br>
    <input type="text" name="name" placeholder="Organization Name"/></br>
    <input type="text" name="domain_names" placeholder="Organization Domain Names"/> **This requires a comma separated list with no spaces**</br>
    <input type="text" name="details" placeholder="Organization Details"/></br>
    <input type="text" name="notes" placeholder="Organization Notes"/></br>
    <input type="number" name="group_id" placeholder="Default Group ID associated with organization"/></br>
    <input type="checkbox" name="shared_tickets" value="true">Should end users in the organization be able to see each other's tickets?</br>
    <input type="checkbox" name="shared_comments" value="true">Should end users in the organization be able to see each other's comments?</br>
    <input type="text" name="tags" placeholder="Organizations tags"/> **This requires a comma separated list with no spaces** </br>
    <input type="text" name="custom_organization_fields" placeholder="Customer Organization Fields"/> **This requires that the fields exist prior to creating the org, and that the input be formatted like below**</br>
    {"org_field_key": "org_field_value", "org_field_key_2": "org_field_value_2"}'</br>

    <input type="checkbox" name="sideload" value="true">Set Sideload for Abilities</br>
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

// optional sideload for get requests
$sideload = (isset($_REQUEST['sideload'])) ? array("abilities") : array();

//Print output of various requests
function print_me($content) {
    echo "<pre>";
    print_r($content);
    echo "</pre>";
}

function select_random() {
    global $client;
    $array = $client->organizations()->findAll()->organizations;
    shuffle($array);
    return $array[0]->id;
}

function create_test() {
    global $client;
    $rand = mt_rand(5, 150000);
    return $client->organizations()->create(array(
        'external_id' => 'abcde'.$rand,
        'name' => 'organization #'.$rand,
        'details' => 'organization lives at '.$rand.' lane',
        'domain_names' => 'example'.$rand.'.org',
        'notes' => 'organization created by PHP',
        'shared_tickets' => true,
        'shared_comments' => true,
        'tags' => array('tag_'.$rand, 'organization_by_php') 
    ));
}

// Gather org fields for update, create, and create_many
$organization = array();
if (!empty($_REQUEST['organization_id'])) $organization['id'] = $_REQUEST['organization_id'];
if (!empty($_REQUEST['name'])) $organization['name'] = $_REQUEST['name'];
if (!empty($_REQUEST['external_id'])) $organization['external_id'] = $_REQUEST['external_id'];
if (!empty($_REQUEST['domain_names'])) $organization['domain_names'] = explode(',', $_REQUEST['domain_names']);
if (!empty($_REQUEST['details'])) $organization['details'] = $_REQUEST['details'];
if (!empty($_REQUEST['notes'])) $organization['notes'] = $_REQUEST['notes'];
if (!empty($_REQUEST['group_id'])) $organization['group_id'] = $_REQUEST['group_id'];
if (!empty($_REQUEST['shared_tickets'])) $organization['shared_tickets'] = $_REQUEST['shared_tickets'];
if (!empty($_REQUEST['shared_comments'])) $organization['shared_comments'] = $_REQUEST['shared_comments'];
if (!empty($_REQUEST['tags'])) $organization['tags'] = explode(',', $_REQUEST['tags']);
if (!empty($_REQUEST['custom_organization_fields'])) $organization['organization_fields'] = json_decode($_REQUEST['custom_organization_fields']) ;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    switch ($_REQUEST['form']){
        // Get all Organizations    
        case "listOrganizations":
            print_me($client->organizations()->findAll(array('sideload' => $sideload)));
            break;
        // Get individual user's Organizations by user_id provided, or fail if none provided
        case "usersOrganizations":
            if (!empty($_REQUEST['user_id'])){
                try {
                    print_me($client->organizations()->findAll(array('user_id' => $_REQUEST['user_id'], 'sideload' => $sideload)));
                } catch(Exception $e) {
                    echo "There was an error, please enter a valid User ID";
                }   
            } else {
                echo "please enter a user ID to search for organizations";
            }
            break;      
        // Get organizations whose name starts with the value specified in the name parameter. The name must be at least 2 characters in length.    
        case "autocomplete":
            if (!empty($_REQUEST['name'])) {
                try {
                    print_me($client->organizations()->autocomplete(array('name' => $_REQUEST['name'])));
                } catch(Exception $e) {
                    echo "There was an error, please enter a valid Organization Name";
                }
            }
            break;
        // Get given organization's Related Information by organization_id provided, or select an organization at random.
        case "relatedInformation":
            if (!empty($_REQUEST['organization_id'])){
                try {
                    print_me($client->organizations()->related(array("id" => $_REQUEST['organization_id'])));
                } catch (Exception $e) {
                    echo "Please provide a valid Organization ID";
                }  
            } else {
                $var = select_random();
                print_me($client->organizations()->related(array('id' => $var)));
            }
            break;
        // Show Organization by organization_id provided, or select at random if none provided. 
        case "showOrganization":
            if (!empty($_REQUEST['organization_id'])) {
                try {
                    print_me($client->organizations()->find(array('id' => $_REQUEST['organization_id'], 'sideload' => $sideload)));
                } catch(Exception $e) {
                    echo "There was an error, please enter a valid Organization ID";
                }
            }
            else {
                $var = select_random();
                print_me($client->organizations()->find(array('id' => $var, 'sideload' => $sideload)));
            }
            break;
        // Create organization with data provided, or create example if none is provided
        case "createOrganization":
            if(!empty($_REQUEST['name'])){
                try {
                    print_me($client->organizations()->create($_REQUEST));
                } catch (Exception $e) {
                    echo "There was an error, please try again.";
                }       
            } else {
                print_me(create_test());
            }
            break;
        // Create many organizations, not allowed for user input, just for testing example
        case "createManyOrganizations": 
            $rand = mt_rand(5, 150000);
            $organizations = array(
                    array('external_id' => 'abc'.$rand,
                        'name' => 'organization #'.$rand,
                        'domain_names' => 'example'.$rand.'.org',
                        'details' => 'organization lives at '.$rand.' lane',
                        'notes' => 'organization created by PHP',
                        'shared_tickets' => true,
                        'shared_comments' => true,
                        'tags' => array('tag_'.$rand, 'organization_by_php')),
                    array('external_id' => 'abc1'.$rand,
                        'name' => 'organization #1'.$rand,
                        'domain_names' => 'example1'.$rand.'.org',
                        'details' => 'organization lives at '.$rand.'1 lane',
                        'notes' => 'organization created by PHP',
                        'shared_tickets' => false,
                        'shared_comments' => false,
                        'tags' => array('tag_1'.$rand, 'organization_by_php'))
            );
            try {
				print_me($client->organizations()->createMany($organizations));
			} catch (Exception $e) {
				echo "There was an error creating the organizations, please try again.";
			}
            break;
            
        // Update organization with data provided, or create and update example if none is provided
        case "updateOrganization":
            if (!empty($_REQUEST['organization_id'])){
                try {
                    print_me($client->organizations()->update($organization));
                } catch(Exception $e) {
                    echo "There was an error, please enter a valid Organization ID";
                }
            }
            else {
                $organization['id'] = create_test()->organization->id;
                $organization['notes'] = "Your test organization was updated by PHP wrapper at ".date('m/d/Y h:i:s a', time());
                try {
                    print_me($client->organizations()->update($organization));
                } catch (Exception $e) {
                    echo "There was an error, please enter a valid Organization ID";
                }   
            }
            break;
        // delete organization by organization_id provided, or create and destroy example if none is provided
        case "deleteOrganization":
            if (!empty($_REQUEST['organization_id'])) {
                try {
                    $client->organizations()->delete(array('id' => $_REQUEST['organization_id']));
                    echo "Organization #".$_REQUEST['organization_id']." deleted successfully.";
                } catch (Exception $e) {
                    echo "There was an error, please enter a valid Organization ID.";
                }
            } else {
                $organization_id = create_test()->organization->id;
                try {
                    $client->organizations()->delete(array('id' => $organization_id));
                    echo "Organization #".$organization_id." deleted successfully.";
                } catch (Exception $e) {
                    echo "There was an error, please enter a valid Organization ID.";
                }
            }
            break;
        // search for an organization by the external_id provided, or create and use example if none is provided
        case "searchByExternalID":
            if (!empty($_REQUEST['external_id'])) {
                try {
                    print_me($client->organizations()->search(array('external_id' => $_REQUEST['external_id'], 'sideload' => $sideload)));
                } catch (Exception $e) {
                    echo "There was an error, please enter a valid External ID for search.";
                }
            } else {
                $organization = create_test();
                try {
                    print_me($client->organizations()->search(array('external_id' => $organization->organization->external_id, 'sideload' => $sideload)));
                } catch (Exception $e) {
                    echo "There was an error, please enter a valid External ID for search.";
                }
            }
            break;
    }
}

?>