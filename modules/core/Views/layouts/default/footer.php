
<script type="text/javascript" src="/plugins/underscore/underscore-umd-min.js"></script>

<!-- <script type="text/javascript" src="/plugins/jquery-modal/jquery.modal.js"></script>
<script type="text/javascript" src="/plugins/jquery-modal/box_alert.js"></script> -->
<script type="text/javascript" src="/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="/plugins/sweetalert2/box_alert.js"></script>

<script type="text/javascript" src="/plugins/toastr/toastr.min.js"></script>



<?php

echo $this->getFooterJs();
?>

<script>
<?php echo $this->getFooterScript(); ?>
</script>

<script src="/plugins/lazyload/lazyload.min.js"></script>
<script>
var lazyLoadInstance = new LazyLoad({
    elements_selector: ".lazy"
});
</script>
</body>
</html>
