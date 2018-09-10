
<form action="index.php" method="POST">
	<input type="text" name="index">
	<input type="submit" value="GET NEW ID">
</form>
<?php
function generatePrime($n){

$prime = null;

  for($i=1;$i<=$n;$i++){

          $counter = 0; 
          for($j=1;$j<=$i;$j++){


                if($i % $j==0){ 

                      $counter++;
                }
          }

        //divide by itself
        if($counter==2){
               $prime.= $i;
        }
    }

    return $prime;
} 

function answer($n)
{
	$n = (string)$n;
	//generate prime numbers upto 10000
	$prime = generatePrime(10000);

	//select the enxt 5 characters
	$ID = substr($prime, $n, 5);

	return $ID;

}

$prime = generatePrime(100);

//handle form submission
if (!empty($_POST)) {
	//if form submitted
	$index = $_POST['index'];

	if ($index != '') {
	$newID = answer($index);
	echo "NEW ID = ".$newID;
	}
	
}

?>
