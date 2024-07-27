<?php include('../_inc/header.php') ?>

    <h1>Скачать японско-русский словарь</h1>

    <p>Здесь вы можете скачать весь словарь целиком.</p>
    <p>
        Мы надеемся, что в число форматов, в которых распространяется словарь, будет со временем расти.
    </p>

    <p>
        <strong>Форма TXT:</strong>
        <a href="/download/warodai_txt.zip">http://www.warodai.ru/download/warodai_txt.zip</a> (<?= round(filesize('warodai_txt.zip')*.000001,2)?>Mb)
        <br/>
        <span style="font-size: smaller">Сборка от <?= date('d.m.Y',filemtime('warodai_txt.zip'))?></span>
    </p>

    <p>
        <strong>Формат EPWING:</strong>
        <a href="/download/warodai_epwing.zip">http://www.warodai.ru/download/warodai_epwing.zip</a> (<?= round(filesize('warodai_epwing.zip')*.000001,2)?>Mb)
        <br/>
        <span style="font-size: smaller">Сборка от <?= date('d.m.Y',filemtime('warodai_epwing.zip'))?></span>
    </p>

    <p>
        <strong>Формат Edict и инструмент для конвертации:</strong>
        <a href="https://github.com/update692/warodai-to-edict">https://github.com/update692/warodai-to-edict</a><br/>
        <span style="font-size: smaller">
            За версию спасибо <strong><a href="https://github.com/update692">update692</a></strong>.
        </span>
    </p>

    <p>
        <strong>Формат Edict2:</strong>
        <a href="/download/ewarodaiedict.zip">http://www.warodai.ru/download/ewarodaiedict.zip</a>(3.4 Mb)<br/>
        <span style="font-size: smaller">
            Сборка основана на оригинале от 26.10.2016<br/>
            За версию спасибо проекту <strong>Jardic</strong> и лично <strong>Виталию Загребельному</strong>.
        </span>
    </p>

    <p>
        <strong>Формат StarDict:</strong>
        <a href="/download/warodai_stardict.zip">http://www.warodai.ru/download/warodai_stardict.zip</a> (8Mb)
        <br/>
        <span style="font-size: smaller">
            Сборка от 27.03.2009
            <br/>
            Спасибо проекту <strong>RJ</strong> и лично <strong>Салиху Закирову</strong>
            за версию словаря в этом формате и скрипты конвертации.
        </span>
    </p>

    <!-- Ссылка не работает
    <p><strong>Формат Lingvo:</strong> <a
            href="http://www.vostokopedia.ru/index.php/Большой_японско-русский_словарь_(БЯРС)_для_Lingvo">http://www.vostokopedia.ru/index.php/Большой_японско-русский_словарь_(БЯРС)_для_Lingvo</a><br/>
<span style="font-size: smaller">Сборка от 09.05.2009, Версия 1.0 (основана на оригинале от 14.04.2009)&nbsp;<br/>
Спасибо LiBeiFeng и проекту&nbsp;</span><a href="http://www.vostokopedia.ru/"><span style="font-size: smaller">www.vostokopedia.ru</span></a><span
            style="font-size: smaller">&nbsp;&nbsp;за версию словаря в этом формате.</span></p>-->

    <p>
        <strong>Формат Lingvo x3:</strong>
        <a href="/download/warodai_lingvo3.zip">http://www.warodai.ru/download/warodai_lingvo3.zip</a> (8.5 Mb)<br/>
        <span style="font-size: smaller">
            Сборка основана на оригинале от 14.04.2009<br/>
            За версию спасибо <strong>TVA (anime4tv на мейлру)</strong>.
        </span>
    </p>

    <!--Слишком устарело
    <p>
        <strong>Формат AppleDict для словаря на MacOS:</strong>
        <a href="/download/WaroDaiJiten.dictionary.zip">http://www.warodai.ru/download/WaroDaiJiten.dictionary.zip</a> (41 Mb)<br/>
        <span style="font-size: smaller">
            Сборка основана на оригинале от 07.09.2012<br/>
            За версию спасибо <strong>Андрею Смирнову</strong>.
        </span>
    </p>
    -->

    <p>
        <strong>Формат для браузерного плагина <a href="https://foosoft.net/projects/yomichan/">Yomichan</a>:</strong>
        <a href="https://github.com/nillsondg/warodai_converter/blob/master/warodai-yomichan.zip?raw=true">https://github.com/nillsondg/warodai_converter/blob/master/warodai-yomichan.zip</a><br/>
        <span style="font-size: smaller">
            Сборка основана на оригинале от 11.04.2019<br/>
            За версию спасибо <strong>Дмитрию Гордееву</strong>.
        </span>
    </p>

    <!--　Ссылка не работает
    <p><strong>Формат для словаря, встроенного в Mac OS Leapard:</strong> <a class="postlink"
                                                                                href="http://zees.ru/wp-content/uploads/2009/08/Warodai.rar">http://zees.ru/wp-content/uploads/2009/08/Warodai.rar</a><br/>
        <span style="font-size: smaller">Спасибо&nbsp;Zees </span><span
            style="font-size: smaller">за версию словаря в этом формате.<br/>
Подробнее о том, как установить словарь: <a href="http://www.zees.ru/2009/08/09/большой-японско-русский-словарь-бярс/">http://www.zees.ru/2009/08/09/большой-японско-русский-словарь-бярс/</a> </span>
    </p> -->

    <p>
        <strong>Специальная версия WARODAI для программы <a href="http://www.jardic.ru">Jardic</a></strong><br/>
        <a href="http://www.jardic.ru/dictionaries/dictionaries_r.htm">http://www.jardic.ru/dictionaries/dictionaries_r.htm</a><br/>
        <span style="font-size: smaller">
            Спасибо проекту <strong>Jardic</strong> и лично <strong>Виталию Загребельному</strong>
            за включение словаря в программу.
        </span>
    </p>

    <p>
        <strong>Словарный файл для браузерного плагина Rikaichan:</strong>
        <a href="/download/rikaichan2.0_warodai.ru.xpi">www.warodai.ru/download/rikaichan2.0_warodai.ru.xpi</a><br/>
        <span style="font-size: smaller">
            Установить плагин для Firefox можно <a href="http://www.polarcloud.com/rikaichan">здесь</a><br/>
            Спасибо <strong>Zork Zero</strong> за работу!
        </span>
    </p>

    <p>
        
        <strong>Словарь в виде приложения для Android:</strong>
        <a target="_blank" href="http://market.android.com/details?id=com.ginkage.ejlookup">http://market.android.com/details?id=com.ginkage.ejlookup</a><br/>
        <span style="font-size: smaller">
            Спасибо <strong>Gin Kage</strong> за работу!
        </span>
    </p>
            
<?php include('../_inc/footer.php') ?>