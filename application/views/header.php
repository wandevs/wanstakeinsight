<!DOCTYPE html>
<html lang="en" style="background:#f4f3ef">

<head>
    <meta charset="utf-8" />
    <base href="<?php echo base_url() ?>"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        <?php echo (isset($web_title)?$web_title.' | ':'')?>WAN STAKE INSIGHT
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="./assets/css/paper-dashboard.css?v=2.0.0" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="./assets/demo/demo.css" rel="stylesheet" />

    <link rel="apple-touch-icon" sizes="180x180" href="./assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="./assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="./assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146678906-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-146678906-1');
    </script>


    <style>

        .main-panel>.content
        {
            margin-top:20px !important;
        }

    </style>

</head>

<style>
    .navbar.navbar-transparent .nav-item.active .nav-link:not(.btn), .navbar.navbar-transparent .nav-item .nav-link:not(.btn):focus, .navbar.navbar-transparent .nav-item .nav-link:not(.btn):hover, .navbar.navbar-transparent .nav-item .nav-link:not(.btn):focus:hover, .navbar.navbar-transparent .nav-item .nav-link:not(.btn):active
    {
        color:#ef8157;


    }
    .navbar-nav a{
         font-size: 18px !important;
         margin-top:6px;

     }
    .navbar-nav .nav-item.active{
        border-bottom:5px solid #ef8157;
    }
    .show a,.collapsing a{
        text-align: center;
        margin-top:0px;
    }
    .show .navbar-nav,.collapsing  .navbar-nav {
        background:#FFFFFF;

    }
    .navbar .navbar-toggler .navbar-toggler-bar.navbar-kebab
    {
        width: 5px;
        height: 5px;
    }
</style>
<div class="wrapper">

    <div class="main-panel" style="width: 100%;">

        <nav class="navbar navbar-expand-lg navbar-transparent">


                    <a style="color:#2c2c2c;margin-left:20px;margin-top:10px;" class="navbar-brand" href="./"><img src="./assets/logo.png" style="margin-top:-5px;"/> WAN STAKE INSIGHT</a>

            <div class="navbar-toggle">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-bar navbar-kebab"></span>
                    <span class="navbar-toggler-bar navbar-kebab"></span>
                    <span class="navbar-toggler-bar navbar-kebab"></span>
                </button>
            </div>
			<style>
			.navbar .navbar-collapse .nav-item a
			{
				    font-size: 14px !important;
					padding-top: 16px;
			}
			.show a, .collapsing a
			{
				margin-top:6px;
			}
			.navbar-nav .dropdown-menu:before,.navbar-nav .dropdown-menu:after
			{
				display:none;
			}
			.show .dropdown-item
			{
				padding-left:45px;
			}
			.show .dropdown-menu
			{
				background:#fafafa;
				left:-50%;
			}
			.dropdown-menu .dropdown-item:hover{
				background:transparent;
				color:#ef8157 !important;
			}
			.dropdown-menu .dropdown-item.active{
				background:transparent;
				color:#ef8157 !important;
			}
		
			</style>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
							 <li class="nav-item dropdown <?php echo !in_array($this->uri->segment(1),array('storeman','token','address'))?'active':''?>">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  GALAXY POS
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								  <a class="dropdown-item <?php echo $this->uri->segment(1)==''?'active':''?>"" href="./">VALIDATORS</a>
								  <a class="dropdown-item" <?php echo $this->uri->segment(1)=='selected-validators'?'active':''?>" href="./selected-validators">SELECTED VALIDATORS</a>
								<a class="dropdown-item <?php echo $this->uri->segment(1)=='reward'?'active':''?>"" href="./reward">REWARD</a>
								<a class="dropdown-item <?php echo $this->uri->segment(1)=='chart'?'active':''?>"" href="./chart">CHARTS</a>
								<a class="dropdown-item <?php echo $this->uri->segment(1)=='stake-guide'?'active':''?>"" href="./stake-guide">STAKING GUIDE</a>
								
									
								</div>
							  </li>
							
							<li class="nav-item <?php echo $this->uri->segment(1)=='storeman'?'active':''?>">
                                <a class="nav-link" href="./storeman">STOREMEN</a>
                            </li>
							<li class="nav-item <?php echo $this->uri->segment(1)=='token' && $this->uri->segment(2)==''?'active':''?>">
                                <a class="nav-link" href="./token">CONVERTED ASSETS</a>
                            </li>
							<li class="nav-item <?php echo $this->uri->segment(1)=='address'?'active':''?>">
                                <a class="nav-link" href="./address">TOP 50</a>
                            </li>
							<li class="nav-item <?php echo $this->uri->segment(2)=='wasp'?'active':''?>">
                                <a class="nav-link" href="./token/wasp">$WASP</a>
                            </li>
							
							
                        </ul>
                    </div>

        </nav>
		
		<div class="alert alert-info text-center"><i class="fa fa-heart"></i> Support us by staking with <b><a style="color:white;text-decoration:underline" href="https://www.wanscan.org/storemaninfo/0x92dce4f5857cad9208a2f168445e3670d4f84d74?groupid=0x000000000000000000000000000000000000000000000041726965735f303031" target="_blank">Our Storeman Node</a></b> <i class="fa fa-heart"></i></div>

       