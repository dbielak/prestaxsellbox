<div class="panel">
    <h3>{l s='Możesz zsynchronizować ogłoszenia w serwisie sellbox z wystawionymi produktami w sklepie używając crona' mod='sellbox'}</h3>
    <pre>//{$smarty.server.HTTP_HOST}/modules/sellbox/check-items.php</pre>
    <p>{l s='Dodaj ten adres do zadań crona - musi wykonywać się najczęściej raz dziennie - w przeciwnym wypadku konto w serwisie sellbox zostanie zablokowane' mod='sellbox'}</p>
</div>

<div class="panel">
    <h3>{l s='Do czego służy moduł sellbox' mod='sellbox'}</h3>
    <p>
        {l s='Moduł sellbox pozwala wystawiać produkty bezpośrednio w serwisie sellbox.pl' mod='sellbox'}
        <br/>
        {l s='Wszystko co należy zrobić to założyć konto firmowe w serwisie sellbox.pl a następnie w zakładce sklep, wygenerować klucza API oraz uzuepłnić wszystkie niezbędne dane.' mod='sellbox'}
        <br/>
        {l s='Wygenerowany klucz API jest ściśle powiązany z nasza domeną dlatego moduł jest całkowicie bezpieczny.' mod='sellbox'}
    </p>
</div>
