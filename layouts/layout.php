<?php
include 'backoffice/header.php';
//include 'backoffice/menuAdmin.php';
//include 'backoffice/footer.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" /> 
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" href="public/css/bootstrap.css" />
        <link rel="stylesheet" href="public/css/font-awesome-4.1.0/css/font-awesome.min.css" />        
        <link rel="stylesheet" href="public/css/style.css" />

        <script type="text/javascript" src="public/js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="public/js/bootstrap.min.js"></script>
    </head>       
    <body>
        <div class="superContainer">
            <div class="container">        
                <header>
                    <?php echo $header; ?>
                </header>
            </div>  

            <?php // echo $menuAdmin; ?>            

            <div class="subtitle">
                <div class="container"> 
                    <p><?php echo $title; ?></p>  
                </div>
            </div>

            <div class="container">   
                <section>        
                    <?php echo $layout; ?>        
                </section>
            </div>

            <footer>
                <?php // echo $footer; ?>
            </footer> 
        </div>
    </body>
</html>


<script type="text/javascript">

</script>



