<?php declare(strict_types=1);
require_once __DIR__ . '/../../controllers/AdminController.php';
require_once __DIR__ . '/../../controllers/Admin-C.php';
session_start();
//Kiểm tra xem token đã có trong session chưa
if (!isset($_SESSION['user'])) {
   header("Location: /login");
   exit();
}

if ($_SESSION['user']['role_id'] == 0){
   header("Location: /home");
   exit();
}
$adminController = new AdminController();
$totalAlbums = $adminController->getTotalAlbums();
$artists = $adminController->getArtist();
$songs = $adminController->getSongs();
?>

<!DOCTYPE html>
<html lang="en">
<head></head>
<!doctype html>
<html lang="en">
<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title>Admin - BASO Music</title>
   <!-- Favicon -->
   <link rel="shortcut icon" href="/public/images/logo.jpg" />
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="/public/css/bootstrap.min.css">
   <!-- Typography CSS -->
   <link rel="stylesheet" href="/public/css/typography.css">
   <!-- Style CSS -->
   <link rel="stylesheet" href="/public/css/admin.css">
   <!-- Responsive CSS -->
   <link rel="stylesheet" href="/public/css/admin-responsive.css">
   <style type="text/css">.apexcharts-canvas {
  position: relative;
  user-select: none;
  /* cannot give overflow: hidden as it will crop tooltips which overflow outside chart area */
}

/* scrollbar is not visible by default for legend, hence forcing the visibility */
.apexcharts-canvas ::-webkit-scrollbar {
  -webkit-appearance: none;
  width: 6px;
}
.apexcharts-canvas ::-webkit-scrollbar-thumb {
  border-radius: 4px;
  background-color: rgba(0,0,0,.5);
  box-shadow: 0 0 1px rgba(255,255,255,.5);
  -webkit-box-shadow: 0 0 1px rgba(255,255,255,.5);
}
.apexcharts-canvas.dark {
  background: #343F57;
}

.apexcharts-inner {
  position: relative;
}

.legend-mouseover-inactive {
  transition: 0.15s ease all;
  opacity: 0.20;
}

.apexcharts-series-collapsed {
  opacity: 0;
}

.apexcharts-gridline, .apexcharts-text {
  pointer-events: none;
}

.apexcharts-tooltip {
  border-radius: 5px;
  box-shadow: 2px 2px 6px -4px #999;
  cursor: default;
  font-size: 14px;
  left: 62px;
  opacity: 0;
  pointer-events: none;
  position: absolute;
  top: 20px;
  overflow: hidden;
  white-space: nowrap;
  z-index: 12;
  transition: 0.15s ease all;
}
.apexcharts-tooltip.light {
  border: 1px solid #e3e3e3;
  background: rgba(255, 255, 255, 0.96);
}
.apexcharts-tooltip.dark {
  color: #fff;
  background: rgba(30,30,30, 0.8);
}
.apexcharts-tooltip * {
  font-family: inherit;
}

.apexcharts-tooltip .apexcharts-marker,
.apexcharts-area-series .apexcharts-area,
.apexcharts-line {
  pointer-events: none;
}

.apexcharts-tooltip.active {
  opacity: 1;
  transition: 0.15s ease all;
}

.apexcharts-tooltip-title {
  padding: 6px;
  font-size: 15px;
  margin-bottom: 4px;
}
.apexcharts-tooltip.light .apexcharts-tooltip-title {
  background: #ECEFF1;
  border-bottom: 1px solid #ddd;
}
.apexcharts-tooltip.dark .apexcharts-tooltip-title {
  background: rgba(0, 0, 0, 0.7);
  border-bottom: 1px solid #333;
}

.apexcharts-tooltip-text-value,
.apexcharts-tooltip-text-z-value {
  display: inline-block;
  font-weight: 600;
  margin-left: 5px;
}

.apexcharts-tooltip-text-z-label:empty,
.apexcharts-tooltip-text-z-value:empty {
  display: none;
}

