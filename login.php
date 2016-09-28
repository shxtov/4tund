<?php
	//võtab ja kopeerib faili sisu
	require ("../../config.php");
	require ("functions.php");



	//kas kasutaja sisse logitud
	if (isset ($_SESSION["userId"])){
		header("Location: data.php");
	}

    //var_dump($_POST);
	
	$signupEmailError = "";
	$signupPasswordError = "";
	$signupBdayError = "";
	$signupCarPrefError ="";
	$signupEmail = "";
	$signupBday = "1995-02-25";
	$signupGender = "male";
	$signupCarPref_items = [];
	$notice = "";

	// kas epost oli olemas
	if (isset ($_POST ["signupEmail"])){
		
		if (empty ($_POST ["signupEmail"])){
			
			//oli email, kuigi see oli tühi
			$signupEmailError = "See väli on kohustuslik!";
			
		} else {
			
			//email on õige, salvestan väärtuse muutujasse
			$signupEmail = $_POST["signupEmail"];
			
		}
		
	}


    if (isset ($_POST ["signupBday"])){

        if (empty ($_POST ["signupBday"])){

            // if bday wasnt set
            $signupBdayError = "See väli on kohustuslik!";

        }else{
			$signupBday = $_POST["signupBday"];
		}

    }
	
	
	
	if (isset ($_POST ["signupBday"])){

        if (empty ($_POST ["signupBday"])){

            // if bday wasnt set
            $signupBdayError = "See väli on kohustuslik!";

        }else{
			$signupBday = $_POST["signupBday"];
		}

    }



	if (isset ($_POST ["signupPassword"])){
		
		if (empty ($_POST ["signupPassword"])){
			
			//oli password, kuigi see oli tühi
			$signupPasswordError = "See väli on kohustuslik!";
			
		}else{
			// tean et oli parool ja see ei olnud tühi
			// vähemalt 8 sümbolit
			
			if (strlen($_POST["signupPassword"])< 8){
			$signupPasswordError = "Parool peab olema vähemalt	8 tähemärki pikk!";
			}
			
			
		}
		
	}


	if (isset ($_POST['signupGender'])){
			$signupGender = $_POST["signupGender"];
	}


	if (isset($_POST['signupCarPref_items'])){
		if (!in_array("eucars", $_POST['signupCarPref_items']) &&
			!in_array("uscars",$_POST['signupCarPref_items']) &&
			!in_array("japcars",$_POST['signupCarPref_items']) &&
			!in_array("ruscars",$_POST['signupCarPref_items']) &&
			!in_array("korcars",$_POST['signupCarPref_items'])){
			$signupCarPrefError = 'Vähemalt üks valik on kohustuslik!';
		} else {
			$signupCarPref_items = $_POST["signupCarPref_items"];
		}


	}



	if (empty ($signupEmailError)&& empty($signupPasswordError) && empty($signupCarPrefError)
		&& empty($signupBdayError) &&  isset ($_POST['signupPassword'])
		&& isset ($_POST['signupEmail']) && isset ($_POST['signupBday'])
		&& isset ($_POST['signupGender']) && !empty ($_POST['signupCarPref_items'])){


		$signupCarPref_todatabase = implode ($_POST['signupCarPref_items'], " ");
		$password = hash("sha512", $_POST["signupPassword"]);


		signup($signupEmail, $password, $signupBday, $signupGender, $signupCarPref_todatabase);
	}

	//kontrollin et kasutaja täitis välja ja võib sisse logida

	if(isset($_POST["loginEmail"]) && isset($_POST['loginPassword']) && !empty($_POST["loginEmail"]) && !empty($_POST['loginPassword'])){
		$notice = login($_POST["loginEmail"], $_POST['loginPassword']);
	} 

?>



<html>


