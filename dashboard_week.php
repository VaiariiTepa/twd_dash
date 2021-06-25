<?php
    require 'class/api.class.php';
    require 'class/apidata.class.php';

    $api = new twd();
    $db_api = new Dbapi();

    $label = array();
    $t_week = array();
    $final_data = '';

    $c_tahiti = array();
    $f_tahiti = array();
    $c_moorea = array();
    $f_moorea = array();

    // count
    $label_count = 1;


    $T = "T";
    $M = "M";

    // if boutton click
    if(isset($_POST['btn'])){
        
        if($_POST['btn'] == "récup"){
            
            // period of day
            $p_start = $_POST['start_day'];
            $p_end = $_POST['end_day'];
            
            // specific day
            $p_day = $_POST['day'];

            // delete all data from twd table
            $db_api->truncate();

            // get data from api
            $j_data = $api->getData($p_start, $p_end);            

            //specific day
            $period = floor((strtotime($p_end) - strtotime($p_start))/(24*60*60));
            
            // loop for specific date
            for($i = 0; $i < $period; $i++){
                if(in_array(date('l',strtotime("$p_start +$i day")),[$p_day])){
                    //specific date on period selected
                    $specific_date = date('Y-m-d',strtotime("$p_start +$i day"));
                    
                    //$specific_date to Date() format 
                    $date_format = new DateTime($specific_date);
                    
                    // format date to natural language
                    $label_date = $date_format->format('l j M');
                    // initialize label of graphique
                    array_push($label, $label_date);

                    // format date to day natural language
                    $week = $date_format->format('l');

                    // initialize array of weeks
                    // use to verify if is the week or not
                    array_push($t_week, $week);

                    // loop to list of data api
                    foreach($j_data as $date =>$va){

                        
                        if($specific_date == $date){

                            foreach($j_data[$date]["T"] as $stime => $value){
            
                                if(isset($value['CATAM'])){

                                    $percent = round($value['CATAM']['place_dispo']);
                                    if ($percent < 0) {
                                        $percent = 0;
                                    }
            
                                    // insert into db
                                    $db_api->insertdata($T,$value['CATAM']['code_societe'],$percent,$date);
                                        
                                }else{

                                    $percent = round($value['FERRY']['place_dispo']);
                                    if ($percent < 0) {
                                        $percent = 0;
                                    }
            
                                    // insert into db
                                    $db_api->insertdata($T,$value['FERRY']['code_societe'],$percent,$date);
                                }   
                            }

                            foreach($j_data[$date]["M"] as $stime => $value){           
                                if(isset($value['CATAM'])){
                                    $percent = round($value['CATAM']['place_dispo']);
                                    if ($percent < 0) {
                                        $percent = 0;
                                    }
                                        // insert into db
                                    $db_api->insertdata($M,$value['CATAM']['code_societe'],$percent,$date);
                                        
                                }else{
                                    $percent = round($value['FERRY']['place_dispo']);
                                    if ($percent < 0) {
                                        $percent = 0;
                                    }
                                        // insert into db
                                    $db_api->insertdata($M,$value['FERRY']['code_societe'],$percent,$date);
                                    
                                }
                            }
                        }
                    }
                }   

            }
        }
        //$specific_date to Date() format 
        $p_start = new DateTime($p_start);
        // format date to natural language
        $p_start = $p_start->format('Y-m-d');

        //$specific_date to Date() format 
        $p_end = new DateTime($p_end);
        // format date to natural language
        $p_end = $p_end->format('Y-m-d');
    }


    $list_db_api = $db_api->getdata();
    
    // loop list day travel
    foreach ($label as $key => $date) {
        
        // loop list of all travel
        while ($row = mysqli_fetch_object($list_db_api)) {
            
            // selection par ile
            if($row->ile == "T"){
    
                // selection par type bateau
                if ($row->code_societe == "CATAM") {
                    $total_date = $row->count_date;
                    $total_car_travel_day = 4 * $total_date;
                    $taux_final = $row->y / $total_car_travel_day;
                    $taux_final = $taux_final * 100;
                    $taux_final = (100 - $taux_final);
                    $taux_final = round($taux_final);
                    array_push($c_tahiti,$taux_final);
                    // array_push($c_tahiti, array("y"=> $taux_final));
                }elseif($row->code_societe == "FERRY"){

                    
                    $total_date = $row->count_date;
    
                    // selection taux max selon week || semaine
                    if(in_array('Friday',$t_week)||in_array('Saturday',$t_week)||in_array('Sunday',$t_week)){
                        
                        $total_car_travel_day = 133 * $total_date;
                    }else{
                        
                        $total_car_travel_day = 86 * $total_date;
                    }
    
                    // calcul taux en %
                    $taux_final = $row->y / $total_car_travel_day;
                    $taux_final = $taux_final * 100;
                    $taux_final = (100 - $taux_final);
    
                    // arrondir
                    $taux_final = round($taux_final);
    
                    // $f_tahiti = $taux_final;
                    // alimenté le tableaux f_tahiti
                    array_push($f_tahiti,$taux_final);
                }else{
                    array_push($f_tahiti,$taux_final);

                }
            
            // sinon Moorea
            }else{
    
                if ($row->code_societe == "CATAM") {

    
                    $total_date = $row->count_date;
                    $total_car_travel_day = 4 * $total_date;
                    $taux_final = $row->y / $total_car_travel_day;
                    $taux_final = $taux_final * 100;
                    $taux_final = (100 - $taux_final);
                    $taux_final = round($taux_final);
                    // $c_moorea = $taux_final;
                    array_push($c_moorea,$taux_final);
                }else{


    
                    $total_date = $row->count_date;
                    if(in_array('Friday',$t_week)||in_array('Saturday',$t_week)||in_array('Sunday',$t_week)){
    
                        $total_car_travel_day = 133 * $total_date;
                    }else{
    
                        $total_car_travel_day = 86 * $total_date;
                    }
                    $taux_final = $row->y / $total_car_travel_day;
                    $taux_final = $taux_final * 100;
                    $taux_final = (100 - $taux_final);
                    $taux_final = round($taux_final);
                    // $f_moorea = $taux_final;
                    array_push($f_moorea,$taux_final);
                }
            }
    
        }

        if(empty($f_tahiti[$key])){
            $f_tahiti[$key] = 0;
        }
        if(empty($f_moorea[$key])){
            $f_moorea[$key] = 0;
        }
        $final_data.= "['".$date."',".intval($c_tahiti[$key]).",".intval($c_moorea[$key]).",".intval($f_tahiti[$key]).",".intval($f_moorea[$key])."],";
    }
    

    
