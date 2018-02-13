<?php
/**
* The Purpose of the document is to have functions pertaining to the correct formatting
* of data for different uses such as HTML IDs.
*
* @author Dylan Sheffer
* @version 1.0
*/


/**
* idFriendly is a function that formats text into something that can be used for
* HTML IDs. Its primary use is to transform cocktail names into a usuable format.
*
* @param $string The string that will be formatted
* @return $newString The formated ID-ready string
*/
function idFriendly( $string )
{
    $string    = str_replace( " ", "", $string );
    $newString = "";
    for ( $i = 0; $i < strlen( $string ); $i++ ) {
        if ( is_numeric( $string[ $i ] ) ) {
            $newString .= convertDigit( $string[ $i ] );
        } //is_numeric( $string[ $i ] )
        else {
            $newString .= $string[ $i ];
        }
        
    } //$i = 0; $i < strlen( $string ); $i++
    $newString = preg_replace( '/[^A-Za-z0-9\-]/', '', $newString );
    
    return $newString;
}


/**
* convertDigit takes in a number and returns its corresponding text equivalent.
* It's used in the idFriendly function
*
* @param $digit is the number that will be converted
* @return The text version of the number passed in
*/
function convertDigit( $digit )
{
    switch ( $digit ) {
        case "0":
            return "zero";
        case "1":
            return "one";
        case "2":
            return "two";
        case "3":
            return "three";
        case "4":
            return "four";
        case "5":
            return "five";
        case "6":
            return "six";
        case "7":
            return "seven";
        case "8":
            return "eight";
        case "9":
            return "nine";
    } //$digit
}


/**
* fixIngredients takes in the raw ingredients data from the database that is delimited
* with a '|' and returns formatted list of ingredients
*
* @param $rawIngredients The unformatted ingredient data
* @return $ingredients The formatted list of ingredients
*/
function fixIngredients( $rawIngredients )
{
    $ingArr      = explode( "|", $rawIngredients );
    $ingredients = "";
    foreach ( $ingArr as $ing ) {
        $ingredients .= "<li>" . ltrim( $ing ) . "</li>";
    } //$ingArr as $ing
    
    return $ingredients;
    
}


/**
* fixInstruction takes in the raw instruction data from the database that is delimited
* with a '+' and returns formatted list of instructions
*
* @param $rawInstuctions The unformatted instruction data
* @return $instructions The formatted list of instructions
*/
function fixInstructions( $rawInstuctions )
{
    $insArr       = explode( "+", $rawInstuctions );
    $instructions = "";
    foreach ( $insArr as $ins ) {
        $instructions .= "<li>" . ltrim( $ins ) . "</li>";
    } //$insArr as $ins
    
    return $instructions;
    
}
?>