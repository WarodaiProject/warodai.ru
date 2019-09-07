<?php include('../_inc/header.php') ?>
<form onsubmit="return false">
    <div class="position-relative">
            <input 
                type="text" 
                class="form-control" 
                onkeydown="initLookup(event)" 
                name="keyword" 
                id="keyword"
                placeholder="японское или русское слово"
            >
        
            <div id="search-btn" onclick="lookup()">
                <i class="fa fa-search"></i>
            </div>                                    
        </div>

        
    </form>

    <div id="results" class="container mt-4 px-0"></div>

    <div id="notes" class="text-muted">
        <p>В словаре можно искать:</p>
        <ul>
            <li>японские слова (например, <a href="#漫画">漫画</a>, <a href="#すごい">すごい</a>, <a href="#тайфу:">тайфу:</a>)</li>
            <li>русские слова (например, <a href="#школа">школа</a>)</li>
            <li>
                словосочетания и выражения на обоих языках (например, <a href="#работать над проектом">работать над проектом</a>, 
                <a href="#и на старуху бывает проруха">и на старуху бывает проруха</a>, <a href="#面から火が出る">面から火が出る</a>, 
                <a href="#風が出て来た">風が出て来た</a>)
            </li>
            <li>словарные карточки по их кодам (например, <a href="#1-230-2-29">1-230-2-29</a>)</li>
        </ul>
        <p>
            Для ввода японских слов можно помимо каны и иероглифов использовать 
            кириллическую транскрипцию по 
            <a href="https://ru.wikipedia.org/wiki/%D0%A1%D0%B8%D1%81%D1%82%D0%B5%D0%BC%D0%B0_%D0%9F%D0%BE%D0%BB%D0%B8%D0%B2%D0%B0%D0%BD%D0%BE%D0%B2%D0%B0" target="_blank">системе Поливанова</a>.
        </p>
        <p>
            Используйте символ "*" для поиска слов, заканчивающихся или начинающихся с заданной подстроки
            (например, <a href="#実*">実*</a>, <a href="#*家">*家</a>)
        </p>
    </div>

<?php include('../_inc/footer.php') ?>