?>


<!-- #############################-----HTML-----############################# -->

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./assets/css/bootstrap.min.css" rel="stylesheet">

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
                <div class="col-md-5">
                    <div class="container">
                        <div class="jumbotron">
                            <div class="card mb-2">
                                
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="date"> Date Début : </label>
                                            <?php
                                                if(isset($p_start)){
                                            ?>
                                                    <input type="date" class="Dstart form-control" name="start_day" value="<?php echo $p_start; ?>" type="date">
                                            <?php
                                                }else{
                                            ?>
                                                  <input type="date" class="Dstart form-control" name="start_day" type="date">
                                            <?php
                                                }
                                            ?>
                                        </div>


                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="date"> Date Fin : </label>
                                            <?php
                                                if(isset($p_start)){
                                            ?>
                                                    <input type="date" class="Dend form-control" name="end_day" value="<?php echo $p_end; ?>" type="date">
                                            <?php
                                                }else{
                                            ?>
                                                <input type="date" class="Dend form-control" name="end_day" type="date">
                                            <?php
                                                }
                                            ?>
                                        </div>


                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="container">
                        <div class="jumbotron">
                            <div class="card mb-2">
                                <label for="date"> Jours </label>
                                <select name="day" class="form-control">
                                    <option value="Monday">
                                        lundi
                                    </option>
                                    <option value="Tuesday">
                                        mardi
                                    </option>
                                    <option value="Wednesday">
                                        mercredi
                                    </option>
                                    <option value="Thursday">
                                        jeudi
                                    </option>
                                    <option value="Friday">
                                        vendredi
                                    </option>
                                    <option value="Saturday">
                                        samedi
                                    </option>
                                    <option value="Sunday">
                                        dimanche
                                    </option>                  
                                </select>
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
        <div class="container-fluid">
            <div class="jumbotron">
                <!--Section: Demo-->
                <div class="card">
                    <section class="border  d-flex justify-content-center">
                            
                        <div class="col-lg-12">
                            <!-- <div id="chart-bar" style="height: 100%;" class="chart">
                                
                            </div> -->
                            <div id="columnchart_material" style="height: 500px;"></div>
                        </div>
                    </section>
                </div>
                <!--Section: Demo-->
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {'packages':['bar']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Jours','Catam Tahiti','Catam Moorea','Ferry Tahiti','Ferry Moorea'],
                    <?=$final_data ?>
                    
                    ]);

                var options = {
                chart: {
                    title: 'Voyage en périodes',
                    subtitle: 'Chiffres exprimes en %',
                }
                };

                var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                chart.draw(data, google.charts.Bar.convertOptions(options));
            }
        </script>

        <!-- Chart bar double datasets example -->
     
    </body>
</html>