.apexcharts-tooltip-text-value,
.apexcharts-tooltip-text-z-value {
  font-weight: 600;
}

.apexcharts-tooltip-marker {
  width: 12px;
  height: 12px;
  position: relative;
  top: 0px;
  margin-right: 10px;
  border-radius: 50%;
}

.apexcharts-tooltip-series-group {
  padding: 0 10px;
  display: none;
  text-align: left;
  justify-content: left;
  align-items: center;
}

.apexcharts-tooltip-series-group.active .apexcharts-tooltip-marker {
  opacity: 1;
}
.apexcharts-tooltip-series-group.active, .apexcharts-tooltip-series-group:last-child {
  padding-bottom: 4px;
}
.apexcharts-tooltip-series-group-hidden {
  opacity: 0;
  height: 0;
  line-height: 0;
  padding: 0 !important;
}
.apexcharts-tooltip-y-group {
  padding: 6px 0 5px;
}
.apexcharts-tooltip-candlestick {
  padding: 4px 8px;
}
.apexcharts-tooltip-candlestick > div {
  margin: 4px 0;
}
.apexcharts-tooltip-candlestick span.value {
  font-weight: bold;
}

.apexcharts-tooltip-rangebar {
  padding: 5px 8px;
}

.apexcharts-tooltip-rangebar .category {
  font-weight: 600;
  color: #777;
}

.apexcharts-tooltip-rangebar .series-name {
  font-weight: bold;
  display: block;
  margin-bottom: 5px;
}

.apexcharts-xaxistooltip {
  opacity: 0;
  padding: 9px 10px;
  pointer-events: none;
  color: #373d3f;
  font-size: 13px;
  text-align: center;
  border-radius: 2px;
  position: absolute;
  z-index: 10;
  background: #ECEFF1;
  border: 1px solid #90A4AE;
  transition: 0.15s ease all;
}

.apexcharts-xaxistooltip.dark {
  background: rgba(0, 0, 0, 0.7);
  border: 1px solid rgba(0, 0, 0, 0.5);
  color: #fff;
}

.apexcharts-xaxistooltip:after, .apexcharts-xaxistooltip:before {
  left: 50%;
  border: solid transparent;
  content: " ";
  height: 0;
  width: 0;
  position: absolute;
  pointer-events: none;
}

.apexcharts-xaxistooltip:after {
  border-color: rgba(236, 239, 241, 0);
  border-width: 6px;
  margin-left: -6px;
}
.apexcharts-xaxistooltip:before {
  border-color: rgba(144, 164, 174, 0);
  border-width: 7px;
  margin-left: -7px;
}

.apexcharts-xaxistooltip-bottom:after, .apexcharts-xaxistooltip-bottom:before {
  bottom: 100%;
}

.apexcharts-xaxistooltip-top:after, .apexcharts-xaxistooltip-top:before {
  top: 100%;
}

.apexcharts-xaxistooltip-bottom:after {
  border-bottom-color: #ECEFF1;
}
.apexcharts-xaxistooltip-bottom:before {
  border-bottom-color: #90A4AE;
}

.apexcharts-xaxistooltip-bottom.dark:after {
  border-bottom-color: rgba(0, 0, 0, 0.5);
}
.apexcharts-xaxistooltip-bottom.dark:before {
  border-bottom-color: rgba(0, 0, 0, 0.5);
}

.apexcharts-xaxistooltip-top:after {
  border-top-color:#ECEFF1
}
.apexcharts-xaxistooltip-top:before {
  border-top-color: #90A4AE;
}
.apexcharts-xaxistooltip-top.dark:after {
  border-top-color:rgba(0, 0, 0, 0.5);
}
.apexcharts-xaxistooltip-top.dark:before {
  border-top-color: rgba(0, 0, 0, 0.5);
}


.apexcharts-xaxistooltip.active {
  opacity: 1;
  transition: 0.15s ease all;
}

