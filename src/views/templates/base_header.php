<?php 
startSession();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/js/all.min.js" integrity="sha512-M+hXwltZ3+0nFQJiVke7pqXY7VdtWW2jVG31zrml+eteTP7im25FdwtLhIBTWkaHRQyPrhO2uy8glLMHZzhFog==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?php echo publicUrl('/assets/css/');?>style.css">

    <script src="<?php echo publicUrl('/assets/js/');?>main.js" defer></script>
    <title></title>
</head>

<body>
    <?php include_once('../src/views/inc/navbar.php'); ?>
    <div class="container">
        <?php include_once('../src/views/inc/flash_messages.php'); ?>
    </div>