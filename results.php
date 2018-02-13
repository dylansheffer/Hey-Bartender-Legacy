<?php
/**
* This document is the brain of the application. It queries the database, makes the function
* calls to generate the necessary HTML, and echos it to the screen.
*
* @author Dylan Sheffer
* @version 1.0
*/

$q          = strval( $_GET[ 'q' ] );
$servername = "localhost";
$username   = "root";
$password   = "cocktail";
$dbname     = "Cocktail_Engine";


// Create connection
$conn = new mysqli( $servername, $username, $password, $dbname );
// Check connection
if ( !$conn ) {
    die( 'Could not connect: ' . mysqli_error( $conn ) );
} //!$conn

mysqli_select_db( $conn, $dbname );

//Takes the CSV parameters the user selected and puts it into array
$qArray = explode( ",", $q );
?>

<body data-spy="scroll" data-target=".navbar-fixed-top" id="page-top">
    <!-- Navigation -->


    <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button class="navbar-toggle" data-target=
                ".navbar-main-collapse" data-toggle="collapse" type=
                "button"><i class="fa fa-bars"></i></button> <a class=
                "navbar-brand page-scroll" href="#page-top"><i class=
                "fa fa-glass"></i> <span class="light">Hey</span>
                Bartender.</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->


            <div class=
            "collapse navbar-collapse navbar-right navbar-main-collapse">
                <ul class="nav navbar-nav">
                    <!-- Hidden li included to remove active class from about link when scrolled up past about section -->


                    <li class="hidden">
                        <a href="http://www.dylansheffer.com/hey-bartender/"></a>
                    </li>


                    <li>
                        <a class="" href=
                        "http://www.dylansheffer.com/hey-bartender/">Recommender</a>
                    </li>


                    <li>
                        <a class="" data-toggle="modal" href=
                        "#credits">Credits</a>
                    </li>


                    <li>
                        <a class="" href=
                        "http://www.dylansheffer.com/contact.html">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    <!-- Results Section -->


    <section class="container content-section text-center setting result" id=
    "page-top">
        <div class="setting-body">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <h2>Here's What I Suggest.</h2>


                    <p>Based off of what you've been telling me, I think these
                    drinks will ease your thirst.</p>
                </div>
            </div>
        </div>
    </section>


    <div class="image-button-container panel-group" id="accordian">
        <div class="panel panel-default">   

<?php

/**
* The algorithm for displaying cocktails for the users is based off a 3-tiered query.
* First it will attempt to query the database using all 3 parameters, if nothing is returned,
* it tries the next query. 
* The second query, searches whether there are cocktails for both the Style and Base spirit. 
* If this doesn't work, it tries the third query. The final query just returns the user's selected base spirit.
*/


/** 1) Get Exact Results Back **/
include 'transform.php';
include 'get-data.php';

$initialSql    = "SELECT DISTINCT Cocktail_name, Image_url FROM Denorm WHERE Setting_Setting_name = '" . $qArray[ 0 ] . "' AND Base_Spirit_Base_Spirit_name = '" . $qArray[ 1 ] . "' AND Cocktail_Style_Cocktail_Style_Name = '" . $qArray[ 2 ] . "'";
$initialResult = mysqli_query( $conn, $initialSql );


