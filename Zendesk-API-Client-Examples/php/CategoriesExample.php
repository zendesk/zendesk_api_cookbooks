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

//create a new category 
$newcategory = $client->categories()->create(array(
	'name'=>'TestCat',
	'description'=>'The Description'
))->category;

//create a new category if the form was submitted
if (!empty($_POST)) {
	$client->categories()->create(array(
		'name'=>$_POST['name'],
		'description'=>$_POST['description']
	));
}

//select a specific category
$found = $client->categories($newcategory->id)->find();

//get all categories
$categories = $client->categories()->findAll();

//print the categories
foreach($categories->categories as $category){
	echo "<div id='$category->id' class='catinfo'>
		<p>Name: <a href='$category->url'>$category->name</a></p>
		<p>Description: $category->description</p>
		</div>";
}

//update a category
$client->categories($newcategory->id)->update(array(
	'description'=>'The updated description'
));

//delete a category
$client->categories($newcategory->id)->delete();


?>

<form action="categories-endpoint.php" method="post">
	Name: <input type="text" name="name"/><br/>
	Description: Name: <input type="text" name="description"/><br/>
	<input type="submit"/>
</form>