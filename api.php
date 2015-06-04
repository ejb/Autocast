<?php
    
$factiva_key = getenv( 'FACTIVA_KEY' );
$articleRefs = fetchArticleList();
$articles = [];
$CharNum = 700;
foreach ($articleRefs as $index => $article) {
    cleanArticle( fetchArticle( $article['ref'], $CharNum ) );
    array_push( $articles, $article );
}
echo json_encode( $articles );


function fetchArticleList(){
    
}

function CleanArticle($Article, $CharNumber) {
	//We parse the dirty article  
	$ArticleString = file_get_contents($Article);
	$ArticleParsed = json_decode($ArticleString, true);

	//We start with a blank article
	$CleanArt = "";

	//We move through each paragraph
	foreach($ArticleParsed["Articles"][0]["Body"] as $index => $ArticlesAux) {
	    
	    //We only want the first CharNumber characters or so of the article
	    if (strlen($CleanArt) < $CharNumber) {
	           $CleanArt .= $ArticlesAux["Items"][0]["Value"]; 
	    }
	    else {
	        break;
	    }
		    
	     //We check for errors when the paragraph doesn't end in a full stop
	     if(substr($CleanArt, -1) != ".") {
		   //We fix the issues 	        
		   $CleanArt .= $ArticlesAux["Items"][1]["Name"];
		   $CleanArt .= $ArticlesAux["Items"][2]["Value"];
	      }
		 
             //Add a space at the end of each paragraph.
	     $CleanArt .= " ";        
	}  	   
	
	//We return a clean article as a string
	return $CleanArt;
}



function fetchArticle($ref){
    
}

?>
