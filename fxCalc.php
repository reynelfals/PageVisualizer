<?php
include_once('FxDataModel.php');
if (!isset($_SESSION)) {
    session_start();
}
require_once 'LoginDataModel.php';
$my_login_data = new LoginDataModel();
$user_key = $my_login_data->getNames()[LoginDataModel::USER_KEY];
$pass_key = $my_login_data->getNames()[LoginDataModel::PSSWD_KEY];

if (!array_key_exists($user_key, $_SESSION)) {
    include LOGIN_SCRIPT;
    exit();
}

if (!array_key_exists(FxDataModel::DATA_MODEL, $_SESSION)) {
    $my_data_model = new FxDataModel();
    $_SESSION[FxDataModel::DATA_MODEL] = serialize($my_data_model);
}
$my_data_model = unserialize($_SESSION[FxDataModel::DATA_MODEL]);
$currency_codes = $my_data_model->getCurrencies();
$ini_file_array = $my_data_model->getIniArray();

$srcCurrency = $ini_file_array[FxDataModel::SRC_CUCY_KEY];
$dstCurrency = $ini_file_array[FxDataModel::DST_CUCY_KEY];
$amountIn = $ini_file_array[FxDataModel::SRC_AMT_KEY];
$amountOut = $ini_file_array[FxDataModel::DST_AMT_KEY];

$emailToKey=$ini_file_array[FxDataModel::EMAIL_TO];
require_once 'EmailModel.php';

$in = 0.0;

$inCurrency = $currency_codes[0];
$outCurrency = $currency_codes[0];
$indexIn = 0;
$indexIn = 0;
$result = '';
$email_error='';
$isInvalidInput = TRUE;
if (array_key_exists($amountIn, $_POST)) {
    $in = $_POST[$amountIn]; //getting the amount of money input.
    if (is_numeric($in) && $in >= 0) {
        $inCurrency = $_POST[$srcCurrency]; //the code in
        $outCurrency = $_POST[$dstCurrency]; //the code out
        $result = $in * $my_data_model->getFxRate($inCurrency, $outCurrency);
        $isInvalidInput = FALSE;
    }
}
if (!$isInvalidInput && array_key_exists($emailToKey, $_POST)){
    $cleanAddr = trim($_POST[$emailToKey]);
    
    if(strlen($cleanAddr)>0){
        $emailResult = new EmailModel();
        if ($emailResult->validateMailAddr($cleanAddr)){
            $body=$in.' '.$inCurrency.' = '.$result.' '.$outCurrency;
    
            $pear_error=$emailResult->sendMail($cleanAddr, $body);
            if(strlen($pear_error)==0){
                $email_error= 'Results are being e-mailed to '.  htmlspecialchars($cleanAddr);
            }else{
                $email_error='The following error ocurred when trying to email results to '.  htmlspecialchars($cleanAddr).
                    ':<br><br>'.$pear_error;
            }
        }else{
            $email_error=  htmlspecialchars($cleanAddr).' is an invalid e-mail address. Results could not be emailed.';
        }
    }
}

?>
<!DOCTYPE html>
<!--
 
-->
<html>

    <title>F/X Calculator</title>

    <body>
        <div id="content" align="center">
            <h2>Money Banks F/X Calculator</h2>
            <hr>
            <h3>Welcome <?php echo $_SESSION[$user_key]; ?></h3>
            <form name="fxCalc" action="<?php echo MAIN_SCRIPT; ?>" method="post">

                <select name="<?php echo $srcCurrency ?>">
                    <?php
                    foreach ($currency_codes as $value) {
                        if (!$isInvalidInput && $value == $inCurrency) {
                            echo '<option selected value="' . $value . '">' . $value . '</option>';
                        } else {
                            echo '<option value="' . $value . '">' . $value . '</option>';
                        }
                    }
                    ?>

                </select>
                <input value="<?php echo $isInvalidInput ? '' : $in; ?>" name="<?php echo $amountIn ?>" required/>


                <select name="<?php echo $dstCurrency ?>">
                    <?php
                    foreach ($currency_codes as $value) {

                        if (!$isInvalidInput && $value == $outCurrency) {
                            echo '<option selected value="' . $value . '">' . $value . '</option>';
                        } else {
                            echo '<option value="' . $value . '">' . $value . '</option>';
                        }
                    }
                    ?>
                </select>  
                <input value="<?php echo $isInvalidInput ? '' : $result; ?>" readonly="readonly" name="<?php echo $amountOut ?>" />

                <br>
                Email Address: <input value="" name="<?php echo $emailToKey ?>" />
                <br>
                 
                <input type="submit" value="Convert">
                <input type="reset">
                
            </form>
            <br>
            <br>
            <?php echo $email_error?>
        </div>
    </body>
</html>

