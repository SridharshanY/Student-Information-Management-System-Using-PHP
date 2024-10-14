<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Performance Management System</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="css/prism/prism.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <script src="js/modernizr/modernizr.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>
        <div class="main-wrapper">
            <div class="content-wrapper">
                <div class="content-container">

         
                    <!-- /.left-sidebar -->

                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-12">
                                    <h2 class="title" align="center">Performance Management System</h2>
                                </div>
                            </div>
                            <!-- /.row -->
                          
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->

                        <section class="section" id="exampl">
                            <div class="container-fluid">

                                <div class="row">
                              
                             

                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <h3 align="center">Student Result Details</h3>
                                                    <hr />
<?php
// code Student Data
$rollid=$_POST['rollid'];
$_SESSION['rollid']=$rollid;
$qery = "SELECT   tblstudents.StudentName,tblstudents.RollId,tblstudents.RegDate,tblstudents.StudentId,tblstudents.Status,tblclasses.ClassName,tblclasses.Section from tblstudents join tblclasses on tblclasses.id=tblstudents.ClassId where tblstudents.RollId=:rollid";
$stmt = $dbh->prepare($qery);
$stmt->bindParam(':rollid',$rollid,PDO::PARAM_STR);
$stmt->execute();
$resultss=$stmt->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($stmt->rowCount() > 0)
{
foreach($resultss as $row)
{   ?>
<p><b>Student Name :</b> <?php echo htmlentities($row->StudentName);?></p>
<p><b>Student Roll Id :</b> <?php echo htmlentities($row->RollId);?>
<?php break;}

    ?>
                                            </div>
                                            <div class="panel-body p-20">







                                                <table class="table table-hover table-bordered" border="1" width="100%">
                                                <thead>
                                                        <tr style="text-align: center">
                                                            <th style="text-align: center">Semester</th>
                                                            <th style="text-align: center"> Department</th>   
                                                            <th style="text-align: center">Attendance</th>															
                                                            <th style="text-align: center">Score</th>
                                                        </tr>
                                               </thead>
  


                                                	
                                                	<tbody>
<?php                                              
// Code for result

 $query ="select t.StudentName,t.RollId,t.ClassId,t.marks,t.marks,t.attendance,SubjectId,tblsubjects.SubjectName from (select sts.StudentName,sts.RollId,sts.ClassId,tr.marks,tr.attendance,SubjectId from tblstudents as sts join  tblresult as tr on tr.StudentId=sts.StudentId) as t join tblsubjects on tblsubjects.id=t.SubjectId where (t.RollId=:rollid)";
$query= $dbh -> prepare($query);
$query->bindParam(':rollid',$rollid,PDO::PARAM_STR);
$query-> execute();  
$results = $query -> fetchAll(PDO::FETCH_OBJ);
$cnt=1;
$x=0;
if($countrow=$query->rowCount()>0)
{ 
foreach($results as $result){

    ?>

                                                		<tr>
<th scope="row" style="text-align: center"><?php echo htmlentities($cnt);?></th>
<td style="text-align: center"><?php echo htmlentities($result->SubjectName);?></td>
<td style="text-align: center"><?php echo htmlentities($attendance=$result->attendance);?> %</td>
<td style="text-align: center"><?php echo htmlentities($totalmarks=$result->marks);?></td>
                                                		</tr>
<?php 
$mark[$x]=$totalmarks;
$totlcount+=$totalmarks;
$x++;
$cnt++;}
?>
<tr>
<th scope="row" colspan="3" style="text-align: center">Total Marks</th>
<td style="text-align: center"><b><?php echo htmlentities($totlcount); ?></b> out of <b><?php echo htmlentities($outof=($cnt-1)*100); ?></b></td>
                                                        </tr>
<tr>
<th scope="row" colspan="3" style="text-align: center">Percntage</th>           
<td style="text-align: center"><b><?php echo  htmlentities($totlcount*(100)/$outof); ?> %</b></td>
</tr>
<tr><td colspan="1" align="center">
    <canvas id="myChart" width="300" height="200"></canvas></td>
	
	<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['First', 'Second', 'Third', 'Fourth', 'Fifth', 'Sixth', 'Seventh', 'Eighth'],
            datasets: [{
                label: 'Score',
                data: [<?php echo $mark[0];?>, <?php echo $mark[1];?>, <?php echo $mark[2];?>, <?php echo $mark[3];?>, <?php echo $mark[4];?>, <?php echo $mark[5];?>, <?php echo $mark[6];?>, <?php echo $mark[7];?>],
                backgroundColor: [
                    'red'
                ],
				
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<td>
<canvas id="myPieChart" width="200" height="200"></canvas></td>
 <script>
        var ctx = document.getElementById('myPieChart').getContext('2d');
        var data = {
            labels: ['Overall'],
            datasets: [{
                data: [<?php echo $totlcount?>, <?php echo $xy=$outof-$totlcount?>],
                backgroundColor: ['red', 'blue'],
            }]
        };
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: data,
        });
    </script>
<td colspan="2">
<canvas id="pieChart" width="200" height="200"></canvas></td>
 <script>
        var ctx = document.getElementById('pieChart').getContext('2d');
        var data = {
            labels: ['Attendance'],
            datasets: [{
                data: [<?php echo $attendance?>, <?php echo $ab=(100-$attendance)?>],
                backgroundColor: ['green', 'red'],
            }]
        };
        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: data,
        });
    </script>

</tr>
<tr>
                              
<td colspan="4" align="center"><i class="fa fa-print fa-2x" aria-hidden="true" style="cursor:pointer" OnClick="printPage()" ></i></td>
                     <script>
        function printPage() {
            window.print();
        }
    </script>                                            </tr>

 <?php } else { ?>     
<div class="alert alert-warning left-icon-alert" role="alert">
                                            <strong>Notice!</strong> Your result not declare yet
 <?php }
?>
                                        </div>
 <?php 
 } else
 {?>

<div class="alert alert-danger left-icon-alert" role="alert">
<strong>Oh snap!</strong>
<?php
echo htmlentities("Invalid Roll Id");
 }
?>
                                        </div>



                                                	</tbody>
                                                </table>

                                            </div>
                                        </div>
                                        <!-- /.panel -->
                                    </div>
                                    <!-- /.col-md-6 -->
                                    <div class="form-group">
                                                           
                                                            <div class="col-sm-6">
                                                               <a href="index.php">Back to Home</a>
                                                            </div>
                                                        </div>

                                </div>
                                <!-- /.row -->
  
                            </div>
                            <!-- /.container-fluid -->
                        </section>
                        <!-- /.section -->

                    </div>
                    <!-- /.main-page -->

                  
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->

        </div></div>
        <!-- /.main-wrapper -->

        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/pace/pace.min.js"></script>
        <script src="js/lobipanel/lobipanel.min.js"></script>
        <script src="js/iscroll/iscroll.js"></script>

        <!-- ========== PAGE JS FILES ========== -->
        <script src="js/prism/prism.js"></script>

        <!-- ========== THEME JS ========== -->
        <script src="js/main.js"></script>
        <script>
            $(function($) {

            });


            function CallPrint(strid) {
var prtContent = document.getElementById("exampl");
var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
WinPrint.document.write(prtContent.innerHTML);
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();
WinPrint.close();
}
</script>

        </script>

        <!-- ========== ADD custom.js FILE BELOW WITH YOUR CHANGES ========== -->

    </body>
</html>
