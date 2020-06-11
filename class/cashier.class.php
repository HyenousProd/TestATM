<?php 
class cashier
{

    private $_cash; // All available change in cashier (array)
    
    public function __construct()
    {
        $this->_cash = array ( 1 => 25, 2 => 74, 5 => 14, 10 => 18, 20 => 0, 50 => 5, 100 => 30, 200 => 15, 500 => 8, 1000 => 11, 2000 => 8, 5000 => 5, 10000 => 2, 20000 => 0, 50000 => 0 );
;
    }

    // GETTER --------
    private function getCashNb($changeVal) // Get available change for a specific amount
    {
        $p = $this->getAvailableChange();
        return $p[$changeVal];
    }   

    public function getAvailableChange() // Get all available change (array)
    {
        return $this->_cash;
    }
    
    public function getChangeToGiveBack($cost, $paid) // Compute total amount of money to give back to customer
    {
        if($paid >= $cost) // Return amount if correct
        {
            return intval(($paid - $cost)*100);   
        }
        else
        {
            return false; //Return error if customer paid less than the bill
        }
    }
    
    public function getCustomerChange($cost, $paid) // get all change to give back to the customer
    {
        $giveBack = $this->getChangeToGiveBack($cost, $paid); // Compute total amount to give back & convert to cts
        $changeToGive = array();
        $temp_array = array_reverse($this->getAvailableChange(), true); // Reorder array to browse from higher amount to lower
        
        foreach($temp_array as $changeVal => $changeNb) // Browse available change
        {
            if($changeVal <= $giveBack and $changeNb > 0)  // Check if this amount is relevant and if still available
            {
                $partInt = intval($giveBack/$changeVal); // Compute the max nb of items we can give back for this amount
                if($partInt <= $changeNb) // If max quantity available, give everything required for this amount
                {
                    $giveBack -= $partInt*$changeVal;
                    $changeToGive[$changeVal] = $partInt;
                    $this->removeCash($changeVal, $partInt); // Update available change
                }
                elseif($partInt > $changeNb) // If not enough quantity for this amount, give all we have in stock
                {
                    $giveBack -= $changeNb*$changeVal;
                    $changeToGive[$changeVal] = $changeNb;
                    $this->removeCash($changeVal, $changeNb); // Update available change
                }
                if($giveBack == 0) // Once we have enough to give the money back, break the loop
                {
                    break;
                }
            }
        }
        if($giveBack == 0) // If we could give back all the money required, proceed and return array
        {
            return $changeToGive;
        }
        else // if we had not enough change available, return error and put the money back
        {
            foreach($changeToGive as $val => $nb)
            {
                $this->addCash($val, $nb);
            }
            return false;
        }   
    }

    //SETTER ---------
    
    private function removeCash($val, $nb) // Remove cash from available change
    {
        //Check if we do not try to take more money than available
        if(($this->getCashNb($val) - $nb) >=0)
        {
            $this->_cash[$val] -= $nb; 
            return true;
        }
        else
        {
            return false;
        }
    }  
    
    private function addCash($val, $nb) // Add cash to available change
    {
        $this->_cash[$val] += $nb; 
    }
}

?>