(function() {

   $(function() {

      socialRiser.create();
      
      window.inited = false;
      setTimeout(function(){
          $('.main-button').removeClass('init').addClass('paused');
      },1000);
      
      $('.main-button').click(function(){
          if (window.inited === false) {
              requestArticles();
          } else if (window.speechSynthesis.paused) {
              resume();
          } else {
              pause();
          }
      });
      
	  $(".skipBack").click(previous);
      // $(".soundOff").click();
	  $(".skipForward").click(next);
      

   });

})();

function requestArticles(){
    $.getJSON('cached.json', function(articles) {
        articles.sort(function(a,b){
            return b.score - a.score;
        });
        window.articles = articles;
        playArticle(0);
        window.inited = true;
    });
}

function playArticle( index ){
    window.currentArticle = index || window.currentArticle || 0;
    while (articles[window.currentArticle].headline === null) {
        window.currentArticle += 1;
    }
    var curr = articles[window.currentArticle];
    if (curr === undefined) {
        return;
    }
    console.log(curr, curr.headline);
    $('.main-button').removeClass('paused').addClass('playing');
    console.log(curr.headline);
    if (curr.headline && curr.headline.indexOf(';' > -1)) {
        curr.headline = curr.headline.split(';')[0];
    }
    setNowPlaying(curr.headline);
    
    speakText(curr.cleanText, function(){
        window.currentArticle += 1;
        playArticle( window.currentArticle );
    });
}

function speakText( txt, callback ) {
    if (!txt) {
        return;
    }
    console.log(txt);
    if (window.msg) {
        window.speechSynthesis.cancel();
        window.msg.removeEventListener("end");
    }
    window.msg = new SpeechSynthesisUtterance( txt );
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
    console.log('pause');
}

function resume(){
    window.speechSynthesis.resume();
    $('.main-button').removeClass('paused').addClass('playing');
}

function next(){
    window.speechSynthesis.cancel();
    window.currentArticle += 1;
    playArticle();
}

function previous(){
    window.speechSynthesis.cancel();
    window.currentArticle -= 1;
    playArticle();
}

function setNowPlaying( txt ) {
    $('.now-playing-title').text( txt );
}




