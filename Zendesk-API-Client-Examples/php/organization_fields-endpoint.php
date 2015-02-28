<h4>Organization Fields</h4>
<form action="organization_fields-endpoint.php" method="POST" enctype="multipart/form-data">
    <select name="field_form">
        <option value="showAllFields">Show All Fields</option>
        <option value="showField">Show Field</option>
        <option value="createField">Create Field</option>
        <option value="updateField">Update Field</option>
        <option value="deleteField">Delete Field</option>
        <option value="reorderField">Reorder Fields</option>
    </select></br>
    <input type="text" name="field_id" placeholder="field ID"/>Input a comma separated list of IDs if Reordering.</br>
    Field Type: <select name="field_type">
        <option value="text">Text</option>
        <option value="textarea">Multi-Line Text</option>
        <option value="checkbox">Checkbox</option>
        <option value="date">Date</option>
        <option value="integer">Integer</option>
        <option value="decimal">Decimal</option>
        <option value="regexp">Regular Expression</option>
        <option value="tagger">Tagger - Custom Dropdown</option>
    </select></br>
    <input type="text" name="title" placeholder="field title"/></br>
    <input type="text" name="key" placeholder="field key"/></br>
    <input type="text" name="description" placeholder="field description"/></br>
    <input type="number" name="position" placeholder="field position (ordering)"/></br>
    <input type="checkbox" name="active" value="true"/>Should this field be active?</br>
    <input type="text" name="regexp_for_validation" placeholder="regexp_for_validation"/></br>
    <input type="text" name="tag" placeholder="Optional tag for checkbox type fields"/></br>
    To add options for a tagger (drop-down) type field, enter them below following the format of the placeholder text.</br>
	<textarea rows="6" cols="60" name="custom_field_options" placeholder="Custom Field Options">[{ "name": "Custom Option 1", "value": "custom_option_1"}, {"name": "Custom Option 2", "value": "custom_option_2" }]</textarea></br>
    <input type="checkbox" name="sample" value="true"/>Should the code create a sample integer field?</br>
    <input type="checkbox" name="tagger" value="true"/>Should the code create a sample tagger field?</br>
    <button type="submit">Submit</button></br>
</form>
<?php

include("vendor/autoload.php");

use Zendesk\API\Client as ZendeskAPI;

$RANDOM_INTEGER = time();
$subdomain = "z3nburmaglot";
$username = "japeterson@zendesk.com";
$token = "FsD6L6pHGSsoHFctgl0HsPVATjEepNRHEwr2zycl"; // replace this with your token

$client = new ZendeskAPI($subdomain, $username);
$client->setAuth('token', $token); // set either token or password

$organization_field = array();
$sample = false;

if (!empty($_REQUEST['sample'])) {
    $sample = true;
    $organization_field = create_field();
} elseif (!empty($_REQUEST['tagger'])) {
    $sample = true;
    $organization_field = create_tagger_field();
} else {
    $organization_field['id'] = (!empty($_REQUEST['field_id'])) ? $_REQUEST['field_id'] : null;
    $organization_field['key'] = (!empty($_REQUEST['key'])) ?  $_REQUEST['key'] : null;
    $organization_field['type'] = (!empty($_REQUEST['field_type'])) ?  $_REQUEST['field_type'] : null;
    $organization_field['title'] = (!empty($_REQUEST['title'])) ?  $_REQUEST['title'] : null;
    $organization_field['description'] = (!empty($_REQUEST['description'])) ?  $_REQUEST['description'] : null;
    $organization_field['position'] = (!empty($_REQUEST['position'])) ?  $_REQUEST['position'] : null;
    $organization_field['active'] = (!empty($_REQUEST['active'])) ?  $_REQUEST['active'] : null;
    $organization_field['regexp_for_validation'] = (!empty($_REQUEST['regexp_for_validation'])) ?  $_REQUEST['regexp_for_validation'] : null;
    $organization_field['tag'] = (!empty($_REQUEST['tag'])) ?  $_REQUEST['tag'] : null;
    $organization_field['custom_field_options'] = (!empty($_REQUEST['custom_field_options'])) ?  json_decode($_REQUEST['custom_field_options']) : array();
}
$organization_field = array_filter($organization_field);

