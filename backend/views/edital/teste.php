<?php

use yii\helpers\Html;

?>

<script>

    setInterval(function(){
	    	 					var xhttp = new XMLHttpRequest();
								xhttp.onreadystatechange = function() {
									if (xhttp.readyState == 4 && xhttp.status == 200) {
										document.getElementById("teste2").innerHTML = xhttp.responseText;
									}
								};
									xhttp.open("GET", "index.php?r=edital/teste2", true);
									xhttp.send();
							}, 8000
	);
</script>

<body> 

<div id = "teste2">

</div>

</body>
