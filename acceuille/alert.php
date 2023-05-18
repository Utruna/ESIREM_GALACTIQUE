<?php
if (!isset($_SESSION)) {
    session_start();
}
if(isset($_SESSION['good_alert'])) {
    ?>
    <div class="good_alert">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
        <?php echo $_SESSION['good_alert']; $_SESSION['good_alert'] = null ?>

    </div>
    <?php
}
if(isset($_SESSION['bad_alert'])) {
    ?>
    <div class="bad_alert">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
        <?php  echo $_SESSION['bad_alert']; $_SESSION['bad_alert'] = null ?>
    </div>
    <?php
}