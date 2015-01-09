<?php
    $title = 'All girls';
    
    ob_start();
        echo $msg;
        $layout = ob_get_contents();
    ob_clean();
include 'layouts/layout.php';

?>

<script type="text/javascript">
    function loadXMLDoc()
    {
        var xmlhttp;
        if (window.XMLHttpRequest){
            xmlhttp = new XMLHttpRequest();
        }else{
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                // do something if the page loaded successfully
            }
        };
        
        xmlhttp.open("GET","login.php",true);
        xmlhttp.send();
    }
</script>
