<?php
/*
Template Name: Homepage
*/
?>

	<?php get_header(); ?>
<!-- Slider main container -->
<div class="swiper">
  <!-- Additional required wrapper -->
  <div class="swiper-wrapper">
    <!-- Slides -->
    <div class="swiper-slide" style="background:red">
      <h1 class="fw-extrabold fs-900 uppercase">World leader in supercritical foams</h1>
      <p class="fw-bold fs-600 uppercase">High-performance supercritical foams made for quality applications in a wide range of markets.</p>
      <p class="fw-regular fs-100 uppercase">NEXT: <span class="fw-bold">AVIATION &amp; AEROSPACE</span></p>
    </div>
    <div class="swiper-slide" style="background:blue">
      Slide 2
    </div>
    <div class="swiper-slide" style="background:green">
      Slide 3
    </div>
  </div>

  <!-- Only the next button -->
  <div class="swiper-button-next"></div>
  
  <!-- Circle container -->
  <div class="circle-container">
    <svg class="circle-svg" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
      <circle class="circle-background" cx="60" cy="60" r="55"></circle>
      <circle class="circle-progress" cx="60" cy="60" r="55"></circle>
    </svg>
  </div>
</div>

	<div class="half-half">
		<div class="half"></div>
		<div class="half"></div>
	</div>

	<?php get_footer(); ?>
