<html>

<body>
<?php
session_start();

$variable = $_SESSION['search'];

$id = $_GET['id'];

$variable = json_decode($variable,true);


if(is_array($variable) && !empty($variable) && isset($id)){

	$new = $variable['Response']['Results'][0][$id];


	echo '<pre>';
	print_r($new);
	echo '</pre>';
}

echo '<pre>';
print_r($variable);
echo '</pre>';
?>

<script>

var jsArray = <?php echo json_encode($new); ?>;
console.log(jsArray);

</script>

</body>
</html>