<style>
	* {font-family: "Calibri Light";}
	.redtext {color: red; font-weight: bold; }
	.table1  {border-collapse:collapse;border-spacing:0;}
	.table1 td{font-family:Arial, sans-serif;font-size:14px;padding:5px;border-style:none;overflow:hidden;word-break:normal;}
	.table1 .table1-style1{color:#f00b0b;vertical-align:top}
	.table1 .table1-style2{}
	.table1 .table1-style3{color:#f00b0b}
	.table1 .table1-style4{text-align:right;vertical-align:top}
	.table1 .table1-style5{vertical-align:top}
	.table1 .table1-style6{vertical-align:top}

</style>

<head>
	<title>Login page</title>
</head>

<body>


		<h1>Logi sisse:</h1>
		
		<form method ="post">
			<p class = "redtext"><?=$notice;?></p>
			<label>E-post:</label><br>
			<input name = "loginEmail" type ="email" placeholder = "E-post">
			<br><br>
			
			<label>Parool:</label><br>
			<input name = "loginPassword" type ="password" placeholder = "Parool">
			<br><br>
			
			<input type ="submit" value = "Logi sisse">
		
		</form>
		
		
		<h1>Loo kasutaja:</h1>
		
		<form method ="post">
            <table class="table1">
                <tr>
                    <td class="table1-style2">E-post:<span class = 'redtext'>*</span></td>
                    <td class="table1-style5"><input name = "signupEmail" type ="email" value = "<?=$signupEmail;?>" placeholder = "E-post"></td>
                    <td class="table1-style3"><span class = 'redtext'><?=$signupEmailError;?></span></td>
                </tr>
                <tr>
                    <td class="table1-style2">Parool:<span class = 'redtext'>*</span></td>
                    <td class="table1-style5"><input name = "signupPassword" type ="password" placeholder = "Parool"></td>
                    <td class="table1-style3"><span class = 'redtext'><?=$signupPasswordError;?></span></td>
                </tr>
                <tr>
                    <td class="table1-style6">Sünnipäev:<span class = 'redtext'>*</span></td>
                    <td class="table1-style5"><input  name="signupBday" type ="date" min="1900-01-01" max = "<?=date('Y-m-d'); ?>" value = "<?=$signupBday;?>"></td>
                    <td class="table1-style1"><span class = 'redtext'><?=$signupBdayError;?></span></td>
                </tr>
                <tr>
                    <td class="table1-style6">Sugu:<span class = 'redtext'>*</span></td>
                    <td class="table1-style5">
					
						<?php if($signupGender == "male") { ?>
							<label><input type="radio" name="signupGender" value="male" checked> Mees</label><br>
						<?php } else { ?>
							<label><input type="radio" name="signupGender" value="male"> Mees</label><br>
						<?php } ?>
						
						<?php if($signupGender ==  "female") { ?>
							<label><input type="radio" name="signupGender" value="female" checked> Naine</label><br>
						<?php } else { ?>
							<label><input type="radio" name="signupGender" value="female"> Naine</label><br>
						<?php } ?>
						
						<?php if($signupGender ==  "unspecified") { ?>
							<label><input type="radio" name="signupGender" value="unspecified" checked> Ei soovi avaldada</label><br>
						<?php } else {?>
							<label><input type="radio" name="signupGender" value="unspecified"> Ei soovi avaldada</label><br>
						<?php } ?>
						

                    <td class="table1-style1"></td>
                </tr>
                <tr>
                    <td class="table1-style6">Autohuvid:<span class = 'redtext'>*</span></td>
                    <td class="table1-style5">

							<input type="hidden" name="signupCarPref_items[]"  value="">

						<?php if(isset($_POST['signupCarPref_items']) && is_array($_POST['signupCarPref_items'])&& in_array("eucars", $_POST['signupCarPref_items'])){?>
							<label><input type="checkbox" name="signupCarPref_items[]" value="eucars" checked> Euroopa autod</label><br>
						<?php } else { ?>
							<label><input type="checkbox" name="signupCarPref_items[]" value="eucars"> Euroopa autod</label><br>
						<?php } ?>

						<?php if(isset($_POST['signupCarPref_items']) && is_array($_POST['signupCarPref_items'])&& in_array("uscars", $_POST['signupCarPref_items'])){?>
							<label><input type="checkbox" name="signupCarPref_items[]" value="uscars" checked> Ameerika autod</label><br>
						<?php } else { ?>
							<label><input type="checkbox" name="signupCarPref_items[]" value="uscars"> Ameerika autod</label><br>
						<?php } ?>

						<?php if(isset($_POST['signupCarPref_items']) && is_array($_POST['signupCarPref_items'])&& in_array("japcars", $_POST['signupCarPref_items'])){?>
							<label><input type="checkbox" name="signupCarPref_items[]" value="japcars" checked>Jaapani autod</label><br>
						<?php } else { ?>
							<label><input type="checkbox" name="signupCarPref_items[]" value="japcars"> Jaapani autod</label><br>
						<?php } ?>

						<?php if(isset($_POST['signupCarPref_items']) && is_array($_POST['signupCarPref_items'])&& in_array("ruscars", $_POST['signupCarPref_items'])){?>
						<label><input type="checkbox" name="signupCarPref_items[]" value="ruscars" checked> Vene autod</label><brc>
						<?php } else { ?>
							<label><input type="checkbox" name="signupCarPref_items[]" value="ruscars"> Vene autod</label><br>
						<?php } ?>

						<?php if(isset($_POST['signupCarPref_items']) && is_array($_POST['signupCarPref_items'])&& in_array("korcars", $_POST['signupCarPref_items'])){?>
							<label><input type="checkbox" name="signupCarPref_items[]" value="korcars" checked> Korea autod</label><br>
						<?php } else { ?>
							<label><input type="checkbox" name="signupCarPref_items[]" value="korcars">  Korea autod</label><br>
						<?php } ?>
                    <td class="table1-style1"><span class = 'redtext'><?=$signupCarPrefError;?></span></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="table1-style4"><input type ="submit" value = "Submit"></td>
                    <td></td>
                </tr>
            </table>
		</form>

</body>
</html>