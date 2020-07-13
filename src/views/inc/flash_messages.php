<?php if(isset($_SESSION['flash'])):?>
    <?php foreach($_SESSION['flash'] as $type=> $message):?>
        <div class="alert <?php echo $type;?>">
            <?php echo $message;?>
        </div>
    <?php endforeach;?>
    <div id="reset-flash"></div>
    <?php unset($_SESSION['flash']);?> 
<?php endif;?>