.apexcharts-yaxistooltip {
  opacity: 0;
  padding: 4px 10px;
  pointer-events: none;
  color: #373d3f;
  font-size: 13px;
  text-align: center;
  border-radius: 2px;
  position: absolute;
  z-index: 10;
  background: #ECEFF1;
  border: 1px solid #90A4AE;
}

.apexcharts-yaxistooltip.dark {
  background: rgba(0, 0, 0, 0.7);
  border: 1px solid rgba(0, 0, 0, 0.5);
  color: #fff;
}

.apexcharts-yaxistooltip:after, .apexcharts-yaxistooltip:before {
  top: 50%;
  border: solid transparent;
  content: " ";
  height: 0;
  width: 0;
  position: absolute;
  pointer-events: none;
}
.apexcharts-yaxistooltip:after {
  border-color: rgba(236, 239, 241, 0);
  border-width: 6px;
  margin-top: -6px;
}
.apexcharts-yaxistooltip:before {
  border-color: rgba(144, 164, 174, 0);
  border-width: 7px;
  margin-top: -7px;
}

.apexcharts-yaxistooltip-left:after, .apexcharts-yaxistooltip-left:before {
  left: 100%;
}

.apexcharts-yaxistooltip-right:after, .apexcharts-yaxistooltip-right:before {
  right: 100%;
}

.apexcharts-yaxistooltip-left:after {
  border-left-color: #ECEFF1;
}
.apexcharts-yaxistooltip-left:before {
  border-left-color: #90A4AE;
}
.apexcharts-yaxistooltip-left.dark:after {
  border-left-color: rgba(0, 0, 0, 0.5);
}
.apexcharts-yaxistooltip-left.dark:before {
  border-left-color: rgba(0, 0, 0, 0.5);
}

.apexcharts-yaxistooltip-right:after {
  border-right-color: #ECEFF1;
}
.apexcharts-yaxistooltip-right:before {
  border-right-color: #90A4AE;
}
.apexcharts-yaxistooltip-right.dark:after {
  border-right-color: rgba(0, 0, 0, 0.5);
}
.apexcharts-yaxistooltip-right.dark:before {
  border-right-color: rgba(0, 0, 0, 0.5);
}

.apexcharts-yaxistooltip.active {
  opacity: 1;
}
.apexcharts-yaxistooltip-hidden {
  display: none;
}

.apexcharts-xcrosshairs, .apexcharts-ycrosshairs {
  pointer-events: none;
  opacity: 0;
  transition: 0.15s ease all;
}

.apexcharts-xcrosshairs.active, .apexcharts-ycrosshairs.active {
  opacity: 1;
  transition: 0.15s ease all;
}

.apexcharts-ycrosshairs-hidden {
  opacity: 0;
}

.apexcharts-zoom-rect {
  pointer-events: none;
}
.apexcharts-selection-rect {
  cursor: move;
}

.svg_select_points, .svg_select_points_rot {
  opacity: 0;
  visibility: hidden;
}
.svg_select_points_l, .svg_select_points_r {
  cursor: ew-resize;
  opacity: 1;
  visibility: visible;
  fill: #888;
}
.apexcharts-canvas.zoomable .hovering-zoom {
  cursor: crosshair
}
.apexcharts-canvas.zoomable .hovering-pan {
  cursor: move
}

.apexcharts-xaxis,
.apexcharts-yaxis {
  pointer-events: none;
}

.apexcharts-zoom-icon,
.apexcharts-zoom-in-icon,
.apexcharts-zoom-out-icon,
.apexcharts-reset-zoom-icon,
.apexcharts-pan-icon,
.apexcharts-selection-icon,
.apexcharts-menu-icon,
.apexcharts-toolbar-custom-icon {
  cursor: pointer;
  width: 20px;
  height: 20px;
  line-height: 24px;
  color: #6E8192;
  text-align: center;
}