function print_me($material, $message = null) {
    echo $message;
    echo "<pre>";
    print_r($material);
    echo "</pre>";
}

// Used to Create a Sample Object
function create_field(array $field = null){
    global $client, $RANDOM_INTEGER;
    return $client->organizationFields()->create(array('type' => 'integer', 'title' => "field $RANDOM_INTEGER", 'key' => "field_{$RANDOM_INTEGER}"));
}
function create_tagger_field(array $field = null){
    global $client, $RANDOM_INTEGER;
    return $client->organizationFields()->create(array('type' => 'tagger', 'title' => "Tagger #{$RANDOM_INTEGER}", 'key' => "tagger_{$RANDOM_INTEGER}", 'custom_field_options' => array(array("name" => "Custom Option 1", "value" => "custom_option_1"), array("name" => "Custom Option 2", "value" => "custom_option_2" ))));
}

// Only execute logic if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch ($_REQUEST['field_form']){
        
        // Show all fields   
        case "showAllFields":
            try {
                print_me($client->organizationFields()->findAll());
            } catch (Exception $e) {
                echo "There was an error retrieving the Organization Fields. Please try again.";
            }
        break;
        
        // Show field with user input - requires ID
        case "showField":
        if ($sample == true) {
            print_me($organization_field);
        } else {
            if (!empty($organization_field['id'])) {
                try {
                    print_me($client->organizationFields()->find(array('id' => $organization_field['id'])));
                } catch (Exception $e) {
                    echo "There was an error retrieving the Organization Field. Please try again.";
                }  
            } else echo "Enter an Organization Field ID or use a sample object!";
        }
        break;
        
        // Create a new field with user input - requires key, type, and title, optionally custom_field_options if a tagger type   
        case "createField":
        if ($sample == true) {
            print_me($organization_field);
        } else {
            if (!empty($organization_field['type']) && !empty($organization_field['title']) && !empty($organization_field['key'])){
                try {
                    print_me($client->organizationFields()->create($organization_field));
                } catch (Exception $e) {
                    echo "There was an error creating the Organization Field. Please try again.";
                }
            } else echo "Please include a valid type, title, and key, or use a sample object!";
        }
        break;
        
        // Update a field with user input - requires ID
        case "updateField":
        if ($sample == true) {
            print_me($client->organizationFields()->update(array('id' => $organization_field->organization_field->id, 'description' => "updated by PHP at {$RANDOM_INTEGER}." )));
        }
        if (!empty($organization_field['id'])){
            try {
                print_me($client->organizationFields()->update($organization_field));
            } catch (Exception $e) {
                echo "There was an error updating the Organization Field. Please try again.";
            }
        } else echo "Enter an Organization Field ID or use a sample object!";
        break;
        
        // Delete field with user input - requires ID
        case "deleteField":
        if ($sample == true) {
            $client->organizationFields()->delete(array('id' => $organization_field->organization_field->id));
            echo "Field #{$organization_field->organization_field->id} has been deleted successfully.";
        } else {
            if (!empty($organization_field['id'])) { 
                try {
                    $client->organizationFields()->delete(array('id' => $organization_field['id']));
                    echo "Field #{$organization_field['id']} deleted successfully.";
                } catch (Exception $e) {
                    echo "There was an error deleting the Organization Field. Please try again.";
                }
            } else {
                echo "Enter an Organization Field ID or use a sample object!";
            }
        }
        break;
        
        // Reorder the field listing with user inputs - requires comma separated list of IDs
        case "reorderField":
            if (!empty($organization_field['id'])){
                $organization_field['id'] = explode(",", preg_replace('/\s+/', '', $organization_field['id']));
                try {
                    $client->organizationFields()->reorder(array('organization_field_ids' => $organization_field['id']));
                    echo "The fields were reordered successfully.";
                    print_me($client->organizationFields()->findAll());
                } catch (Exception $e) {
                    echo "There was an error reordering the Organization Fields. Please try again";
                }
            } else echo "Enter a Comma Separated list of Organization IDs for Reordering!";
        break;
    }
}
?>