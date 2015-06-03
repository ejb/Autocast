<?php
    
$factiva_key = getenv( 'FACTIVA_KEY' );
$articleRefs = fetchArticleList();
$articles = [];
foreach ($articleRefs as $index => $article) {
    cleanArticle( fetchArticle( $article['ref'] ) );
    array_push( $articles, $article );
}
echo json_encode( $articles );


function fetchArticleList(){
    
}

function CleanArticle($ArticleList) {
 
//We parse the dirty article list 
$ArticleString = file_get_contents($ArticleList);
$ArticleParsed = json_decode($ArticleString, true);
$Articles=$ArticleParsed["$ArticleParsed"][0]["Body"];

//Array of empty strings where cleaned articles are going to go
$CleanArticles = array_fill(0,sizeof($Articles),"");

foreach($Articles as $index => $ArticlesAux) {
	
	$i=0
	
	//Each article starts empty
	$CleanArt = "";
	
	//We fill each article	
	foreach($ArticlesAux as $index2 => $Art) {
		
		//After checking we ended on a full sentence, we only want the first 500 characters or so of each article
		if (substr($CleanArt, -1) != "." And strlen($CleanArt < 500)) {
			$CleanArt = $CleanArt . $Art[0]["Items"][0]["Value"];
		}
	}
	
	//We insert the full article into the right place in the list
	$CleanArticles[i] = $CleanArticles[i] . $CleanArt;	
}

//We return an array of cleaned articles
return $CleanArticles;

}

function fetchArticle($ref){
    
}

?>
