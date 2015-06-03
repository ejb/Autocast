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

function cleanArticle($article){
    
}

function fetchArticle($ref){
    
}

?>