
<footer class="footer">
    <div class="container-fluid">

        <div class="text-center">
            <a class="navbar-brand" style="margin-bottom:20px;"><img src="./assets/logo.png" style="margin-top:-5px;"/> WAN STAKE INSIGHT</a><br/>

            <i class="fa fa-bullhorn"></i> Contact me via telegram <a href="https://t.me/cryptofennec" target="_blank">@cryptofennec</a>
			 or Follows us at <a href="https://twitter.com/wanstakeinsight" target="_blank"> <i class="fa fa-twitter"></i> Wanstakeinsight</a>
			<br/>
            <i class="fa fa-exclamation-triangle"></i> This is an experimental project! Don't use any information in this website for any serious matter. <br/>©

            <?php echo date('Y')?>, this template made with <i class="fa fa-heart heart"></i> by Creative Tim

        </div>
    </div>
    </div>
</footer>
</div>
</div>
<!--   Core JS Files   -->
<script src="./assets/js/core/jquery.min.js"></script>
<script src="./assets/js/core/popper.min.js"></script>
<script src="./assets/js/core/bootstrap.min.js"></script>

<?php
if (isset($js))
$this->load->view($js);
?>

</body>

</html>
