<!DOCTYPE html>
<html lang="en">
<?php 
	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$start = $time;
  include 'layout/head.php'; ?>

<body>
  
<div>
  <?php include 'layout/header.php'; ?>

  <!--================Blog Area =================-->
  <section id="news" class="blog_area section_padding">
    <div class="container">
        <div class="row">
          <div class="col-lg-8 mb-5 mb-lg-0">
              <div class="blog_left_sidebar">
              
