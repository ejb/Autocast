(function() {

   $(function() {

      socialRiser.create();
      
      window.inited = false;
      $('.main-button').click(function(){
          if (!inited) {
              requestArticles();
          } else if (window.speechSynthesis.paused) {
              resume();
          } else {
              pause();
          }
      });
      

   });

})();

function requestArticles(){
    $.getJSON('cached.json', function(articles) {
        window.articles = articles;
        playArticle(0);
        window.inited = true;
    });
}

function playArticle( index ){
    window.currentArticle = index || window.currentArticle || 0;
    var curr = articles[window.currentArticle];
    if (curr === undefined) {
        return;
    }
    setNowPlaying('HED');
    speakText(curr, function(){
        window.currentArticle += 1;
        playArticle( window.currentArticle );
    });
}

function speakText( txt, callback ) {
    if (!txt) {
        return;
    }
    window.speechSynthesis.cancel();
    var msg = new SpeechSynthesisUtterance( txt );
    msg.rate = 1; // 0.1 to 10
    if (navigator.userAgent.match(/(iPad|iPhone|iPod touch)/i)){
        // iPhone speech rate is much faster, for some reason
        msg.rate = 0.3;
    }
    msg.lang = 'en-US';
    // var voices = window.speechSynthesis.getVoices();
    // msg.voice = voices[0];
    window.speechSynthesis.speak(msg);
    if (callback) {
        msg.addEventListener("end", callback);
    }
}

function pause(){
    window.speechSynthesis.pause();
}

function resume(){
    window.speechSynthesis.resume();
}

function next(){
    window.currentArticle += 1;
    playArticle();
}

function previous(){
    window.currentArticle -= 1;
    playArticle();
}

function setNowPlaying( txt ) {
    $('.now-playing-title').text( txt );
}




