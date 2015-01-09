<?php

class Result 
{    
    protected $passed, $errorMessage;
       
    public function __construct($passed, $errorMessage = "")
    {
        $this->passed = $passed;
        $this->errorMessage = $errorMessage;
    }
    
    // ################################################################
    
    public function getPassed()
    {
        return $this->passed;
    }
    
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    // ################################################################

    public function setPassed($passed)
    {
        $this->passed = $passed;
    }
    
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }   
    
}
