<?php
if (isset($src)) {
    foreach ($src as $s) {
?>
    <script src="<?php echo $s ?>"></script>
<?php
    }
}

if (isset($script)) {
    echo $script;
}
?>

</body>

</html>