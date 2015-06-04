(function() {

   $(function() {

      socialRiser.create();
      
      window.inited = false;
      setTimeout(function(){
          $('.main-button').removeClass('init').addClass('paused');
      },500);
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
    $('.main-button').removeClass('paused').addClass('playing');
    setNowPlaying(curr.headline.split(';')[0]);
    
    speakText(curr.cleanText, function(){
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
    msg.rate = 0.9; // 0.1 to 10
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
    $('.main-button').removeClass('playing').addClass('paused');
}

function resume(){
    window.speechSynthesis.resume();
    $('.main-button').removeClass('paused').addClass('playing');
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




