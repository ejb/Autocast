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
	global $factiva_key;
	$arefs = [];
	$days = '1';
	$search = 'sc%3Awsjo';

	$url = "http://api.dowjones.com/api/public/2.0/content/headlines/json?EncryptedToken=$factiva_key&DaysRange=$days&SearchString=$search";

	$l = json_decode( file_get_contents( $url ), TRUE );
	$chunks = ceil ( $l["TotalRecords"] / 100 );
	$i = $chunks;

	//handle article list > 100
	while ($i > 0) {
	 	$offset = (string)($chunks - $i) * 100;
	 	$url_new = $url . "&Offset=$offset";
	 	$a = json_decode( file_get_contents( $url_new ), TRUE );
		foreach( $a["Headlines"] as $v) {
		array_push( $arefs, $v["ArticleRef"] );
	    }
	 	$i -= 1; 
	}

	return $arefs; 
    
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
	global $factiva_key;
	$url = "http://api.dowjones.com/api/public/2.0/Content/Article/ArticleRef/json?articleRef=$ref&encryptedToken=$factiva_key&ArticleFormat=FULL&Parts=RegionCodes|SubjectCodes|IndustryCodes";

	$a = json_decode( file_get_contents( $url ), TRUE);

	return $a;
}

?>
