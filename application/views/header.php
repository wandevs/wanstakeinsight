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
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item <?php echo $this->uri->segment(1)==''?'active':''?>">
                                <a class="nav-link" href="./">HOME</a>
                            </li>
                            <li class="nav-item <?php echo $this->uri->segment(1)=='selected-validators'?'active':''?>">
                                <a class="nav-link" href="./selected-validators">SELECTED VALIDATORS</a>
                            </li>
                            <li class="nav-item <?php echo $this->uri->segment(1)=='reward'?'active':''?>">
                                <a class="nav-link" href="./reward">REWARD</a>
                            </li>
                            <li class="nav-item <?php echo $this->uri->segment(1)=='chart'?'active':''?>">
                                <a class="nav-link" href="./chart">CHARTS</a>
                            </li>
                            <li class="nav-item <?php echo $this->uri->segment(1)=='stake-guide'?'active':''?>">
                                <a class="nav-link" href="./stake-guide">STAKING GUIDE</a>
                            </li>

                        </ul>
                    </div>

        </nav>
<!--
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a style="color:#2c2c2c" class="navbar-brand" href="./"><img src="./assets/logo.png" style="margin-top:-5px;"/> WAN STAKE INSIGHT <sup class="badge badge-danger" style="font-size:11px;font-weight:normal;vertical-align: top">Experimental</sup></a>

                </div>
                <div class="navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="ti-panel"></i>
                                <p>Stats</p>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="ti-bell"></i>
                                <p class="notification">5</p>
                                <p>Notifications</p>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Notification 1</a></li>
                                <li><a href="#">Notification 2</a></li>
                                <li><a href="#">Notification 3</a></li>
                                <li><a href="#">Notification 4</a></li>
                                <li><a href="#">Another notification</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">
                                <i class="ti-settings"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
-->

     