if ( $initialResult->num_rows > 0 ) {
    $counter = 0;
    while ( $row = $initialResult->fetch_assoc() ) {
        $resultArray[ $counter ] = $row;
        $counter++;
    } //$row = $initialResult->fetch_assoc()
    
    foreach ( array_chunk( $resultArray, 4, true ) as $array ) {
        echo "<div class='row'>";
        foreach ( $array as $row ) {
            echo generateCocktail( $row );
        } //$array as $row
        echo '</div>';
        
        foreach ( $array as $row ) {
            $detailSql    = "SELECT DISTINCT Ingredients, Instructions, Recipe_url, Recipe_source FROM Denorm WHERE Cocktail_name = '" . $row[ Cocktail_name ] . "'";
            $detailResult = mysqli_query( $conn, $detailSql );
            
            echo '<div class="panel-collapse collapse" id="' . idFriendly( $row[ Cocktail_name ] ) . '-recipe">';
            if ( $detailResult->num_rows > 0 ) {
                while ( $detailRow = $detailResult->fetch_assoc() ) {
                    echo generateDetails( $detailRow );
                } //$detailRow = $detailResult->fetch_assoc()
            } //$detailResult->num_rows > 0
            echo '</div>';
        } //$array as $row
        
    } //array_chunk( $resultArray, 4, true ) as $array
    
} //$initialResult->num_rows > 0
else {

    /** 2) Find Cocktail with the same Base Spirit and Style **/
    
    $secondarySql = "SELECT DISTINCT Cocktail_name, Image_url FROM Denorm WHERE Base_Spirit_Base_Spirit_name = '" . $qArray[ 1 ] . "' AND Cocktail_Style_Cocktail_Style_Name = '" . $qArray[ 2 ] . "'";
    $result       = mysqli_query( $conn, $secondarySql );
    $counter      = 0;
    while ( $row = $result->fetch_assoc() ) {
        $resultArray[ $counter ] = $row;
        $counter++;
    } //$row = $result->fetch_assoc()
    
    //If there are Cocktails that match Style and Spirit
    if ( count( $resultArray ) != 0 ) {
        echo "<h3>I Couldn't Exactly Find What You're Looking For.</h3><p>Here's Some Similar Cocktails Based On Spirit and Style, or <a href='index.html'>Click Here To Try Again.</a></p>";
        
        foreach ( array_chunk( $resultArray, 4, true ) as $array ) {
            echo "<div class='row'>";
            foreach ( $array as $row ) {
                echo generateCocktail( $row );
            } //$array as $row
            echo '</div>';
            
            foreach ( $array as $row ) {
                $detailSql    = "SELECT DISTINCT Ingredients, Instructions, Recipe_url, Recipe_source FROM Denorm WHERE Cocktail_name = '" . $row[ Cocktail_name ] . "'";
                $detailResult = mysqli_query( $conn, $detailSql );
                
                echo '<div class="panel-collapse collapse" id="' . idFriendly( $row[ Cocktail_name ] ) . '-recipe">';
                if ( $detailResult->num_rows > 0 ) {
                    while ( $detailRow = $detailResult->fetch_assoc() ) {
                        echo generateDetails( $detailRow );
                    } //$detailRow = $detailResult->fetch_assoc()
                } //$detailResult->num_rows > 0
                echo '</div>';
            } //$array as $row
        } //array_chunk( $resultArray, 4, true ) as $array
    } //count( $resultArray ) != 0
    
    /** 3) There are No Cocktail that Matches the Spirt and Style, find one that matches only Spirit **/  
    else {
        //If a spirit was selected
        if ( $qArray[ 1 ] != NULL ) {
            $finalSql = "SELECT DISTINCT Cocktail_name, Image_url FROM Denorm WHERE Base_Spirit_Base_Spirit_name = '" . $qArray[ 1 ] . "'";
            $result   = mysqli_query( $conn, $finalSql );
            $counter  = 0;
            while ( $row = $result->fetch_assoc() ) {
                $resultArray[ $counter ] = $row;
                $counter++;
            } //$row = $result->fetch_assoc()
            
            echo "<h3>I Couldn't Find Exactly What You're Looking For.</h3><p>Here's Some Similar Cocktails Based On Your Spirit Preference, or <a href='http://www.dylansheffer.com/hey-bartender/'>Click Here To Try Again.</a></p>";
            
            foreach ( array_chunk( $resultArray, 4, true ) as $array ) {
                echo "<div class='row'>";
                foreach ( $array as $row ) {
                    echo generateCocktail( $row );
                } //$array as $row
                echo '</div>';
                
                foreach ( $array as $row ) {
                    $detailSql    = "SELECT DISTINCT Ingredients, Instructions, Recipe_url, Recipe_source FROM Denorm WHERE Cocktail_name = '" . $row[ Cocktail_name ] . "'";
                    $detailResult = mysqli_query( $conn, $detailSql );
                    
                    echo '<div class="panel-collapse collapse" id="' . idFriendly( $row[ Cocktail_name ] ) . '-recipe">';
                    if ( $detailResult->num_rows > 0 ) {
                        while ( $detailRow = $detailResult->fetch_assoc() ) {
                            echo generateDetails( $detailRow );
                        } //$detailRow = $detailResult->fetch_assoc()
                    } //$detailResult->num_rows > 0
                    echo '</div>';
                } //$array as $row
            } //array_chunk( $resultArray, 4, true ) as $array
        } //$qArray[ 1 ] != NULL
        //Base Spirit was not selected
        else {
            echo "<h3><a href='http://www.dylansheffer.com/hey-bartender/'>Please Select a Base Spirit and Try Again.</a></h3>";
        }
    }
}

mysqli_close( $conn );
?>

        </div>
</div>
    
    <footer class="footer">
        <div class="container text-center">
            <p>Copyright &copy; Dylan Sheffer 2016</p>
        </div>
    </footer>
   
    <!-- Credits -->
    <div aria-hidden="true" aria-labelledby="creditsLabel" class="modal fade"
    id="credits" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center">Credits.</h3>
                </div>


                <div class="modal-body">
                    <p class="text-center">This application was developed by
                    <b><a href="http://dylansheffer.com">Dylan Sheffer</a></b>
                    for his undergraduate capstone project.</p>

                    <hr>


                    <h5>Thank You!</h5>


                    <p>A big thank you is in order for every person that helped
                    me input data into the database. In no particular order
                    here is a list of all involved.</p>


                    <ul>
                        <li>Collin McHugh</li>


                        <li>Jeffery Lilliston</li>


                        <li>Bryan Petty</li>


                        <li>Timothy Giles</li>


                        <li>Sebastian Bosek</li>


                        <li>Kaitlin Isaac</li>


                        <li>Blair Stinson</li>


                        <li>Laura Fielden</li>
                    </ul>


                    <p>And a very special thank you to <b>Austin Schaffer</b>
                    for allowing us to use his webscraper to accomplish this
                    task.</p>

                    <hr>


                    <h5>Template</h5>


                    <p>The base of the website is the <b><a href=
                    "http://startbootstrap.com/template-overviews/grayscale/">Grayscale
                    template</a></b> developed by <b><a href=
                    "http://startbootstrap.com">StartBootstrap.com</a></b></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="js/jquery.js">
    </script> <!-- Bootstrap Core JavaScript -->
     
    <script src="js/bootstrap.min.js">
    </script> <!-- Plugin JavaScript -->
     
    <script src="js/jquery.easing.min.js">
    </script> <!-- Custom Theme JavaScript -->
     
    <script src="js/myScripts.js">
    </script>
</body>