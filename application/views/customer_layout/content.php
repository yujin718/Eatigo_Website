<?php

switch ($pageName) {
    case "Home":
        include ("contents/home.php");
        break;
    case "LOGIN":
        include ("contents/login-page.php");
        break;
    case "REGISTER":
        include ("contents/signup-couple.php");
        break;
    case "PROFILE":
        include ("contents/restaurant-profile.php");
        break;
    case "CONTACTUS":
        include ("contents/contact-us.php");
        break;           
    case "RESTAURANTS":
        include ("contents/restaurant.php");
        break;            
    case "ABOUT":
        include ("contents/about-us.php");
    break;            
}
?>