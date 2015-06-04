<?php
    
$factiva_key = getenv( 'FACTIVA_KEY' );
$articleRefs = fetchArticleList();
$articles = [];
$CharNum = 700;
foreach ($articleRefs as $index => $ref) {
    $cleanArticle = cleanArticle( fetchArticle( $ref ), 300 );
    array_push( $articles, $cleanArticle );
}
file_put_contents( 'cached.json', json_encode( $articles ) );


function fetchArticleList(){
	global $factiva_key;
	$arefs = [];
	$days = '1';
	$search = 'sc%3Awsjo';

	$url = "http://api.dowjones.com/api/public/2.0/content/headlines/json?EncryptedToken=".$factiva_key."&DaysRange=".$days."&SearchString=".$search;
    
    echo $url;

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

function CleanArticle($ArticleParsed) {

	//We start with a blank article
	$CleanArt = "";

	//We only want the first $numgraf grafs
	$numgraf = 3;
	$a = array_slice($ArticleParsed["Articles"][0]["Body"], 0, $numgraf);

	//We move through each paragraph
	foreach($a as $index => $ArticlesAux) {
	    foreach($ArticlesAux["Items"] as $val) {
	    	if($val["Value"]){
	    		$CleanArt .= $val["Value"];
	    	}
	    	elseif ($val["Name"]) {
	    		$CleanArt .= $val["Name"];
	    	}
	    }

        //Add a space at the end of each paragraph.
	    $CleanArt .= " ";        
	}
    
    //get tags and other fun stuff, put it all in an array	 

	$array = array(
		"cleanText" => $CleanArt,
		"tags" => $ArticleParsed["Articles"][0]["MetadataCodes"],
		"words" => $ArticleParsed["Articles"][0]["WordCount"],
		"section" => $ArticleParsed["Articles"][0]["Section"],
		"headline" => $ArticleParsed["Articles"][0]["Title"][0]["Items"][0]["Value"]
		);

	//We return the array with the clean text and the tags etc.
	return $array;
}



function fetchArticle($ref){
	global $factiva_key;
	$url = "http://api.dowjones.com/api/public/2.0/Content/Article/ArticleRef/json?articleRef=$ref&encryptedToken=$factiva_key&ArticleFormat=FULL&Parts=RegionCodes|SubjectCodes|IndustryCodes";
    echo $url;
	$a = json_decode( file_get_contents( $url ), TRUE);

	return $a;
}

?>
