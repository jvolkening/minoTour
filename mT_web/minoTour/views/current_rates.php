<?php

// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("libraries/password_compatibility_library.php");
}

// include the configs / constants for the database connection
require_once("config/db.php");

// load the login class
require_once("classes/Login.php");

// load the functions
require_once("includes/functions.php");



// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process. in consequence, you can simply ...
$login = new Login();

// ... ask if we are logged in here:
if ($login->isUserLoggedIn() == true) {
    // the user is logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are logged in" view.
    //include("views/index_old.php");
	?>

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<!--
Import the header.
-->
<?php
include 'includes/head-new.php';
?>
  <!--
  BODY TAG OPTIONS:
  =================
  Apply one or more of the following classes to get the
  desired effect
  |---------------------------------------------------------|
  | SKINS         | skin-blue                               |
  |               | skin-black                              |
  |               | skin-purple                             |
  |               | skin-yellow                             |
  |               | skin-red                                |
  |               | skin-green                              |
  |---------------------------------------------------------|
  |LAYOUT OPTIONS | fixed                                   |
  |               | layout-boxed                            |
  |               | layout-top-nav                          |
  |               | sidebar-collapse                        |
  |               | sidebar-mini                            |
  |---------------------------------------------------------|
  -->
  <body class="hold-transition skin-blue sidebar-mini fixed">
    <div class="wrapper">

        <!--Import the header-->
        <?php
        include 'navbar-header-new.php';
        ?>

        <!--Import the left hand navigation-->
        <?php
        include 'navbar-top-links-new.php';
        #include 'test.php';
        ?>


      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">

        <!-- Content Header (Page header) -->

        <section class="content-header">

          <h1>
            Current Summary
            <small> - run: <?php echo cleanname($_SESSION['active_run_name']); ?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-table"></i> Current Run</a></li>
            <li><a href="#"><i class="fa fa-bar-chart-o"></i> Data Summary</a></li>
            <li class="active">Here</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content"><?php include 'includes/run_check.php';?>

            <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php echo cleanname($_SESSION['active_run_name']);?></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="row">
            <div class="col-md-12">
              <!-- Custom Tabs -->
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li><a href="current_summary.php">Read Summaries</a></li>
                  <?php if ($_SESSION['currentbasesum'] > 0){?>
                  <li><a href="current_basecalling.php">Basecaller Summary</a></li>
                  <?php }; ?>
                  <li><a href="current_histogram.php">Read Histograms</a></li>
                  <li class="active"><a href="current_rates.php">Sequencing Rates</a></li>
                  <li><a href="current_pores.php">Pore Activity</a></li>
                  <li><a href="current_quality.php">Read Quality</a></li>
                  <?php if ($_SESSION['activereference'] != "NOREFERENCE") {?>
                  <li><a href="current_coverage.php">Coverage Detail</a></li>
                  <li><a href="current_bases.php">Base Coverage</a></li>
                  <?php }; ?>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                      <div class="panel panel-default">
          			  <div class="panel-heading">
          			    <h3 class="panel-title"><!-- Button trigger modal -->
          <button class="btn btn-info" data-toggle="modal" data-target="#modal2">
           <i class="fa fa-info-circle"></i> Sequencing Rate Information</h4>
          </button>

          <!-- Modal -->
          <div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                  <h4 class="modal-title" id="myModalLabel"> Sequencing Rate Information
                </div>
                <div class="modal-body">
                  Rate of Basecalling<br>
          		This plot show the number of reads generated in one minute intervals over the course of the sequencing run.<br><br>
          		Average Read Length Over Time<br>
          		This plot shows the average length of read generated each minute over the course of the sequncing run.<br><br>
          		Average Time To Process Reads Over Time<br>
          		This plot shows how long a singel read takes to pass through the pore over the course of the sequencing run (1 minute bins).<br><br>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div></h3>
          			  </div>
                        <div class="panel-body">
          					<div id="cumulativeyield" style="width:100%; height:400px;"><i class="fa fa-cog fa-spin fa-3x"></i> Calculating Cumulative Reads</div>
          			  		<div id="sequencingrate" style="height:400px;"><i class="fa fa-cog fa-spin fa-3x"></i> Calculating Sequencing Rates.</div>
                              <div id="ratio2dtemplate" style="height:400px;"><i class="fa fa-cog fa-spin fa-3x"></i> Calculating Ratio 2D to Template.</div>
                              <?php if ($_SESSION['focusBASE'] > 0) {?>
                              <div id="ratiopassfail" style="height:400px;"><i class="fa fa-cog fa-spin fa-3x"></i> Calculating Pass Fail Reads.</div>
          					<div id="readrate" style="width:100%; height:400px;"><i class="fa fa-cog fa-spin fa-3x"></i> Calculating Read Rate</div>
          					<div id="averagelength" style="width:100%; height:400px;"><i class="fa fa-cog fa-spin fa-3x"></i> Calculating Read Length Over Time</div>
          					<div id="averagetime" style="width:100%; height:400px;"><i class="fa fa-cog fa-spin fa-3x"></i> Calculating Time To Complete Reads</div>
                              <?php }; ?>
          			  </div>
          			</div>
                          <!-- /.col-lg-12 -->


                </div>
                <!-- /.tab-content -->
              </div>
              <!-- nav-tabs-custom -->
            </div>
            <!-- /.col -->


            <!-- /.col -->
          </div>
        </div>
    </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <?php include 'includes/reporting-new.php'; ?>
      <script>
  		$(document).ready(function() {
  		    var options = {
                  chart: {
  		            renderTo: 'cumulativeyield',
  					zoomType: 'x',
  		            type: 'spline',
  		        },
  		        title: {
  		          text: 'Cumulative Reads'
  		        },
  		        resetZoomButton: {
                  position: {
                      // align: 'right', // by default
                      // verticalAlign: 'top', // by default
                      x: -10,
                      y: 10
                  },
                  relativeTo: 'chart'
              },
  		        plotOptions: {
  		        	spline: {
  					                animation: false,
  									marker: {
  							            enabled: false
  							        }

  				},



          },
  				xAxis: {
  					type: 'datetime',
  			            dateTimeLabelFormats: { // don't display the dummy year
                 				month: '%e. %b',
             				    year: '%b'
  				            },
  				            title: {
  				                text: 'Time (S)'
  				            }
  				        },
  						yAxis: [{
  				                labels: {
              				        align: 'right',
              	    			    x: -3
              	   				},

              	    			title: {
              	        			text: 'Cumulative Reads'
  				                },
  				                height: '100%',
  				                lineWidth: 1,
  				                min: 0
  				            }],
  								credits: {
  								    enabled: false
  								  },
  		        legend: {
  		        	title: {
                  text: 'Read Type <span style="font-size: 9px; color: #666; font-weight: normal">(Click to hide)</span>',
                  style: {
                      fontStyle: 'italic'
                  }
              },

  		            layout: 'horizontal',
  		            align: 'center',
  		            //verticalAlign: 'middle',
  		            borderWidth: 0
  		        },
  		        series: []
  		    };

  		    $.getJSON('jsonencode/cumulativeyield.php?prev=0&callback=?', function(data) {
  				//alert("success");
  		        options.series = data; // <- just assign the data to the series property.



  		        //options.series = JSON2;
  				var chart = new Highcharts.Chart(options);
  				});
  		});

  			//]]>

  			</script>

              <script>
              		$(document).ready(function() {
              		    var options = {
              		        chart: {
              		            renderTo: 'sequencingrate',
              					zoomType: 'x',
              		            type: 'spline',
              		        },
              		        title: {
              		          text: 'Sequencing Rate'
              		        },
              		        resetZoomButton: {
                              position: {
                                  // align: 'right', // by default
                                  // verticalAlign: 'top', // by default
                                  x: -10,
                                  y: 10
                              },
                              relativeTo: 'chart'
                          },
              		        plotOptions: {
              		        	spline: {
              					                animation: false,
              									marker: {
              							            enabled: false
              							        }

              				},



                      },
              				xAxis: {
              					type: 'datetime',
              			            dateTimeLabelFormats: { // don't display the dummy year
                             				month: '%e. %b',
                         				    year: '%b'
              				            },
              				            title: {
              				                text: 'Time/Date'
              				            }
              				        },
              						yAxis:
              							{

              				                labels: {
                          				        align: 'right',
                          	    			    x: -3
                          	   				},

                          	    			title: {
                          	        			text: 'Bases/Second'
              				                },
              				                height: '100%',
              				                lineWidth: 1,
              				                min: 0
              				            },
              								credits: {
              								    enabled: false
              								  },
              		        legend: {
              		        	title: {
                              text: 'Read Type <span style="font-size: 9px; color: #666; font-weight: normal">(Click to hide)</span>',
                              style: {
                                  fontStyle: 'italic'
                              }
                          },

              		            layout: 'horizontal',
              		            align: 'center',
              		            //verticalAlign: 'middle',
              		            borderWidth: 0
              		        },
              		        series: []
              		    };

                 			$.getJSON('jsonencode/sequencingrate.php?prev=0&callback=?', function(data) {

                          options.series = data; // <- just assign the data to the series property.

                          //options.series = JSON2;
                          var chart = new Highcharts.Chart(options);

              });


              			});

              				//]]>

              </script>

  			<script>

  			$(document).ready(function() {
  			    var options = {
  			        chart: {
  			            renderTo: 'ratiopassfail',
  						//zoomType: 'x'
  			            type: 'spline'
  			        },
  					plotOptions: {
  					            spline: {
  					                animation: false,
                                      marker:{
                                          enabled: false,
                                      }

  					            }
  					        },
  			        title: {
  			          text: '2d, Complement and Template Pass/Fail Counts in 15 minute windows'
  			        },
  					xAxis: {
  						type: 'datetime',
  			            dateTimeLabelFormats: { // don't display the dummy year
                 				month: '%e. %b',
             				    year: '%b'
  				            },
  					            title: {
  					                text: 'Time/Date'
  					            }
  					        },
  							yAxis: {
  							            title: {
  							                text: 'Reads'
  							            },
  							            min: 0
  							        },
  									credits: {
  									    enabled: false
  									  },
  			        legend: {
  			            layout: 'horizontal',
                                          align: 'center',
                                          verticalAlign: 'bottom',
  			            borderWidth: 0
  			        },
  			        series: []
  			    };

  			    $.getJSON('jsonencode/ratiopassfail.php?prev=0&callback=?', function(data) {
  				//alert("success");
  		        options.series = data; // <- just assign the data to the series property.



  		        //options.series = JSON2;
  				var chart = new Highcharts.Chart(options);
  				});
  		});



  				//]]>

  </script>




  <script>

  			$(document).ready(function() {
  			    var options = {
  			        chart: {
  			            renderTo: 'ratio2dtemplate',
  						//zoomType: 'x'
  			            type: 'spline'
  			        },
  					plotOptions: {
  					            spline: {
  					                animation: false,
                                      marker: {
                                          enabled: false,
                                      }

  					            }
  					        },
  			        title: {
  			          text: '2d, Complement and Template reads in 15 minute windows'
  			        },
  					xAxis: {
  						type: 'datetime',
  			            dateTimeLabelFormats: { // don't display the dummy year
                 				month: '%e. %b',
             				    year: '%b'
  				            },
  					            title: {
  					                text: 'Time/Date'
  					            }
  					        },
  							yAxis: {
  							            title: {
  							                text: 'Reads'
  							            },
  							            min: 0
  							        },
  									credits: {
  									    enabled: false
  									  },
  			        legend: {
  			            layout: 'horizontal',
                                          align: 'center',
                                          verticalAlign: 'bottom',
  			            borderWidth: 0
  			        },
  			        series: []
  			    };
  			    $.getJSON('jsonencode/ratio2dtemplate.php?prev=0&callback=?', function(data) {
  				//alert("success");
  		        options.series = data; // <- just assign the data to the series property.



  		        //options.series = JSON2;
  				var chart = new Highcharts.Chart(options);
  				});
  		});

  				//]]>

  </script>





  	<script>

  $(document).ready(function() {
      var options = {
  			        chart: {
  			            renderTo: 'readrate',
  						zoomType: 'x'
  			            //type: 'line'
  			        },
  					plotOptions: {
  					            line: {
  					                animation: false

  					            }
  					        },
  			        title: {
  			          text: 'Rate Of BaseCalling'
  			        },
  					xAxis: {
  					type: 'datetime',
  			            dateTimeLabelFormats: { // don't display the dummy year
                 				month: '%e. %b',
             				    year: '%b',
  				            },
  				            title: {
  				                text: 'Time/Date'
  				            }
  				        },
  							yAxis: {
  							            title: {
  							                text: 'Reads/Minute'
  							            },
  							            min: 0
  							        },
  									credits: {
  									    enabled: false
  									  },
  			        legend: {
  		        	title: {
                  text: 'Read Type <span style="font-size: 9px; color: #666; font-weight: normal">(Click to hide)</span>',
                  style: {
                      fontStyle: 'italic'
                  }
              },
  			            layout: 'horizontal',
                                          align: 'center',
                                          verticalAlign: 'bottom',
  			            borderWidth: 0
  			        },
  			        series: []
  			    };
      $.getJSON('jsonencode/reads_over_time2.php?prev=0&callback=?', function(data) {
  		//alert("success");
          options.series = data; // <- just assign the data to the series property.



          //options.series = JSON2;
  		var chart = new Highcharts.Chart(options);
  		});
  });

  	//]]>

  	</script>

  	<script>

  	$(document).ready(function() {
  	    var options = {
  	        chart: {
  	            renderTo: 'averagelength',
  				zoomType: 'x'
  	            //type: 'line'
  	        },
  	        title: {
  	          text: 'Average Read Length Over Time'
  	        },
  			xAxis: {
  				type: 'datetime',
  			            dateTimeLabelFormats: { // don't display the dummy year
                 				month: '%e. %b',
             				    year: '%b',
  				            },
  			            title: {
  			                text: 'Time/Date'
  			            }
  			        },
  					yAxis: {
  					            title: {
  					                text: 'Average Read Length'
  					            },
  					            min: 0
  					        },

  							credits: {
  							    enabled: false
  							  },
  	        legend: {
  	            title: {
                  text: 'Read Type <span style="font-size: 9px; color: #666; font-weight: normal">(Click to hide)</span>',
                  style: {
                      fontStyle: 'italic'
                  }
              },
  			            layout: 'horizontal',
                                          align: 'center',
                                          verticalAlign: 'bottom',
  			            borderWidth: 0
  	        },
  	        series: []
  	    };

  	    $.getJSON('jsonencode/average_length_over_time.php?prev=0&callback=?', function(data) {
  			//alert("success");
  	        options.series = data; // <- just assign the data to the series property.



  	        //options.series = JSON2;
  			var chart = new Highcharts.Chart(options);
  			});
  	});

  		//]]>

  		</script>

  		<script>

  	$(document).ready(function() {
  	    var options = {
  	        chart: {
  	            renderTo: 'averagetime',
  				zoomType: 'x'
  	            //type: 'line'
  	        },
  	        title: {
  	          text: 'Average Time to process Reads Over Time'
  	        },
  			xAxis: {
  				type: 'datetime',
  			            dateTimeLabelFormats: { // don't display the dummy year
                 				month: '%e. %b',
             				    year: '%b',
  				            },
  			            title: {
  			                text: 'Time/Date'
  			            }
  			        },
  					yAxis: {
  					            title: {
  					                text: 'Average Time To Process Read (s)'
  					            },
  					            min: 0
  					        },
  							credits: {
  							    enabled: false
  							  },
  	        legend: {
  	            title: {
                  text: 'Read Type <span style="font-size: 9px; color: #666; font-weight: normal">(Click to hide)</span>',
                  style: {
                      fontStyle: 'italic'
                  }
              },
  			            layout: 'horizontal',
                                          align: 'center',
                                          verticalAlign: 'bottom',
  			            borderWidth: 0

  	        },
  	        series: []
  	    };

  	    $.getJSON('jsonencode/average_time_over_time2.php?prev=0&callback=?', function(data) {
  			//alert("success");
  	        options.series = data; // <- just assign the data to the series property.



  	        //options.series = JSON2;
  			var chart = new Highcharts.Chart(options);
  			});
  	});

  		//]]>

  		</script>


         <script>


             $.getJSON('http://www.nottingham.ac.uk/~plzloose/minoTourhome/message.php?callback=?', function(result) {

                       $.each(result, function(key,value){
                          //checking version info.
                          if (key == 'version'){
                              if (value == '<?php echo $_SESSION['minotourversion'];?>'){
                                  $('#newstarget').html("You are running the most recent version of minoTour - version "+value+".<br>");
                              }else if (value < '<?php echo $_SESSION['minotourversion'];?>'){
                                  $('#newstarget').html("You appear to be in the fortunate position of running a future version of the minoTour web application "+value+". If you have modified the code yourself - great. If not then there might be an issue somewhere!.<br>");
                              }else if (value > '<?php echo $_SESSION['minotourversion'];?>'){
                                  $('#newstarget').html("You are running an outdated version of the minoTour web application. The most recent version of minoTour is version "+value+".<br>"+"Instructions for upgrading will be posted below.<br>");
                              }


                          }else if (key.substring(0, 7) == 'message') {
                              $('#newstarget').append(value + "<br>");
                            }
                       });
                     });

         </script>




  </body>
</html>
<?php
} else {

	    // the user is not logged in. you can do whatever you want here.
	    // for demonstration purposes, we simply show the "you are not logged in" view.
	    include("views/not_logged_in.php");
	}

	?>
