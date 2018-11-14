<?php include "includes/admin_header.php";?>
<div id="wrapper"> 
    <!-- Navigation -->
    <?php include "includes/admin_navigation.php";?>
    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Welcome To The User Dashboard
                        <?php echo strtoupper(getUsername());?>
                    </h1>
                </div>
            </div>      
            <!-- /.row -->   
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file-text fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class='huge'><?php echo getAllUserRecordCount('posts');?></div>
                                    <div>Posts</div>
                                </div>
                            </div>
                        </div>
                        <a href="./posts.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class='huge'><?php echo getAllUserRecordCount('comments');?></div>
                                    <div>Comments</div>
                                </div>
                            </div>
                        </div>
                        <a href="./comments.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <script type="text/javascript">
                    google.charts.load('current', {'packages':['bar']});
                    google.charts.setOnLoadCallback(drawChart);
                    
                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                        ['Data', 'Count'],
                        <?php
                        $element_text = ['All Posts', 'Published Posts', 'Draft Posts', 'Comments', 'Approved Comments', 'Unapproved Comments'];
                        $element_count = [getAllUserRecordCount('posts'), getAllUserStatusCount('posts', 'post_status', 'published'), getAllUserStatusCount('posts', 'post_status', 'draft'), getAllUserRecordCount('comments'), getAllUserStatusCount('comments', 'comment_status', 'approved'), getAllUserStatusCount('comments', 'comment_status', 'unapproved')];
                        for($i = 0;$i < 6;$i++){
                            echo "['{$element_text[$i]}'" . "," . " {$element_count[$i]}],";
                        }
                        ?>
                        ]);
                        
                        var options = {
                            chart: {
                                title: '',
                                subtitle: '',
                            }
                        };
                    
                        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
                        
                        chart.draw(data, google.charts.Bar.convertOptions(options));
                    }
                </script>
                <div id="columnchart_material" style="width: 'auto'; height: 500px;"></div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div> 
<?php include "includes/admin_footer.php";?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://js.pusher.com/4.3/pusher.min.js"></script>    
<script>
    $(document).ready(function(){
       var pusher = new Pusher('9492261a29552d24f5be', {
          cluster: 'eu',
          encrypted: true 
       });
        var notificationChannel = pusher.subscribe('notifications');
        notificationChannel.bind('new_user', function(notification){
            var message = notification.message;
            toastr.success(`${message} just registered`);
            console.log(message);
        });
    });
</script>