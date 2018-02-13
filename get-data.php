<?php
/**
* The Purpose of the document is to separate the functions of the application from the resulting page.
* Doing so makes the code much easier to read within the application and makes it much more modular.
*
* @author Dylan Sheffer
* @version 1.0
*/


/**
* generateCocktail takes in a raw cocktail entry returned from the database and formats it into the
* resulting HTML.
*
* @param $row The raw cocktail entry
* @return $htmlOutput The formatted HTML for that particular cocktail
*/
function generateCocktail( $row )
{
    $htmlOutput = '<a data-toggle="collapse" data-parent="#accordian" href="#' . idFriendly( $row[ Cocktail_name ] ) . '-recipe">
  <div style="background: url(&#39;' . $row[ Image_url ] . '&#39;) no-repeat center center scroll;" class="image-button col-md-3 result-image">
   ' . $row[ Cocktail_name ] . '
  </div>
    </a>';
    
    return $htmlOutput;
    
}


/**
* generateDetails takes in a raw cocktail details returned from the database and formats it into the
* resulting HTML. This differs from the previous function because the recipe detail query is a 
* separate query about one specific cocktail.
*
* @param $detailRow The cocktail name (primary key)
* @return $htmlOutput The formatted HTML for that particular cocktail detail
*/
function generateDetails( $detailRow )
{
    $htmlOutput = '<div class="row recipe-detail text-left">
          <div class="col-md-4">
            <h3 class="lead">Ingredients</h3>
            <ul>' . fixIngredients( $detailRow[ Ingredients ] ) . '</ul>
          </div>
          <div class="col-md-8">
            <h3 class="lead">Instructions</h3> <ol>' . fixInstructions( $detailRow[ Instructions ] ) . ' </ol>
          </div>
          <div class="col-md-12 text-right">
            <p class="small">Recipie provided by <a href="' . $detailRow[ Recipe_url ] . '">' . $detailRow[ Recipe_source ] . '</a></p>
          </div></div>';
    
    return $htmlOutput;
}


?>