.apexcharts-zoom-icon svg,
.apexcharts-zoom-in-icon svg,
.apexcharts-zoom-out-icon svg,
.apexcharts-reset-zoom-icon svg,
.apexcharts-menu-icon svg {
  fill: #6E8192;
}
.apexcharts-selection-icon svg {
  fill: #444;
  transform: scale(0.76)
}

.dark .apexcharts-zoom-icon svg,
.dark .apexcharts-zoom-in-icon svg,
.dark .apexcharts-zoom-out-icon svg,
.dark .apexcharts-reset-zoom-icon svg,
.dark .apexcharts-pan-icon svg,
.dark .apexcharts-selection-icon svg,
.dark .apexcharts-menu-icon svg,
.dark .apexcharts-toolbar-custom-icon svg{
  fill: #f3f4f5;
}

.apexcharts-canvas .apexcharts-zoom-icon.selected svg,
.apexcharts-canvas .apexcharts-selection-icon.selected svg,
.apexcharts-canvas .apexcharts-reset-zoom-icon.selected svg {
  fill: #008FFB;
}
.light .apexcharts-selection-icon:not(.selected):hover svg,
.light .apexcharts-zoom-icon:not(.selected):hover svg,
.light .apexcharts-zoom-in-icon:hover svg,
.light .apexcharts-zoom-out-icon:hover svg,
.light .apexcharts-reset-zoom-icon:hover svg,
.light .apexcharts-menu-icon:hover svg {
  fill: #333;
}

.apexcharts-selection-icon, .apexcharts-menu-icon {
  position: relative;
}
.apexcharts-reset-zoom-icon {
  margin-left: 5px;
}
.apexcharts-zoom-icon, .apexcharts-reset-zoom-icon, .apexcharts-menu-icon {
  transform: scale(0.85);
}

.apexcharts-zoom-in-icon, .apexcharts-zoom-out-icon {
  transform: scale(0.7)
}

.apexcharts-zoom-out-icon {
  margin-right: 3px;
}

.apexcharts-pan-icon {
  transform: scale(0.62);
  position: relative;
  left: 1px;
  top: 0px;
}
.apexcharts-pan-icon svg {
  fill: #fff;
  stroke: #6E8192;
  stroke-width: 2;
}
.apexcharts-pan-icon.selected svg {
  stroke: #008FFB;
}
.apexcharts-pan-icon:not(.selected):hover svg {
  stroke: #333;
}

