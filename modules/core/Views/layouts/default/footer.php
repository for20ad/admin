

<script type="text/javascript" src="/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript" src="/plugins/sweetalert2/sweetalert2.all.js" <?php csp_script_nonce()?>></script>
<script type="text/javascript" src="/plugins/sweetalert2/box_alert.js" <?php csp_script_nonce()?>></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- Tabler Core -->
<script src="/dist/js/tabler.min.js" defer></script>
<script src="/dist/js/common.js" defer></script>


<?php
$this->setFooterJs('/dist/js/core/global.js', 0);
$uriString = \Config\Services::request()->uri->getPath();

if ( strpos( $uriString, '/login' ) === false && strpos( $uriString, 'test' ) === false){
    $this->setFooterJs('/dist/js/core/admin/infinity.js', 0);
}


echo $this->getFooterJs();
?>

<script <?php echo csp_script_nonce()?>>
<?php echo $this->getFooterScript(); ?>
</script>
<script>
    check_popup();
</script>
<script src="/plugins/lazyload/lazyload.min.js"></script>
<script <?php echo csp_script_nonce()?>>
var lazyLoadInstance = new LazyLoad({
    elements_selector: ".lazy"
});
</script>
<!-- <footer class="footer footer-transparent d-print-none">
            <div class="container-xl">
                <div class="row text-center align-items-center flex-row-reverse">
                    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                        <ul class="list-inline list-inline-dots mb-0">
                            <li class="list-inline-item">
                                Copyright &copy; 2024
                                <a href="#" class="link-secondary">산수유람</a>.
                                All rights reserved.
                            </li>
                            <li class="list-inline-item">
                                <a href="#" class="link-secondary" rel="noopener">
                                    v1.0.0
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>--> -->
    <!--</div>
</div>-->
</body>
</html>