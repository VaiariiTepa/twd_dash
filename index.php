<?php
    require 'class/api.class.php';
    require 'class/apidata.class.php';


    $api = new twd();
    $db_api = new Dbapi();


    $ct_label = array();
    $ft_label = array();
    $cm_label = array();
    $fm_label = array();
    $c_tahiti = array();
    $f_tahiti = array();
    $c_moorea = array();
    $f_moorea = array();

    $T = "T";
    $M = "M";

    if(isset($_POST['btn'])){

        if($_POST['btn'] == "récup"){

            $p_start = $_POST['day'];



            $db_api->truncate_travel();
            // get data from api
            $j_data = $api->getData($p_start, $p_start);

            //specific day




            foreach($j_data as $date =>$va){



                foreach($j_data[$date]["T"] as $stime => $value){
                    if(isset($value['CATAM'])){
                        $percent = round($value['CATAM']['place_dispo']);
                        if ($percent < 0) {
                            $percent = 0;
                        }
                        // insert into db
                        $db_api->insertdatatravel($T,$value['CATAM']['code_societe'],$percent,$date);

                    }else{
                    $percent = round($value['FERRY']['place_dispo']);
                    if ($percent < 0) {
                        $percent = 0;
                    }
                        // insert into db
                    $db_api->insertdatatravel($T,$value['FERRY']['code_societe'],$percent,$date);
                    }
                }

                foreach($j_data[$date]["M"] as $stime => $value){
                    if(isset($value['CATAM'])){
                        $percent = round($value['CATAM']['place_dispo']);
                        if ($percent < 0) {
                            $percent = 0;
                        }
                            // insert into db
                        $db_api->insertdatatravel($M,$value['CATAM']['code_societe'],$percent,$date);

                    }else{
                        $percent = round($value['FERRY']['place_dispo']);
                        if ($percent < 0) {
                            $percent = 0;
                        }
                            // insert into db
                        $db_api->insertdatatravel($M,$value['FERRY']['code_societe'],$percent,$date);

                    }
                }
            }

        }
        //$specific_date to Date() format 
        $date_format = new DateTime($p_start);

        // format date to natural language
        $p_start = $date_format->format('Y-m-d');
    }


    $list_data_ct = $db_api->getdata_ct();
    foreach ($list_data_ct as $key => $value) {
        $num_voyage[] = $key;
    }
    $list_data_ct = $db_api->getdata_ct();
    $list_data_ft = $db_api->getdata_ft();
    foreach ($list_data_ft as $key => $value) {
        $num_voyage_ferry[] = $key;
    }
    $list_data_ft = $db_api->getdata_ft();
    $list_data_cm = $db_api->getdata_cm();
    $list_data_fm = $db_api->getdata_fm();

    $total_ct = mysqli_num_rows($list_data_ct);
    $total_ft = mysqli_num_rows($list_data_ft);
    $total_cm = mysqli_num_rows($list_data_cm);
    $total_fm = mysqli_num_rows($list_data_fm);

    $count_catam = 0;
    $final_data_catam = '';
    
    foreach($num_voyage as $key){
        $key++;
        
        
        while ($row = mysqli_fetch_object($list_data_ct)) {
            
            $total_car_travel_day = 4 * $total_ct;
            $taux_final = $row->y / $total_car_travel_day;
            $taux_final = $taux_final * 100;
            $taux_final = (100 - $taux_final);
            $taux_final = round($taux_final);
            array_push($c_tahiti,$taux_final);
            
        }
        while ($row = mysqli_fetch_object($list_data_cm)) {
            
            $total_car_travel_day = 4 * $total_cm;
            $taux_final = $row->y / $total_car_travel_day;
            $taux_final = $taux_final * 100;
            $taux_final = (100 - $taux_final);
            $taux_final = round($taux_final);
            array_push($c_moorea, $taux_final);
        }
        $final_data_catam.= "['V".$key."',".intval($c_tahiti[$count_catam]).",".intval($c_moorea[$count_catam])."],";
        $count_catam++;
    }
    
    
    $count_ferry = 0;
    $final_data_ferry = '';
    
    foreach ($num_voyage_ferry as $key => $value) {
        $key++;
        while ($row = mysqli_fetch_object($list_data_ft)) {
            
            $total_car_travel_day = 86 * $total_ft;
            $taux_final = $row->y / $total_car_travel_day;
            $taux_final = $taux_final * 100;
            $taux_final = (100 - $taux_final);
            $taux_final = round($taux_final);
            array_push($f_tahiti, $taux_final);
            
        }
        
        
        while ($row = mysqli_fetch_object($list_data_fm)) {
            
            $total_car_travel_day = 86 * $total_fm;
            $taux_final = $row->y / $total_car_travel_day;
            $taux_final = $taux_final * 100;
            $taux_final = (100 - $taux_final);
            $taux_final = round($taux_final);
            array_push($f_moorea, $taux_final);
            
        }
        
        $final_data_ferry.= "['V".$key."',".intval($f_tahiti[$count_ferry]).",".intval($f_moorea[$count_ferry])."],";
        $count_ferry++;
    }


    
    
    ?>


