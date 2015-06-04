(function() {

   $(function() {

      socialRiser.create();
      

      $('.play-button').click(function(){
          requestArticles();
      });
      

      var fm = Iframe.init(); // must be at the end of your code
   });

})();

function requestArticles(){
    $.getJSON('dummy.json', function(articles) {
        window.currentArticle = 0;
        speakText(articles[window.currentArticle], function(){
            window.currentArticle += 1;
            if (articles[window.currentArticle]) {
                speakText(articles[window.currentArticle]);
            }
        });
    });
}

function speakText( txt, callback ) {
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
