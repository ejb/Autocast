## WSJ entry for NewsHACK 7

*Currently unnamed project*

### Setup

Set Factiva API key as environment variable:

    export FACTIVA_KEY="XXXXXXXXXXXXXX"

Run this to cache articles:

    php fetch.php
    
Then either deploy to a PHP server, or run one locally using this:

    php -S localhost:8000