<!-- #############################-----HTML-----############################# -->

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <title>Dashboard TWD</title>
        <style>
            .form-control {
                border-radius: 20px;
            }


            .card{
                padding: 20px;
                border-radius: 50px;
                box-shadow: 2px 3px 10px grey;
            }
        </style>

    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Taux d'occupation en véhicules</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Journalier</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard_week.php">Période</a>
                    </li>
                </ul>
            </div>
        </nav>
        <form action="" method="post">

            <div class="row pt-5" style="justify-content: center;">
                <div class="col-md-4">
                    <div class="container">
                        <div class="jumbotron">
                            <div class="card mb-2">
                                <div class="form-group">
                                    <label for="date"> Choix date </label>
                                    <?php
                                        if(isset($p_start)){
                                    ?>
                                            <input type="date" class="Dend form-control" name="day" value="<?php echo $p_start; ?>" type="date">
                                    <?php
                                        }else{
                                    ?>
                                        <input type="date" class="Dend form-control" name="day" type="date">
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 my-auto">
                    <div class="container">
                        <div class="card">
                            <button type="submit" name="btn" class="bg-info form-control" value="récup">valider</button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
        <br>

        <div class="container">
            <div class="jumbotron">
                <!--Section: Demo-->
                <section class="border p-4 mb-4 d-flex justify-content-center">
                    <div class="card">
                        <div class="row mb-4">
                            <div id="lineChartCatam" class="col-lg-6" style="width: 600px;height: 400px;  ">
                                
                            </div>
                            <div id="lineChartFerry" class="col-lg-6" style="width: 600px;height: 400px;  ">
                                
                            </div>

                        </div>
                    </div>
                </section>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <script src="./assets/js/loader.js" type="text/javascript"></script>

        <!-- TAHITI -->
        <!-- CATAM -->
        <script type="text/javascript">

            google.charts.load('current', {'packages':['line']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Voyages dans la journée');
                data.addColumn('number', 'Catam Tahiti');
                data.addColumn('number', 'Catam Moorea');

                data.addRows([
                    <?=$final_data_catam ?>
                ]);

                var options = {
                    chart: {
                    title: 'CATAM'
                    },
                    colors: ['red', 'green']
                };

                var chart = new google.charts.Line(document.getElementById('lineChartCatam'));

                chart.draw(data, google.charts.Line.convertOptions(options));
            }
        </script>

        <script type="text/javascript">

            google.charts.load('current', {'packages':['line']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Voyages dans la journée');
                data.addColumn('number', 'Ferry Tahiti');
                data.addColumn('number', 'Ferry Moorea');

                data.addRows([
                    <?=$final_data_ferry ?>
                ]);

                var options = {
                    chart: {
                    title: 'FERRY'
                    },
                    colors: ['black', 'blue']
                };

                var chart = new google.charts.Line(document.getElementById('lineChartFerry'));

                chart.draw(data, google.charts.Line.convertOptions(options));
            }
        </script>

    </body>
</html>