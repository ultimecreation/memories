<?php 
startSession();
?>
<!DOCTYPE html>
<html lang="en">

<head>
<link  href="https://fonts.googleapis.com/css2?family=Montserrat&family=Raleway&display=swap">
    <meta name="description" content="le blog des globetrotters">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Memories</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo publicUrl('/assets/css/style.css');?>">

    <script src="<?php echo publicUrl('/assets/js/');?>main.js" defer></script>
   
   
    <title></title>
</head>

<body>
    <?php include_once('../src/views/inc/navbar.php'); ?>
    <div class="container flash-message">
        <?php include_once('../src/views/inc/flash_messages.php'); ?>
    </div>