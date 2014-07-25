<?php
/*************
** Example Written By: Ben Evans
*************/

include("vendor/autoload.php");

use Zendesk\API\Client as ZendeskAPI;

$subdomain = "";
$username = "";
$token = ""; // replace this with your token
$catid = ""; //The category to attach a new forum to

$client = new ZendeskAPI($subdomain, $username);
$client->setAuth('token', $token); // set either token or password

//Create a new forum, and a persistent one from the submit box
$newforum = $client->forums()->create(array(
	'name'=>'Test2',
	'forum_type'=>'articles',
	'access'=>'logged-in users',
	'category_id'=>$catid
))->forum;

//if the form was submitted, make a new item
if (!empty($_POST)) {
	$client->forums()->create(array(
		'name'=>$_POST['name'],
		'forum_type'=>$_POST['type'],
		'access'=>$_POST['access'],
		'category_id'=>$_POST['category']
	));
}

//Select a single form
$found = $client->forum($newforum->id)->find();

//get all forums
$fourms = $client->forums()->findAll();

//Print the forums
foreach($fourms->forums as $forum){
	//Get the category information
	$cattext = "";
	if(isset($forum->category_id)) {
		$category = $client->category($forum->category_id)->find()->category;
		$cattext = "<p>Category: <a href='" . $category->url . "'>" . $category->name . "</a></p>";
	}
	
	echo "<div id='$forum->id' class='foruminfo'>
		<p>Name: <a href='$forum->url'>$forum->name</a></p>
		$cattext
		</div>";
}


//update a forum
$client->forum($newforum->id)->update(array(
	'name'=>'Test3'
));

//Delete the forum
$delete = $client->forum($newforum->id)->delete();

?>

<form action="forum-endpoint.php" method="post">
	
Name: <input type="text" name="name">
<br/>

Access: 
<select name="access">
	<option value="everybody">everybody</option>
	<option value="logged-in users">logged-in users</option>
	<option value="agents only">agents only</option>
</select>
<br/>

Type:
<select name="type">
	<option value="articles">articles</option>
	<option value="ideas">ideas</option>
	<option value="questions">questions</option>
</select>
<br/>

Category:
<select name="category">
	<?php
	$categories = $client->categories()->findAll();	
	foreach($categories->categories as $category){
		echo "<option value='$category->id''>$category->name</option>";
	}
	?>
</select>

<input type="submit"/>
</form>