.apexcharts-toolbar {
  position: absolute;
  z-index: 11;
  top: 0px;
  right: 3px;
  max-width: 176px;
  text-align: right;
  border-radius: 3px;
  padding: 0px 6px 2px 6px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.apexcharts-toolbar svg {
  pointer-events: none;
}

.apexcharts-menu {
  background: #fff;
  position: absolute;
  top: 100%;
  border: 1px solid #ddd;
  border-radius: 3px;
  padding: 3px;
  right: 10px;
  opacity: 0;
  min-width: 110px;
  transition: 0.15s ease all;
  pointer-events: none;
}

.apexcharts-menu.open {
  opacity: 1;
  pointer-events: all;
  transition: 0.15s ease all;
}

.apexcharts-menu-item {
  padding: 6px 7px;
  font-size: 12px;
  cursor: pointer;
}
.light .apexcharts-menu-item:hover {
  background: #eee;
}
.dark .apexcharts-menu {
  background: rgba(0, 0, 0, 0.7);
  color: #fff;
}

@media screen and (min-width: 768px) {
  .apexcharts-toolbar {
    /*opacity: 0;*/
  }

  .apexcharts-canvas:hover .apexcharts-toolbar {
    opacity: 1;
  }
}

.apexcharts-datalabel.hidden {
  opacity: 0;
}

.apexcharts-pie-label,
.apexcharts-datalabel, .apexcharts-datalabel-label, .apexcharts-datalabel-value {
  cursor: default;
  pointer-events: none;
}

.apexcharts-pie-label-delay {
  opacity: 0;
  animation-name: opaque;
  animation-duration: 0.3s;
  animation-fill-mode: forwards;
  animation-timing-function: ease;
}

.apexcharts-canvas .hidden {
  opacity: 0;
}

.apexcharts-hide .apexcharts-series-points {
  opacity: 0;
}

.apexcharts-area-series .apexcharts-series-markers .apexcharts-marker.no-pointer-events,
.apexcharts-line-series .apexcharts-series-markers .apexcharts-marker.no-pointer-events, .apexcharts-radar-series path, .apexcharts-radar-series polygon {
  pointer-events: none;
}

/* markers */

.apexcharts-marker {
  transition: 0.15s ease all;
}

@keyframes opaque {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

/* Resize generated styles */
@keyframes resizeanim {
  from {
    opacity: 0;
  }
  to {
    opacity: 0;
  }
}

.resize-triggers {
  animation: 1ms resizeanim;
  visibility: hidden;
  opacity: 0;
}

.resize-triggers, .resize-triggers > div, .contract-trigger:before {
  content: " ";
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  overflow: hidden;
}

.resize-triggers > div {
  background: #eee;
  overflow: auto;
}

.contract-trigger:before {
  width: 200%;
  height: 200%;
}
</style>
   <link type="text/css" rel="stylesheet" charset="UTF-8" href="https://www.gstatic.com/_/translate_http/_/ss/k=translate_http.tr.26tY-h6gH9w.L.W.O/am=GAw/d=0/rs=AN8SPfoV6mMC6tlFnBTPsgfPv12vhvDMnA/m=el_main_css">
</head>
<body>
   <!-- loader Start -->
   <div id="loading-1">
      <div id="loading-center-1">
          <div id="loading-circle-1"></div>
      </div>
   </div>
   <!-- loader END -->
   <!-- Wrapper Start -->
   <div class="wrapper">
      <!-- TOP Nav Bar -->
      <div class="iq-top-navbar">
         <div class="iq-navbar-custom">
            <nav class="navbar navbar-expand-lg navbar-light p-0">
               <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav ml-auto navbar-list">
                     <li class="line-height pt-3">
                        <a href="#" class="search-toggle iq-waves-effect d-flex align-items-center">
                           <img src="<?php echo $_SESSION['user']['img'] ?>" class="img-fluid rounded-circle" alt="user">
                        </a>
                        <div class="iq-sub-dropdown iq-user-dropdown">
                           <div class="iq-card shadow-none m-0">
                              <div class="iq-card-body p-0 ">
                                 <div class="bg-primary p-3">
                                    <h5 class="mb-0 text-white line-height">Hello <?php echo $_SESSION['user']['fullname'] ?></h5>
                                 </div>
                                 <div class="d-inline-block w-100 text-center p-3">
                                    <a class="bg-primary iq-sign-btn" href="logout" role="button">Sign out<i class="ri-login-box-line ml-2"></i></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </li>
                  </ul>
               </div>
            </nav>
         </div>
      </div>
      <!-- TOP Nav Bar END -->
      <!-- Page Content  -->
      <div id="content-page" class="content-page">
         <div class="container-fluid">
            <div class="row"> 
               <div class="col-sm-6 col-md-6 col-lg-4">
                  <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                     <div class="iq-card-body">
                        <div class="d-flex align-items-center justify-content-between">
                           <h6>Nghệ sĩ</h6>
                           <span class="iq-icon"><i class="ri-information-fill"></i></span>
                        </div>
                        <div class="iq-customer-box d-flex align-items-center justify-content-between mt-3">
                           <div class="iq-map text-primary font-size-32"><i class="ri-bar-chart-grouped-line"></i></div>
                           <div class="d-flex align-items-center">
                              <h2><?php echo $artists['total_artists']; ?></h2>
                              <div class="rounded-circle iq-card-icon iq-bg-primary ml-3"> <i class="ri-inbox-fill"></i></div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6 col-md-6 col-lg-4">
                  <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                     <div class="iq-card-body">
                        <div class="d-flex align-items-center justify-content-between">
                           <h6>Album</h6>
                           <span class="iq-icon"><i class="ri-information-fill"></i></span>
                        </div>
                        <div class="iq-customer-box d-flex align-items-center justify-content-between mt-3">
                           <div class="iq-map text-success font-size-32"><i class="ri-bar-chart-grouped-line"></i></div>
                           <div class="d-flex align-items-center">
                              <h2><?php echo $totalAlbums; ?></h2>
                              <div class="rounded-circle iq-card-icon iq-bg-success ml-3"><i class="ri-price-tag-3-line"></i></div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6 col-md-6 col-lg-4">
                  <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                     <div class="iq-card-body">
                        <div class="d-flex align-items-center justify-content-between">
                           <h6>Người dùng</h6>
                           <span class="iq-icon"><i class="ri-information-fill"></i></span>
                        </div>
                        <div class="iq-customer-box d-flex align-items-center justify-content-between mt-3">
                           <div class="iq-map text-danger font-size-32"><i class="ri-bar-chart-grouped-line"></i></div>
                           <div class="d-flex align-items-center">
                              <h2>
                                  <?php
                                    require_once __DIR__ . '/../../controllers/Admin-C.php';
                                    $adminC = new Admin_C();
                                    echo $adminC->getTotalUser();
                                  ?>
                              </h2>
                              <div class="rounded-circle iq-card-icon iq-bg-danger ml-3"><i class="ri-radar-line"></i></div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                     <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                           <h4 class="card-title">Người dùng</h4>
                        </div>
                     </div>
                     <div style="height: 312px; overflow: auto;" class="iq-card-body">
                        <ul class="list-inline p-0 m-0">
                          <?php
                              require_once __DIR__ . '/../../controllers/Admin-C.php';
                              $adminC = new Admin_C();
                              $user = $adminC->getUser();
                              foreach ($user as $key => $value) {
                          ?>
                           <li class="d-flex mb-3 align-items-center p-3 sell-list border border-primary rounded">
                              <div class="user-img img-fluid">
                                 <img src="<?php echo $value['img'] ?>" alt="story-img" class="img-fluid rounded-circle avatar-40">
                              </div>
                              <div class="media-support-info ml-3">
                                 <h6><?php echo $value['fullname'] ?></h6>
                                 <p class="mb-0 font-size-12"><?php echo $value['created_at'] ?></p>
                              </div>
                           </li>
                          <?php
                              }
                          ?>
                        </ul>
                     </div>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                     <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                           <h4 class="card-title">Ca sĩ</h4>
                        </div>
                     </div>
                     <div style="height: 312px; overflow: auto;" class="iq-card-body">
                        <ul class="list-inline p-0 m-0">
                           <?php                        
                              foreach ($artists['artist_details'] as $key => $value) {
                          ?>
                           <li class="d-flex mb-3 align-items-center p-3 sell-list border border-primary rounded">
                              <div class="user-img img-fluid">
                                 <img src="<?php echo $value['image'] ?>" alt="story-img" class="img-fluid rounded-circle avatar-40">
                              </div>
                              <div class="media-support-info ml-3">
                                 <h6><?php echo $value['name'] ?></h6>
                                 <p class="mb-0 font-size-12">Ca Sĩ</p>
                              </div>
                              <div class="iq-card-header-toolbar d-flex align-items-center">
                                 <div class="badge badge-pill badge-primary"><?php echo $value['popularity'] ?></div>
                              </div>
                           </li>
                          <?php
                              }
                          ?>
                        </ul>
                     </div>
                  </div>
               </div>
               <div class="col-lg-12">
                  <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                     <div class="iq-card-header d-flex justify-content-between">
                        <div class="iq-header-title">
                           <h4 class="card-title">Song Table</h4>
                        </div>
                     </div>
                     <div class="iq-card-body rec-pat">
                        <div style="height: 295px; overflow: auto" class="table-responsive">
                           <table class="table table-striped mb-0 table-borderless">
                              <thead>
                                 <tr>
                                    <th>No</th>
                                    <th>Song</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                 </tr>
                              </thead>
                              <tbody >
                              <?php 
                                  $i=1;
                                  foreach ($songs as $key => $value){ 
                              ?>
                                 <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($value['track_name']) ?></td>
                                    <td>
                                       <div class="media align-items-center">
                                          <div class="media-body ml-3">
                                             <p class="mb-0"><?= htmlspecialchars($value['artist_name']) ?></p>
                                          </div>
                                       </div>
                                    </td>
                                    <td><?= htmlspecialchars($value['release_date']) ?></td>
                                 </tr>
                              <?php } ?>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <script>
        // Wait until the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', () => {
                    // Hide the loader
                const loader = document.getElementById('loading-1');
                if (loader) {
                      loader.style.display = 'none';
                  }
              });
    </script>
   <!-- Wrapper END -->
   <!-- color-customizer -->
   <!-- color-customizer END -->
   <!-- Optional JavaScript -->
   <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="/public/js/jquery.min.js"></script>
   <script src="/public/js/popper.min.js"></script>
   <script src="/public/js/bootstrap.min.js"></script>
   <!-- Appear JavaScript -->
   <script src="/public/js/jquery.appear.js"></script>
   <!-- Countdown JavaScript -->
   <script src="/public/js/countdown.min.js"></script>
   <!-- Counterup JavaScript -->
   <script src="/public/js/waypoints.min.js"></script>
   <script src="/public/js/jquery.counterup.min.js"></script>
   <!-- Wow JavaScript -->
   <script src="/public/js/wow.min.js"></script>
   <!-- Apexcharts JavaScript -->
   <script src="/public/js/apexcharts.js"></script>
   <!-- Slick JavaScript -->
   <script src="/public/js/slick.min.js"></script>
   <!-- Select2 JavaScript -->
   <script src="/public/js/select2.min.js"></script>
   <!-- Owl Carousel JavaScript -->
   <script src="/public/js/owl.carousel.min.js"></script>
   <!-- Magnific Popup JavaScript -->
   <script src="/public/js/jquery.magnific-popup.min.js"></script>
   <!-- Smooth Scrollbar JavaScript -->
   <script src="/public/js/smooth-scrollbar.js"></script>
   <!-- lottie JavaScript -->
   <script src="/public/js/lottie.js"></script>
   <!-- am core JavaScript -->
   <script src="/public/js/core.js"></script>
   <!-- am charts JavaScript -->
   <script src="/public/js/charts.js"></script>
   <!-- am animated JavaScript -->
   <script src="/public/js/animated.js"></script>
   <!-- am kelly JavaScript -->
   <script src="/public/js/kelly.js"></script>
   <!-- am maps JavaScript -->
   <script src="/public/js/maps.js"></script>
   <!-- am worldLow JavaScript -->
   <script src="/public/js/worldLow.js"></script>
   <!-- Raphael-min JavaScript -->
   <script src="/public/js/raphael-min.js"></script>
   <!-- Morris JavaScript -->
   <script src="/public/js/morris.js"></script>
   <!-- Morris min JavaScript -->
   <script src="/public/js/morris.min.js"></script>
   <!-- Flatpicker Js -->
   <script src="/public/js/flatpickr.js"></script>
   <!-- Style Customizer -->
   <script src="/public/js/style-customizer.js"></script>
   <!-- Chart Custom JavaScript -->
   <script src="/public/js/chart-custom.js"></script>
<!-- Music js -->
   <script src="/public/js/music-player.js"></script>
   <!-- Music-player js -->
   <script src="/public/js/music-player-dashboard.js"></script>
   <!-- Custom JavaScript -->
   <script src="/public/js/custom.js"></script>
</body>
</html>