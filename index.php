<?php
require_once('class/cashier.class.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Test ATM</title>
        <meta charset="utf-8" />
    </head>
    <body>
        <?php
            $cash = new cashier();
            //Display default change availability
            echo "<h2>Available change</h2>"; 
            echo "<ul>";
                foreach($cash->getAvailableChange() as $key=>$content)
                {
                    if($key >=100)
                    {
                        echo "<li>".$content." x ".($key/100)."€</li>";  
                    }
                    else
                    {
                        echo "<li>".$content." x ".$key."cts</li>";     
                    }  
                }
            echo "</ul>";
        
            //Check form 
            if((isset($_POST['cost']) and !is_numeric($_POST['cost'])) or (isset($_POST['paid']) and !is_numeric($_POST['paid'])))
            {
                echo "<h2>Invalid numbers</h2>";    
            }
            elseif(isset($_POST['cost']) and $_POST['cost'] <= $_POST['paid'])
            {
                echo "<h2>You must give back ".($cash->getChangeToGiveBack($_POST['cost'], $_POST['paid'])/100)."€</h2>";
                $giveBack = $cash->getCustomerChange($_POST['cost'], $_POST['paid']);
                if($giveBack == false)
                {
                    echo "<h3>Error : you do not have enough change</h3>";    
                }
                else
                {
                    echo "<ul>";
                    foreach($giveBack as $key=>$content)
                    {
                        if($key >=100)
                        {
                            echo "<li>".$content." x ".($key/100)."€</li>";  
                        }
                        else
                        {
                            echo "<li>".$content." x ".$key."cts</li>";     
                        }
                    }
                    echo "</ul>";

                    echo "<h2>Remaining change after payback</h2>";
                    echo "<ul>";
                    foreach($cash->getAvailableChange() as $key=>$content)
                    {
                        if($key >=100)
                        {
                            echo "<li>".$content." x ".($key/100)."€</li>";  
                        }
                        else
                        {
                            echo "<li>".$content." x ".$key."cts</li>";     
                        }  
                    }
                    echo "</ul>";    
                }     
            }
            elseif(isset($_POST['cost']) and $_POST['cost'] > $_POST['paid'])
            {
                echo "<h2>Error : customer did not pay enough !</h2>";    
            }
            else
            {
                echo "<h2>Enter values below</h2>";
            }
        ?>
        <form method="post" action="index.php">
            Bill <input type="text" name="cost" />
            Paid <input type="text" name="paid" />
            <input type="submit" name="Send" />
        </form>
    </body>
</html>

