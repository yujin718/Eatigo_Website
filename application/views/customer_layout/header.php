<?php
$account_type = $this->session->userdata('login_type');
?>
<div class="top-bar">
    <div class="container">
        <div class="row">
            <div class="col-md-6 top-message">
                <p>Welcome to Burped</p>
            </div>
            <div class="col-md-6 top-links">
                <ul class="listnone">
                        <li><a href="<?php echo base_url(); ?>index.php/CustomerController/pricingplan">Pricing Plan</a></li> 
                    <?php if ($account_type == 'customer') { ?>                       
                        <li><a href="<?php echo base_url(); ?>index.php/LoginController/logout">Logout</a></li> 
                    <?php } else if ($account_type == 'vendor') { ?>
                        <li><a href="<?php echo base_url(); ?>index.php/LoginController/logout">Logout</a></li>
                    <?php } else if ($this->session->fb_login == 1) { ?>
                        <li><a href="<?php echo base_url(); ?>index.php/CustomerController/fb_logout">Logout</a></li>
                    <?php } else { ?>
                        <li><a href="<?php echo base_url(); ?>index.php/CustomerController/signupvendor" class=" ">Are you Vendor ?</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/CustomerController/login" class=" ">Login</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/CustomerController/register" class=" ">Sign Up</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="tp-nav" id="headersticky"><!-- navigation start -->
    <div class="container">
        <nav class="navbar navbar-default navbar-static-top"> 

            <!-- Brand and toggle get grouped for better mobile display -->

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <a class="navbar-brand" href="<?php echo base_url() . 'index.php/CustomerController' ?>"><img src="<?php echo base_url(); ?>images/logo.png" alt="" class="img-responsive"></a> </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="active hoo"><a href="<?php echo base_url(); ?>index.php/CustomerController">Home</a></li>
                    <li class="dropdown hoo"> <a href="<?php echo base_url(); ?>index.php/CustomerController/restaurants">Places </a></li>

                    <?php if ($account_type == 'customer' || $account_type == 'vendor') { ?>
                        <li class="dropdown hoo"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="fa fa-angle-down"></span></a>
                            <ul class="dropdown-menu">

                                <?php if ($account_type == 'customer') { ?>
                                    <li><a href="<?php echo base_url(); ?>index.php/CustomerController/profile">Profile</a></li>
                                <?php } else if ($account_type == 'vendor') { ?>
                                    <li><a href="<?php echo base_url(); ?>index.php/VendorController">Profile</a></li>
                                <?php } ?>                            

                            </ul>
                        </li>
                    <?php } ?>

                    <!-- /.Mega Dropdown -->
<!--                    <li class="hoo"><a href="<?php echo base_url(); ?>index.php/CustomerController/contactus">Contact Us</a></li>-->
                </ul>
            </div>

            <!-- /.navbar-collapse --> 
        </nav>
    </div>
    <!-- /.container-fluid --> 


</div><!-- navigation end -->

